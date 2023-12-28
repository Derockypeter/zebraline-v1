<template>
    <div class="row" id="otherRelatedProducts">
        <div class="container">
            <div class="col l12 m12 s12">
                <h2 class="center-align">You may also like</h2>
            </div>
            <div class="col l12 m12 s12">
                <div class="row">
                    <!-- Loop through your products here -->
                    <div
                        class="col l3 m6 s12"
                        v-for="product in relatedProducts"
                        :key="product.id"
                    >
                        <img
                            :src="product.images[0].url"
                            alt="Product Image"
                            loading="lazy"
                            class="responsive-img"
                        />
                        <p class="grey-text">{{ product.category.title }}</p>
                        <h3>
                            <router-link
                                :to="
                                    loggedIn
                                        ? `#!`
                                        : {
                                              name: `product-details`,
                                              params: {
                                                  product_name: product.title,
                                              },
                                          }
                                "
                                class="blue-text"
                                >{{ product.title }}</router-link
                            >
                        </h3>
                        <p class="grey-text price">
                            {{ formatPrice(product.amount, fx, location) }}
                        </p>
                        <div class="stars">
                            <span
                                v-for="i in 5"
                                :key="i"
                                class="far fa-star"
                                style="color: #ffc107"
                            ></span>
                        </div>
                        <div class="buttons">
                            <button class="btn" @click="addToCart(product)">
                                <i class="fas fa-shopping-cart left"></i> Add to
                                cart
                            </button>
                            <button class="btn">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="btn">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- End product loop -->
                </div>
            </div>
        </div>
    </div>
</template>
  
  <script>
    import { useCartStore } from "@/store/store";
    import priceMixin from "@/mixin/priceMixin";

    export default {
        props: {
            relatedProducts: Array, // Pass productId as a prop
            loggedIn: Boolean
        },
        mixins: [priceMixin],
        computed: {
            shortDescription() {
                if (this.product.description) {
                    const characterLimit = 350;
                    return this.product.description.length > characterLimit
                        ? this.product.description.slice(0, characterLimit) + "..."
                        : this.product.description;
                }
            },
            isWishlisted() {
                const cartStore = useCartStore();
                return cartStore.isItemInWishlist(this.product);
            },
            addingCartItem() {
                const cartStore = useCartStore();
                return cartStore.addingCartItem;
            },
            addedItemToCart() {
                const cartStore = useCartStore();
                return cartStore.addedItemToCart;
            },
            isItemInCart() {
                const cartStore = useCartStore();
                const productId = this.product.id;

                let found = cartStore.cartItems.find((item) => {
                    return item.product_id == productId;
                });

                if (found == undefined) {
                    return false;
                } else {
                    return true;
                }
            },
            isAuthenticated() {
                const cartStore = useCartStore();
                return cartStore.isAuthenticated;
            },
            fx() {
                const cartStore = useCartStore();
                return cartStore.fx;
            },
            location() {
                const cartStore = useCartStore();
                return cartStore.location;
            },
            reviewsEmpty() {
                if (this.product.reviews !== undefined) {
                    return true ? this.product.reviews.length == 0 : false;
                }
            },
        },
        data() {
            return {};
        },
        methods: {
            addToCart(product) {
                if (product.variants.length > 0) {
                    this.$route.push({ name: 'product-details', params: { product_name: product.title } });
                } else {
                    this.addingToCart(product);
                    var elems = document.querySelector("#addedToCart");
                    var instances = M.Modal.init(elems);
                    instances.open();
                    // var elems = document.querySelectorAll('.modal');
                    // var instances = M.Modal.init(elems);
                }
            },
            addingToCart(product) {
                product.quantity = 1;
                product.options = [];
                const cartStore = useCartStore();
                cartStore.addToCart(product);
            },
            wishlist() {
                if (this.isAuthenticated) {
                    this.addOrRemove();
                } else {
                    M.toast({
                        html: 'Hello, please &nbsp; <a href="/auth/signin"> Login</a>',
                        displayLength: 9000,
                        classes: "primary",
                    });
                }
            },
            addOrRemove() {
                const cartStore = useCartStore();
                let data = {
                    user_id: cartStore.user.id,
                    product_id: this.product.id,
                };
                cartStore.addToMyWishes(data);
            },
            checkWishlist(product) {
                let wished = this.$store.state.wishlist.find(
                    (el) => product.id == el.product_id
                );
                return wished;
            },
        },
    };
</script>
  
  <style scoped>
    #otherRelatedProducts {
        padding: 3vh 0 10vh;
    }
    h2 {
        font-size: 3rem;
        margin-bottom: 6vh;
    }
    .container {
        width: 88%;
        max-width: unset;
    }

    .buttons .btn {
        font-size: 0.8rem;
        margin-right: 0.5vw;
        color: #9e9e9e;
        background-color: #fff;
    }
    .buttons .btn:hover {
        /* border-color: #0084D6; */
        background-color: #0084d6;
        color: #fff;
    }
    .buttons .btn i {
        font-size: 0.95rem;
    }
    h3 {
        margin-top: 0;
        margin-bottom: 1vh;
        font-size: 1.5rem;
    }
    h3 a {
        color: #000;
    }
    h3 a:hover {
        color: #0084d6;
    }
    p.grey-text {
        margin-bottom: 0.7vh;
    }
    p.grey-text.price {
        margin-top: 0.6vh;
    }
    .stars {
        margin-bottom: 1.7vh;
    }
    /* Add your custom styles here */
</style>
  