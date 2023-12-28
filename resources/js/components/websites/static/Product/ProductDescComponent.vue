<template>
    <div class="container product-detail-container mt-4">
        <div class="row">
            <div class="product-image col l6 s12 m12">
                <div class="row">
                    <div class="col l12">
                        <img
                            :src="currentImage"
                            alt="Product Image"
                            class="responsive-img mainImage"
                        />
                    </div>
                </div>

                <div class="thumbnail-images row">
                    <div
                        v-if="
                            product &&
                            product.images &&
                            product.images.length <= 4
                        "
                    >
                        <div
                            v-for="(thumbnail, index) in product.images"
                            :key="index"
                            class="col l3 s4 m3"
                            :style="{
                                marginLeft: product.images.length == 1 ? 0 : '',
                            }"
                            @click="changeImage(thumbnail)"
                        >
                            <img
                                :src="thumbnail.url"
                                alt="Thumbnail"
                                class="responsive-img"
                            />
                        </div>
                    </div>
                    <div class="relative" v-else>
                        <div
                            class="flex space-between image-container"
                            ref="imageContainer"
                        >
                            <img
                                v-for="(thumbnail, index) in product.images"
                                :key="index"
                                :src="thumbnail.url"
                                alt="Thumbnail"
                                class=""
                                @click="changeImage(thumbnail)"
                            />
                        </div>
                        <div class="navigation">
                            <a
                                class="scroll-left waves-effect waves-light"
                                @click="scrollLeft"
                            >
                                <i class="material-icons">chevron_left</i>
                            </a>
                            <a
                                class="scroll-right waves-effect waves-light"
                                @click="scrollRight"
                            >
                                <i class="material-icons">chevron_right</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-details col l6 s12 m12">
                <h1 class="product-name">{{ product.title }}</h1>
                <p class="product-price">{{ formatPrice(product.amount, fx, location) }}</p>
                <div class="product-review row">
                    <div class="col l12 mg-bt-1">
                        <i
                            v-for="star in 5"
                            :key="star"
                            class="far fa-star"
                        ></i>
                    </div>
                    <div class="col l12">
                        <p class="product-description">
                            {{ shortDescription }}
                            <br />
                            <a class="modal-trigger" href="#productDescModal"
                                >Description</a
                            >
                        </p>
                    </div>
                </div>

                <div v-for="variant in product.variants" :key="variant.id">
                    <div
                        v-if="variant.name === 'Color'"
                        class="color-variations row"
                    >
                        <div class="col l12 header">
                            <h2>Colors:</h2>
                        </div>
                        <div class="col l12">
                            <div>
                                <div
                                    v-for="(color, index) in JSON.parse(
                                        variant.data
                                    )"
                                    :key="index"
                                    class="color-circle"
                                    :style="{
                                        backgroundColor: color.name,
                                        border: isSelected(variant, color)
                                            ? '2px solid #000'
                                            : 'none',
                                    }"
                                    @click="selectOption(variant, color)"
                                ></div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="variant.name === 'Size'"
                        class="size-variations row"
                    >
                        <div class="col l12 header">
                            <h2>Sizes:</h2>
                        </div>
                        <div class="col l12">
                            <div>
                                <a
                                    v-for="(size, index) in JSON.parse(
                                        variant.data
                                    )"
                                    :key="index"
                                    href="#!"
                                    class="size-link"
                                    :style="{
                                        border: isSelected(variant, size)
                                            ? '2px solid #000'
                                            : '1px solid grey',
                                    }"
                                    @click="selectOption(variant, size)"
                                    >{{ size.name }}</a
                                >
                            </div>
                        </div>
                    </div>
                    <div
                        v-else-if="
                            (variant.name != 'Size') & (variant.name != 'Color')
                        "
                        class="size-variations"
                    >
                        <div class="col l12 header">
                            <h2>{{ variant.name }}:</h2>
                        </div>
                        <div class="row">
                            <a
                                v-for="(custom, index) in JSON.parse(
                                    variant.data
                                )"
                                :key="index"
                                href="#!"
                                class="size-link col s3"
                                :style="{
                                    border: isSelected(variant, custom)
                                        ? '2px solid #000'
                                        : '1px solid grey',
                                }"
                                @click="selectOption(variant, custom)"
                                >{{ custom.name }}</a
                            >
                        </div>
                    </div>
                </div>

                <div class="quantity-container row">
                    <div class="input-field col l4">
                        <span
                            class="productQtyCOntrol"
                            @click="decrementQuantity"
                            ><i class="fas fa-minus"></i
                        ></span>
                        <input type="number" v-model="quantity" />
                        <span
                            class="productQtyCOntrol"
                            @click="incrementQuantity"
                            ><i class="fas fa-plus"></i
                        ></span>
                    </div>
                    <div class="add-to-cart col l8">
                        <a
                            class="waves-effect waves-light btn"
                            @click="addToCart(product)"
                            ><i class="fas fa-shopping-cart"></i> Add to Cart</a
                        >
                    </div>
                </div>
                <div class="row buyNowWish">
                    <div class="col l10 m10 s8">
                        <a
                            href="#"
                            @click="buyNow"
                            class="col s12 btn buy-now-button"
                            >Buy Now</a
                        >
                    </div>
                    <div class="col l2 m2 s4">
                        <a
                            href="#"
                            class="col s12 btn wishlist-link"
                            @click="wishlist"
                            ><i
                                class="far fa-heart"
                                :class="{ 'red-text': isWishlisted }"
                            ></i
                        ></a>
                    </div>
                </div>
                <div class="social-media-icons">
                    <a
                        href="#"
                        class="social-media-icon"
                        @click="shareOnFacebook"
                        ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a
                        href="#"
                        class="social-media-icon"
                        @click="shareOnTwitter"
                        ><i class="fab fa-twitter"></i
                    ></a>
                    <a href="#" class="social-media-icon"
                        ><i class="fab fa-instagram"></i
                    ></a>
                </div>
            </div>
        </div>
        <!-- Modal Structure -->
        <div id="productDescModal" class="modal">
            <div class="modal-content">
                <h4>{{ product.title }}</h4>
                <div class="row">
                    <div class="col l12">
                        <ul class="tabs tabs-fixed-width">
                            <li class="tab col l3">
                                <a class="active" href="#test1">Description</a>
                            </li>
                            <li class="tab col l3">
                                <a href="#review">Reviews</a>
                            </li>
                        </ul>
                    </div>
                    <div id="test1" class="col l12">
                        <p>
                            {{ product.description }}
                        </p>
                    </div>
                    <div id="review" class="col l12">
                        <h5>Customer reviews</h5>
                        <div>
                            <div class="flex gap-2vw separator mb-4" v-for="review in product.reviews" :key="review">
                                <div class="">
                                    <div class="circle">
                                        <span>{{ getInitials(review.user.names) }}</span>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="flex justify-between">
                                        <h6 class="reviewer fw-600">{{ review.user.names }}</h6>
                                        <p class="m-0 reviewDate">{{ moment(review.updated_at).format('MMMM Do YYYY') }}</p>
                                    </div>
                                    <div>
                                        <div class="rating">
                                            <i v-for="i in review.rating" :key="i" class="fa fa-star" :class="{ 'active': i <= review.rating }"></i>
                                            <i v-for="i in 5 - review.rating" :key="i" class="fa fa-star"></i>
                                        </div>
                                        <p class="comment">
                                            {{ review.comment }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="center-align" v-if="reviewsEmpty">
                            No Reviews on this product yet!
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div id="addedToCart" class="modal">
            <div class="modal-content">
                <h6>Product Successfully Added to cart</h6>
                <div class="suc">
                    <i class="fa-solid fa-check fa-4x text-white"></i>
                </div>
            </div>
            <div class="modal-footer">
                <a
                    href="/checkout"
                    class="modal-close waves-effect waves-green btn-flat"
                    >Checkout Now</a
                >
            </div>
        </div>
    </div>
</template>
  
  <script>
    import { useCartStore } from "@/store/store";
    import priceMixin from "@/mixin/priceMixin";
    import moment from "moment";

    export default {
        data() {
            return {
                currentImage: "",
                thumbnails: [
                    "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/big-product3.jpg",
                    "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/big-product4.jpg",
                    "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/big-product5.jpg",
                    "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/big-product6.jpg",
                ],
                productName: "Product Name",
                productPrice: "$100",
                productDescription:
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla commodo euismod sapien, non scelerisque dui laoreet sit amet. Nulla commodo euismod sapien, non scelerisque dui laoreet sit amet.",
                colors: ["red", "blue", "green"],
                sizes: ["S", "M", "L", "XL"],
                quantity: 1,
                user: null,
                selectedOptions: {},
            };
        },
        mounted() {
            M.AutoInit();
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
            }
        },
        methods: {
            moment(arg) {
                return moment(arg);
            },
            getInitials(name) {
                const words = name.split(" ");
                
                let initials = "";
                
                for (let i = 0; i < words.length; i++) {
                    initials += words[i][0].toUpperCase();
                }
                
                return initials;
            },
            buyNow() {
                if (this.product.variants.length > 0) {
                    if (
                        Object.entries(this.selectedOptions).length !==
                        this.product.variants.length
                    ) {
                        M.toast({
                            html: "Please select an option to continue!",
                        });
                    } else {
                        window.open("/checkout");
                    }
                } else {
                    this.addingToCart(this.product);
                    window.open("/checkout");
                }
            },
            scrollLeft() {
                this.$refs.imageContainer.scrollLeft -= 200; // Adjust the scroll amount
            },
            scrollRight() {
                this.$refs.imageContainer.scrollLeft += 200; // Adjust the scroll amount
            },
            selectOption(variant, option) {
                // Store the selected option for the variant
                this.selectedOptions[variant.name] = option.name;
            },
            isSelected(variant, option) {
                // Check if the option is selected for the variant
                return this.selectedOptions[variant.name] === option.name;
            },
            shareOnFacebook() {
                const url = window.location.href; // URL to share
                window.open(
                    `http://www.facebook.com/share.php?u=${encodeURIComponent(url)}`
                );
            },
            shareOnTwitter() {
                const url = window.location.href; // URL to share
                const text = `Check out this product on ${url}`; // Tweet text

                const tweetUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(
                    url
                )}&text=${encodeURIComponent(text)}`;

                window.open(tweetUrl, "_blank");
            },
            changeImage(imageSrc) {
                this.currentImage = imageSrc.url;
            },
            incrementQuantity() {
                this.quantity++;
            },
            decrementQuantity() {
                if (this.quantity > 1) {
                    this.quantity--;
                }
            },
            addToCart(product) {
                if (this.product.variants.length > 0) {
                    if (
                        Object.entries(this.selectedOptions).length !==
                        this.product.variants.length
                    ) {
                        M.toast({
                            html: "Please select to add to cart!",
                        });
                    } else {
                        this.addingToCart(product);
                        var elems = document.querySelector("#addedToCart");
                        var instances = M.Modal.init(elems);
                        instances.open();
                    }
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
                product.quantity = this.quantity;
                product.options = this.selectedOptions;
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
        props: {
            product: Object,
        },
        watch: {
            product(newval) {
                if (newval) {
                    this.currentImage = newval.images[0].url;
                }
            },
        },
    };
</script>
  
  <style scoped>
    .gold {
        color: #ffc107;
    }
    .gap-2vw {
        gap: 2vw;
    }
    .circle {
        border-radius: 50%;
        width: 5.5vw;
        height: 10.5vh;
        background-color: rgb(202, 200, 200);

        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;

        font-size: 2.5rem;
    }
    .reviewer {
        font-size: 1.6rem;
        font-family: 'Jost', sans-serif;
    }
    .reviewDate {
        padding: 0 1rem;
        height: 4.2rem;
        line-height: 4rem;
        font-size: 1.5rem;
        font-family: 'Jost', sans-serif;
        border: 1px solid #e4e4e4;
        border-radius: 5px;
    }
    .comment {
        font-size: 1.5rem;
        line-height: 2.8rem;
        color: #606060;
        font-weight: 400;
        font-family: 'Jost', sans-serif;
    }
    .separator {
        border-bottom: 1px solid #e4e4e4;
    }
    #review {
        font-family: 'Jost', sans-serif;
    }
    .rating .fa-star.active {
        color: gold;
    }
    #addedToCart .modal-content {
        display: flex;
        flex-direction: column;
        gap: 4vh;
        justify-content: center;
        align-items: center;
    }
    .suc {
        background-color: green;
        color: #fff;
        width: 7vh;
        height: 7vh;
        line-height: 7vh;
        text-align: center;
        border-radius: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .image-container {
        display: flex;
        flex-wrap: nowrap; /* Prevent images from wrapping to the next line */
        overflow-x: hidden; /* Horizontal scrollbar for overflow */
        gap: 10px; /* Spacing between images */
        padding: 10px; /* Padding for better styling */
    }
    .image-container {
        padding: 5px; /* Add spacing between images */
    }

    .image-container:hover + .navigation,
    .navigation:hover {
        display: block;
    }

    .navigation {
        text-align: center;
        position: absolute;
        width: 100%;
        top: 47%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
    }

    .scroll-left,
    .scroll-right {
        font-size: 24px;
        margin: 5px;
        width: 5vh;
        height: 5vh;
        background-color: var(--primary-color);
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 100%;
    }

    .scroll-left:hover,
    .scroll-right:hover {
        background-color: #061738;
    }

    .scroll-left {
        float: left;
    }

    .scroll-right {
        float: right;
    }

    .image-container img {
        max-width: 140px !important; /* Adjust the maximum width as needed */
        height: auto;
        cursor: pointer;
    }
    .row {
        margin-bottom: 5vh;
    }
    .product-detail-container {
        display: flex;
        justify-content: space-between;
        width: 70%;
    }
    .product-image {
        width: 70%;
    }
    .product-image img {
        width: 100%;
        max-width: unset; /* Remove max-width for responsive-img */
    }
    /* .mainImage {
                        height: 70vh;
                    } */
    .thumbnail-images {
        display: flex;
        /* justify-content: space-between; */
    }
    .thumbnail-images .l3 {
        cursor: pointer;
    }
    .product-details {
        width: 28%;
        padding: 0 20px;
    }
    .product-name {
        margin: 0.5vh 0;
        font-size: 3rem;
    }
    p.product-price {
        font-size: 1.5rem;
        font-weight: 500;
        margin-top: 1vh;
    }
    .product-review {
        font-size: 18px;
    }
    p.product-description {
        margin: 0;
    }

    h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 500;
    }
    .size-variations .header,
    .color-variations .header {
        padding-bottom: 1vh;
    }
    .color-circle {
        display: inline-block;
        width: 3vh;
        height: 3vh;
        border-radius: 50%;
        margin-right: 1vh;
    }
    .quantity-container {
        display: flex;
        justify-content: space-between;
        padding-top: 1.5vh;
    }
    .quantity-container .input-field {
        margin: 0;
        display: flex;
        align-items: center;
    }
    .quantity-container .input-field input {
        text-align: center;
        margin-bottom: 0;
        border-bottom: unset;
    }
    input[type="number"]:not(.browser-default):focus:not([readonly]) {
        border-bottom: unset;
        box-shadow: unset;
    }
    .quantity-input {
        display: flex;
    }
    .productQtyCOntrol {
        background-color: rgb(234, 235, 236);
        color: black;

        padding: 1.5vh 1vw;
        border-radius: 50%;
    }
    .add-to-cart .btn,
    .buyNowWish div:first-child .btn {
        background-color: var(--primary-color);
        color: #fff;
        height: 6vh;
        line-height: 6vh;
    }
    .buyNowWish div:last-child .btn {
        background-color: #fff;
        color: var(--primary-color);
        height: 6vh;
        line-height: 6vh;
    }
    .add-to-cart .btn:hover {
        background-color: #fff;
        color: var(--primary-color);
    }
    .wishlist-favorite {
        color: var(--primary-color);
    }
    .size-link {
        text-decoration: none;
        border: 1px solid grey;
        padding: 5px 10px;
        margin-right: 10px;
        color: black;
        margin-top: 1vh;
    }
    .social-media-icon {
        background-color: var(--primary-color);
        color: white;
        text-align: center;
        width: 2.5vw;
        height: 2.5vw;
        line-height: 2.5vw;
        border-radius: 50%;
        margin-right: 1vw;
        text-decoration: none;
        display: inline-block;
        font-size: 1vw;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type="number"] {
        -moz-appearance: textfield; /* Firefox */
    }
    .mg-bt-1 {
        margin-bottom: 1vh;
    }
    @media only screen and (min-width: 768px) and (max-width: 1023px) {
        .container {
            width: 85%;
        }
        input[type="number"] {
            width: 30%;
        }
        .productQtyCOntrol {
            padding: 1.5vh 2.5vw;
        }
        .social-media-icon {
            width: 5.5vw;
            height: 5.5vw;
            line-height: 5.5vw;
            font-size: 1.5rem;
        }
    }
    @media only screen and (max-width: 767px) {
        .fa-4x {
            font-size: 2em;
        }
        .container {
            width: 90%;
        }
        .productQtyCOntrol {
            padding: 1.5vh 4vw;
        }
        .social-media-icon {
            width: 6.5vw;
            height: 6.5vw;
            line-height: 6.5vw;
            font-size: 1rem;
        }
    }
</style>
  