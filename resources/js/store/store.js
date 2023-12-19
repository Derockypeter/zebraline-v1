import { defineStore } from 'pinia';

export const useCartStore = defineStore('store', {
    state: () => ({
        cartItems: [],
        publicKey: "",
        wishlists: [],
        fetchingCartItems: true,
        removingCartItem: {
            status: false,
            itemId: 0
        },
        addingCartItem: false,
        addedItemToCart: false,
        isAuthenticated: false, // Example state
        accessToken: null, // Example state
        user: null,
        fx: null,
        location: null,
    }),
    getters: {
        cartTotal() {
            return this.cartItems.reduce((total, item) => {
                return total + item.product.amount * item.quantity;
            }, 0);
        },
        cartCount() {
            // return this.cartItems.reduce((count, item) => {
            //     return count + item.quantity;
            // }, 0);
            return this.cartItems.length;
        },
        wishlistItemCount: (state) => state.wishlists.length,
        selectedItemsTotal() {
            return this.cartItems.reduce((total, item) => {
                if (item.selected == 1) {
                    return total + item.product.price * item.quantity;
                }
                return total;
            }, 0);
        },
        getSelectedItem: state => {
            return state.selectedItem;
        },
    },
    actions: {
        getPublicKey(value) {
            this.publicKey = value;
        },
        updateUser(value, user) {
            this.isAuthenticated = value;
            this.user = user;
        },
        async fetchJSON(request) {
            try {
              const response = await fetch(request);
              const contentType = response.headers.get("content-type");
              if (!contentType || !contentType.includes("application/json")) {
                throw new TypeError("Oops, we haven't got JSON!");
              }
              const jsonData = await response.json();
              return jsonData;
            } catch (error) {
              console.error("Error:", error);
            }
        },
        async getIPFromAmazon() {
            // let location = await this.fetchJSON(`https://api.ipdata.co?api-key=${process.env.MIX_IPDATA_API}&fields=currency`);
            // let exchange = await this.fetchJSON(`https://openexchangerates.org/api/latest.json?app_id=${process.env.MIX_OPENXCHANGE_API}`);
            // fx.base = exchange.base;
            // fx.rates = exchange.rates;
            // this.fx = fx;
            // this.location = location;
        },
        async fetchCart() {
            try {
                if (this.isAuthenticated) {
                    const response = await axios.get('/carts');
                    if (response.status === 200) {
                        this.cartItems = response.data;
                    }
                } else {
                    const localCart = localStorage.getItem('cart');
                    if (localCart) {
                        this.cartItems = JSON.parse(localCart);
                    }
                }
                this.fetchingCartItems = false;
            } catch (error) {
                console.error('Failed to fetch item to the database', error);
            }
        },

        async addToCart(product) {
            this.addingCartItem = true;
            try {
                if (this.isAuthenticated) {
                    let data = {
                        product_id: product.id,
                        quantity: product.quantity || 1,
                        options: product.options,
                    }
                    let doneAddingToCart = await axios.post('/carts', data);
                    if (doneAddingToCart.status === 200) {
                        this.addedItemToCart = true;
                        this.addingCartItem = false;
                        this.cartItems = doneAddingToCart.data.cart;
                        // M.toast({
                        //     html: 'Item added to cart',
                        //     classes: 'successNotifier'
                        // });
                    }
                } else {
                    // User is not logged in, check the local storage cart
                    const localCart = JSON.parse(localStorage.getItem('carts')) || [];
                    const existingCartItemIndex = localCart.findIndex(item => {
                        // Check if the product ID and options are the same
                        const sameProduct = item.product_id === product.id;
                        const sameOptions = JSON.stringify(item.options) === JSON.stringify(product.options);
                        return sameProduct && sameOptions;
                    });

                    if (existingCartItemIndex !== -1) {
                        // Item with the same product ID and options exists, increase quantity
                        localCart[existingCartItemIndex].quantity += product.quantity || 1;
                    } else {
                        // Item with the same product ID and options does not exist, add it to localCart
                        localCart.push({
                            product_id: product.id,
                            product: product,
                            quantity: product.quantity || 1,
                            options: JSON.stringify(product.options),
                        });
                    }
                    // Save the updated localCart to local storage
                    localStorage.setItem('cart', JSON.stringify(localCart));
                    this.cartItems = localCart;

                    // M.toast({
                    //     html: 'Item added to cart',
                    //     classes: 'successNotifier'
                    // });
                }
            } catch (error) {
                console.error('Failed to save item to the database', error);
                M.toast({
                    html: 'Failed to save' + error,
                    classes: 'errorNotifier'
                });
            }
        },

        async removeFromCart(productId) {
            this.removingCartItem.status = true;
            this.removingCartItem.itemId = productId;
            // Find the index of the cart item with the matching product ID
            const index = this.cartItems.findIndex(item => item.id === productId);

            if (index !== -1) {
                try {
                    let itemRemoved = await axios.delete(`/cart/${productId}`);
                    if (itemRemoved.status == 200) {
                        // Remove the item from the cartItems array
                        this.cartItems.splice(index, 1);

                        // M.toast({
                        //     html: 'Item Removed from Cart',
                        //     classes: 'successNotifier'
                        // })
                    }
                } catch (error) {
                    console.log(error);
                    M.toast({
                        html: 'Error Removing from Cart' + error,
                        classes: 'errorNotifier'
                    })
                }

            }

        },
        
        async clearCart() {
            try {
                const response = await axios.get(`/cart_clear`);
                if (response.status == 204) {
                    this.cartItems = [];
                }
            } catch (error) {
                console.log(error);
                M.toast({
                    html: 'Error Clearing from Cart' + error,
                    classes: 'errorNotifier'
                })
            }
        },
        setCartItems(items) {
            this.cartItems.push(items);
            localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
        },
        setSelectedToCheckout(items) {
            this.checkoutItems.push(items);
        },
        async storeCartItemsLoop(products) {
            try {
                products.map(async product => {
                    let data = {
                        product_id: product.id,
                        quantity: product.qty || 1,
                    }
                    await axios.post('/carts', data);
                    console.log('Item saved to the database');
                })
            } catch (error) {
                console.error('Failed to save item to the database', error);
            }
        },

        async updateCartItemQuantity(itemId, newQuantity) {
            try {
                const item = this.cartItems.find(item => item.id === itemId);

                if (item && newQuantity >= 1) {
                    item.quantity = newQuantity;

                    // Make an API request to update the quantity in the database
                    await axios.put(`/carts/${itemId}`, { quantity: newQuantity });
                }
            } catch (error) {
                console.error('Failed to update quantity in the database', error);
                M.toast({
                    html: 'Failed to update quantity' + error,
                    classes: 'errorNotifier'
                });
            }
        },

        async increaseQuantity(itemId) {
            const item = this.cartItems.find(item => item.id === itemId);
            if (item) {
                this.updateCartItemQuantity(itemId, item.quantity + 1);
            }
        },

        async decreaseQuantity(itemId) {
            const item = this.cartItems.find(item => item.id === itemId);
            if (item && item.quantity > 1) {
                this.updateCartItemQuantity(itemId, item.quantity - 1);
            }
        },

        async updateSelectedStatus(itemId, selected, selectedState) {
            const item = this.cartItems.find(item => item.id === itemId);
            if (item) {
                item.selected = selected;
                try {
                    let cartContent = await axios.put(`/carts/selected/${itemId}`, { selected });

                    if (cartContent.status == 200) {
                        if (selectedState == null) {
                            item.selected = 1;
                        } else {
                            item.selected = null;
                        }

                    }
                } catch (error) {
                    console.error('Failed to update selected status in the database', error);
                    M.toast({
                        html: 'Failed to update' + error,
                        classes: 'errorNotifier'
                    })
                }
            }
        },

        async fetchUserWishlists() {
            if (this.isAuthenticated) {
                try {
                    const response = await axios.get(`/user-wishlist`)
                    this.wishlists = response.data.wishlists;
                }
                catch (error) {
                    console.log(error);
                    M.toast({
                        html: 'An Error occured while fetching wishlists',
                        classes: 'errorNotifier',
                    });
                }
            }
        },
        async addToMyWishes(item) {
            try {
                const response = await axios.post("/wishlist", item);
                if (response.status === 201) {
                    this.addToWishlist(item);
                    // M.toast({
                    //     html: 'Added to wishlist',
                    //     classes: 'successNotifier',
                    // });
                } else {
                    this.removeFromWishlist(item);
                    // M.toast({
                    //     html: 'Removed from wishlist',
                    //     classes: 'successNotifier',
                    // });
                }
                this.fetchUserWishlists();
            } catch (error) {
                console.log(error);
                M.toast({
                    html: 'An Error occured while posting wishlists',
                    classes: 'errorNotifier',
                });
            }
        },
        addToWishlist(item) {
            this.wishlists.push(item);
        },
        removeFromWishlist(item) {
            const index = this.wishlists.findIndex((i) => i.id === item.id);
            if (index !== -1) {
                this.wishlists.splice(index, 1);
            }
        },
        clearWishlist() {
            this.wishlists = [];
        },
        isItemInWishlist(item) {
            return this.wishlists.some((i) => i.product_id === item.id);
        },
    },
});