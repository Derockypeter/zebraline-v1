import priceMixixn from "@/mixin/priceMixin";
import { useCartStore } from "@/store/store";
export default {
    mixins: [priceMixixn],
    computed: {
        displayedProducts() {
            if (this.products && this.products.length > 0) {
                return this.products;
            } else if (this.seededProducts && this.seededProducts.length > 0) {
                return this.seededProducts;
            } else {
                return [];
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
        sliceSixCate() {
            if (this.categories != undefined) {
                return this.categories.slice(0, 6);
            }
        },
        sliceSvenEnd() {
            if (this.categories != undefined) {
                return this.categories.slice(7, 12);
            }
        },
        mailUs() {
            return (
                "mailto:" +
                this.email +
                "?subject=Contact%20Us&body=Hello%20Team"
            );
        },
        brandShortsDesc() {
            if (this.branddesc !== "") {
                return this.branddesc;
            } else {
                return "Pulvinar aenean dignissim porttitor sed risus urna, pretium quis non id.";
            }
        },
        role() {
            const cartStore = useCartStore();
            const role = cartStore.user.role;
            return role;
        },
        names() {
            const cartStore = useCartStore();
            const names = cartStore.user.names;
            const nameParts = names.split(" ");
            return nameParts[0];
        },
        cartCount() {
            const cartStore = useCartStore();
            return cartStore.cartCount;
        },
        wishlistCount() {
            return useCartStore().wishlistItemCount;
        },
        sortedReviews() {
            if (this.reviews !== undefined) {
                return this.reviews.slice().sort((a, b) => b.rating - a.rating);
            }
        },
    },
    methods: {
        showBlogEditor() {
            if (this.loggedIn) {
                this.$emit("showEditBlogMenu", true);
            }
        },

        showCategoryEditEditor(mode = true) {
            if (this.loggedIn) {
                this.$emit("showEditNavMenu", {
                    evt: mode,
                    width: this.imgDimensionWidth,
                    height: this.imgDimensionHeight,
                });
            }
        },

        showProductEditor() {
            if (this.loggedIn) {
                this.$emit("showProductEditor", true);
            }
        },

        showOffersEditor() {
            if (this.loggedIn) {
                this.$emit("showOffersEditor", {
                    evt: true,
                    width: this.imgDimensionWidth,
                    height: this.imgDimensionHeight,
                });
            }
        },

        renderImage(prd) {
            if (prd !== undefined && prd.images[0] !== undefined) {
                return prd.images[0].url;
            }
        },

        initCarousel() {
            let elem = document.getElementById("promo");
            let instance = M.Carousel.init(elem, {
                fullWidth: true,
                indicators: true,
            });

            // Autoplay functionality
            let autoplayInterval = 4500; // Delay between slide transitions in milliseconds
            let autoplayInstance = setInterval(() => {
                instance.next();
            }, autoplayInterval);

            // Pause autoplay on mouseover and resume on mouseout
            elem.addEventListener("mouseenter", () => {
                clearInterval(autoplayInstance);
            });

            elem.addEventListener("mouseleave", () => {
                autoplayInstance = setInterval(() => {
                    instance.next();
                }, autoplayInterval);
            });

            window.next = function () {
                var el = document.querySelector(".carousel");
                var l = M.Carousel.getInstance(el);
                l.next(1);
            };

            //carousel previous function
            window.prev = function () {
                var el = document.querySelector(".carousel");
                var l = M.Carousel.getInstance(el);
                l.prev(1);
            };
        },

        showHeroEditor() {
            if (this.loggedIn) {
                this.$emit("showHeroEditor", {
                    evt: true,
                    width: this.imgDimensionWidth,
                    height: this.imgDimensionHeight,
                });
            }
        },

        showSellingPointEditor() {
            if (this.loggedIn) {
                this.$emit(`showSellingPointEditor`, true);
            }
        },

        showSocialEditor() {
            this.$emit("showSocialEditor", true);
        },

        wishlist(prd) {
            if (this.isAuthenticated) {
                this.addOrRemove(prd);
            } else {
                M.toast({
                    html: 'Hello, please &nbsp; <a href="/auth/signin"> Login</a>',
                    displayLength: 9000,
                    classes: "primary",
                });
            }
        },

        addOrRemove(prd) {
            const cartStore = useCartStore();
            let data = {
                user_id: cartStore.user.id,
                product_id: prd.id,
            };
            cartStore.addToMyWishes(data);
        },

        checkWishlist(product) {
            let wished = useCartStore().wishlists.find(
                (el) => product.id == el.product_id
            );
            return wished;
        },
        // Get product title
        title(prd) {
            if (prd !== undefined) {
                if (prd.title.length > 15) {
                    return prd.title.slice(0, 15) + "...";
                } else {
                    return prd.title;
                }
            }
        },
        comment(review) {
            if (review.length > 200) {
                return review.slice(0, 200) + '...';
            } else {
                return review;
            }
        },
        getInitials(name) {
            const words = name.split(" ");

            let initials = "";

            for (let i = 0; i < words.length; i++) {
                initials += words[i][0].toUpperCase();
            }

            return initials;
        },
        getRandomColor() {
            return '#' + Math.floor(Math.random() * 16777215).toString(16);
        },
        getTextColor(bgColor) {
            // Determine whether to use light or dark text based on background color brightness
            const r = parseInt(bgColor.slice(1, 3), 16);
            const g = parseInt(bgColor.slice(3, 5), 16);
            const b = parseInt(bgColor.slice(5, 7), 16);
            const brightness = (r * 299 + g * 587 + b * 114) / 1000;

            return brightness > 125 ? '#000000' : '#ffffff';
        },
        itemStyle(item) {
            const color = item.color || this.getRandomColor();
            const textColor = this.getTextColor(color);
            return {
                backgroundColor: color,
                color: textColor,
            };
        },
    },
    props: {
        loggedIn: Boolean,

        blogs: Array,

        // Categories
        categories: Array,
        catetimestamp: Number,

        products: Array,

        // Footer
        brandname: String,
        socials: Object,
        email: String,
        branddesc: String,

        // HeroComponent
        hero: Array,
        timestamp: Number,
        defaultHero: Number,

        // Offers
        offers: Array,

        // SellPoint
        sellPoint: Array,

        // Reviews
        reviews: Array,
    },
    watch: {
        blogs: {
            handler(newBlog) {
                if (newBlog.length > 0) {
                    this.blogSeeder = [...newBlog]; // Make a copy of the new products
                }
            },
            deep: true, // Enable deep watching to detect changes within the array
        },
        categories: {
            handler(newVal) {
                if (this.seeder !== undefined) {
                    if (newVal) {
                        newVal.forEach((category, i) => {
                            if (i < this.seeder.length) {
                                // Update existing seeder items
                                this.seeder[i].name = category.name;
                                this.seeder[i].image =
                                    category.image || this.seeder[i].image;
                                this.seeder[i].description =
                                    category.description ||
                                    this.seeder[i].description;
                            } else {
                                // Create new seeder items if there are more categories
                                this.seeder.push({
                                    name: category.name,
                                    image: category.image || null,
                                    description: category.description || null,
                                });
                            }
                        });
                    }
                }
            },
            deep: true,
        },
        products: {
            handler(newProducts) {
                if (newProducts.length > 0) {
                    this.seededProducts = [...newProducts]; // Make a copy of the new products
                }
            },
            deep: true, // Enable deep watching to detect changes within the array
        },
        socials(newVal) {
            this.social = {
                facebook: "",
                youtube: "",
                tiktok: "",
                twitter: "",
                instagram: "",
            };
            if (newVal !== null && newVal !== undefined) {
                if (Object.entries(newVal).length !== 0) {
                    this.social = newVal;
                }
            }
        },
        hero: {
            handler(newVal, oldVal) {
                if (newVal.length > 0) {
                    if (this.heroSeeder !== undefined) {
                        if (this.defaultHero == undefined) {
                            this.heroSeeder = newVal[0];
                        } else {
                            this.heroSeeder = newVal;
                            this.initCarousel();
                            M.AutoInit();
                        }
                    }
                } else if (this.heroSeeder !== undefined) {
                    if (this.defaultHero == undefined) {
                        this.heroSeeder = this.heroSeeder[0];
                    }
                }
            },
            deep: true
        },
        offers(newVal) {
            if (Object.entries(newVal).length > 0) {
                this.offerSeeder = newVal[0];
            }
        },
    },
    mounted() {
        // Get the button element by its ID
        const scrollToTopButton = document.getElementById("scrollToTopButton");
        if (scrollToTopButton) {
            scrollToTopButton.addEventListener("click", () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth",
                });
            });

            window.addEventListener("scroll", () => {
                if (
                    document.body.scrollTop > 20 ||
                    document.documentElement.scrollTop > 20
                ) {
                    scrollToTopButton.style.display = "block";
                } else {
                    scrollToTopButton.style.display = "none";
                }
            });
        }

        this.emitter.on("openNtargetEditClickedOn", (data) => {
            let mode = data.msg.mode;
            let editOn = data.msg.editOn;
            if (editOn === 0) {
                this.showHeroEditor(mode);
            } else if (editOn === 1) {
                this.showCategoryEditEditor(mode);
            }  else if (editOn === 2) {
                this.showProductEditor(mode);
            }  else if (editOn === 3) {
                this.showOffersEditor(mode);
            } else if (editOn === 4) {
                this.showSellingPointEditor(mode);
            } else if (editOn === 5) {
                this.showSocialEditor(mode);
            }
        });
    },
};
