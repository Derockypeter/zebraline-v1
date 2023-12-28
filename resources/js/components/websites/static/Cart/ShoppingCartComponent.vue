<template>
    <NavbarComponent :categories="categories" />
    <div class="row noMarginBottom" id="shoppingCartBanner">
        <div class="col s12 m12 l12 center-align">
            <h1 class="noMarginTop">Shopping Cart</h1>
        </div>
        <div class="col s12 m12 l12 center-align">
            <a href="/" class="breadcrumb">Home</a>
            <a href="#!" class="breadcrumb">Shopping Cart</a>
        </div>
    </div>
    <div class="row" id="shoppingCartContent">
        <div class="container">
            <div class="col s12 m12 l12">
                <h2>Shopping Cart</h2>
            </div>
            <div class="col l8">
                <div class="col s12 m12 l12">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="center-align">Price</th>
                                <th class="center-align">Quantity</th>
                                <th class="right-align">Total</th>
                            </tr>
                        </thead>

                        <tbody v-if="cartItems.length > 0">
                            <tr v-for="cart in cartItems" :key="cart">
                                <td class="productColumn">
                                    <i
                                        class="fas fa-spinner fa-spin"
                                        v-if="
                                            removingCartItem &&
                                            itemBeingRemoved == cart.id
                                        "
                                    ></i>
                                    <i
                                        class="fas fa-times"
                                        v-else
                                        @click="removePrd(cart.id)"
                                    ></i>

                                    <img
                                        class="border-radius-5"
                                        :src="renderImage(cart.product.images)"
                                        alt="cart-product"
                                    />
                                    <div>
                                        <h3>
                                            <a href="#">{{
                                                cart.product.title
                                            }}</a>
                                        </h3>
                                        <p
                                            class="variant"
                                            v-for="(value, key) in JSON.parse(
                                                cart.options
                                            )"
                                            :key="value"
                                        >
                                            {{ key }}: {{ value }}
                                        </p>
                                    </div>
                                </td>
                                <td class="center-align">
                                    {{ formatPrice(cart.product.amount, fx, location) }}
                                </td>
                                <td class="center-align flex flexCenter">
                                    <div class="input-field">
                                        <span
                                            class="productQtyControl cursor"
                                            @click="decreaseQty(cart)"
                                            ><i class="fas fa-minus"></i
                                        ></span>
                                        <input
                                            type="number"
                                            :value="cart.quantity"
                                        />
                                        <span
                                            class="productQtyControl cursor"
                                            @click="increaseQty(cart)"
                                            ><i class="fas fa-plus"></i
                                        ></span>
                                    </div>
                                </td>
                                <td class="right-align addTocartColumn">
                                    <b
                                        >{{
                                            formatPrice(cart.product.amount * cart.quantity, fx, location)
                                        }}</b
                                    >
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr>
                                <td colspan="4" class="center-align grey-text">
                                    <small
                                        ><em
                                            >No product in Shopping Cart</em
                                        ></small
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    class="col s12 m12 l12 gap-1vh flex spaceBetween alignItemsCenter underWishlist"
                >
                    <router-link
                        :to="{
                            name: 'product-search-category',
                            params: { category_name: 'all' },
                            query: { additionalData: null },
                        }"
                        class="ctaLinks"
                        >Continue Shopping</router-link
                    >
                    <ul
                        class="pagination"
                        v-if="cartItems.length > 0"
                        style="display: none"
                    >
                        <li class="disabled">
                            <a href="#!"
                                ><i class="material-icons lineHeightOneThree"
                                    >arrow_back</i
                                ></a
                            >
                        </li>
                        <li class="paginationLi active"><a href="#!">1</a></li>
                        <li class="waves-effect paginationLi">
                            <a href="#!">2</a>
                        </li>
                        <li class="waves-effect paginationLi">
                            <a href="#!">3</a>
                        </li>
                        <li class="waves-effect paginationLi">
                            <a href="#!">4</a>
                        </li>
                        <li class="waves-effect paginationLi">
                            <a href="#!">5</a>
                        </li>
                        <li class="waves-effect">
                            <a href="#!"
                                ><i class="material-icons lineHeightOneThree"
                                    >arrow_forward</i
                                ></a
                            >
                        </li>
                    </ul>
                    <a
                        href="#"
                        class="ctaLinks"
                        @click="clearCart"
                        style="padding-left: 2vh;"
                        v-if="cartItems.length > 0"
                        >Clear Cart</a
                    >
                </div>
            </div>
            <div class="col l4 s12 m4 checkoutCard">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col l12 m12 s12">
                                <h3>Note</h3>
                                <textarea
                                    id="textarea1"
                                    class="browser-default"
                                    v-model="note"
                                    placeholder="Leave a note for us"
                                ></textarea>
                            </div>
                        </div>

                        <div class="row noMarginBottom">
                            <div class="col l12 m12 s12 mgBtm-5">
                                <div class="col s6">
                                    <h3>Total</h3>
                                </div>
                                <div class="col s6 right-align">
                                    <b>{{ formatPrice(cartTotal, fx, location) }}</b>
                                </div>
                            </div>
                            <div
                                class="col s12 center-align"
                                v-if="cartTotal > 0"
                            >
                                <a
                                    class="waves-effect waves-light btn"
                                    @click="checkout"
                                    >Check out</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="row" id="latestProducts">
        <div class="container">
            <div class="col l12">
                <h2>Latest Products</h2>
            </div>
            <div
                class="col l3 m6 s12 mgBtm-5"
                v-for="product in relatedProducts"
                :key="product.id"
            >
                <img
                    :src="product.image"
                    alt="Product Image"
                    loading="lazy"
                    class="responsive-img"
                />
                <p class="grey-text">{{ product.category }}</p>
                <h3>
                    <a href="">{{ product.name }}</a>
                </h3>
                <p class="grey-text price">{{ product.price }}</p>
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
                        <i class="fas fa-shopping-cart left"></i> Add to cart
                    </button>
                    <button class="btn">
                        <i class="fas fa-heart"></i>
                    </button>
                    <button class="btn">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>
    </div> -->
</template>
  
<script>
    import NavbarComponent from "../Navbar.js";
    import { useCartStore } from "@/store/store";
    import priceMixin from "@/mixin/priceMixin";
    // import { loadComponent } from '../../DynamicComponentLoader.js';

    export default {
        components: { NavbarComponent },
        data() {
            return {
                cartArr: [
                    {
                        id: 1,
                        img: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product1.png",
                        productName: "Fresh-whole-fish",
                        variants: [
                            {
                                id: 1,
                                name: "COLOR",
                                value: "Blue",
                            },
                            {
                                id: 2,
                                name: "Size",
                                value: "M",
                            },
                        ],
                        price: 100,
                        qty: 2,
                    },
                    {
                        id: 2,
                        img: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product2.png",
                        productName: "Vegetable Healthy",
                        variants: [
                            {
                                name: "COLOR",
                                value: "Blue",
                            },
                            {
                                name: "Size",
                                value: "M",
                            },
                        ],
                        price: 50,
                        qty: 1,
                    },
                    {
                        id: 3,
                        img: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product3.png",
                        productName: "Onion Patterned",
                        variants: [
                            {
                                name: "COLOR",
                                value: "Blue",
                            },
                            {
                                name: "Size",
                                value: "M",
                            },
                        ],
                        price: 70,
                        qty: 1,
                    },
                    {
                        id: 4,
                        img: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product4.png",
                        productName: "Overized Cotton Dress",
                        variants: [
                            {
                                name: "COLOR",
                                value: "Blue",
                            },
                            {
                                name: "Size",
                                value: "M",
                            },
                        ],
                        price: 89.99,
                        qty: 2,
                    },
                ],
                relatedProducts: [
                    {
                        image: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product2.png",
                        category: "Women",
                        name: "Oversized Cotton Dress",
                        price: "$100",
                    },
                    {
                        image: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product3.png",
                        category: "Women",
                        name: "Boxy Denim Jacket",
                        price: "$100",
                    },
                    {
                        image: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product4.png",
                        category: "Women",
                        name: "Quilted Shoulder Bag",
                        price: "$100",
                    },
                    {
                        image: "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/product/product5.png",
                        category: "Women",
                        name: "Square Shoulder Bag",
                        price: "$100",
                    },
                ],
                removingPrdt: false,
                prdtIdToRemove: 0,
                note: "",
            };
        },
        mounted() {
            // Initialize Materialize components
            // M.AutoInit();
            localStorage.setItem("previousPage", this.$route.fullPath);
        },
        mixins: [priceMixin],
        methods: {
            renderImage(images) {
                if (images) {
                    return images[0].url;
                }
            },
            checkout() {
                // Use this.$router.push to navigate to the /checkout route
                this.$router.push({
                    path: "/checkout",
                    query: { note: this.note },
                });
            },
            clearCart() {
                const cartStore = useCartStore();
                cartStore.clearCart();
            },
            decreaseQty(item) {
                const cartStore = useCartStore();
                let itemId = item.id;
                cartStore.decreaseQuantity(itemId);
            },
            increaseQty(item) {
                const cartStore = useCartStore();
                let itemId = item.id;
                cartStore.increaseQuantity(itemId);
            },
            pushToSelected(item) {
                const selectedState = item.selected;
                const cartStore = useCartStore();
                cartStore.updateSelectedStatus(
                    item.id,
                    item.selected,
                    selectedState
                );
                // cartStore.fetchCart();
            },
            removePrd(itemId) {
                const cartStore = useCartStore();
                cartStore.removeFromCart(itemId);
                cartStore.fetchCart();
            },
        },
        computed: {
            subtotal() {
                return this.cartArr.reduce((accumulator, object) => {
                    return accumulator + object.price * object.qty;
                }, 0);
            },
            cartTotal() {
                const cartStore = useCartStore();
                return cartStore.cartTotal;
            },
            total() {
                const cartStore = useCartStore();
                return cartStore.selectedItemsTotal;
            },
            cartItems() {
                const cartStore = useCartStore();
                return cartStore.cartItems;
            },
            fetchingCartItems() {
                const cartStore = useCartStore();
                return cartStore.fetchingCartItems;
            },
            removingCartItem() {
                const cartStore = useCartStore();
                return cartStore.removingCartItem.status;
            },
            itemBeingRemoved() {
                const cartStore = useCartStore();
                return cartStore.removingCartItem.itemId;
            },
            fx() {
                const cartStore = useCartStore();
                return cartStore.fx;
            },
            location() {
                const cartStore = useCartStore();
                return cartStore.location;
            },
        },
        props: {
            categories: Array,
        },
    };
</script>
<style scoped>
    /* Header */
    .noMarginTop {
        margin-top: 0 !important;
    }
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    h1 {
        font-size: 3.5rem;
    }
    h6 {
        font-weight: 500;
    }
    #shoppingCartBanner {
        background-color: var(--primary-color);
        color: #fff;
        padding: 5.5vh 0;
    }
    .breadcrumb {
        font-size: 1.7rem;
    }
    .breadcrumb::before {
        font-size: 1.9rem;
    }

    /* shoppingCartContent */
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    #shoppingCartContent .container {
        width: 98%;
        max-width: unset;
    }
    #shoppingCartContent h2 {
        font-size: 2rem;
        font-weight: 600;
    }
    table tr th {
        text-transform: uppercase;
    }
    table tr td.productColumn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* width: 70%; */
    }
    table tr td.productColumn i {
        font-size: 1.5rem;
        border-radius: 50%;
        cursor: pointer;
    }
    table tr td.productColumn i:hover {
        color: var(--primary-color);
    }
    table tr td.productColumn img {
        width: 8vw;
        border-radius: 1rem;
    }

    table tr td.productColumn div {
        width: 50%;
    }
    table tr td.productColumn h3,
    table tr td.productColumn p.variant {
        margin: 0 0 1vh 0;
    }
    table tr td.productColumn h3 {
        font-size: 1.3rem;
        font-weight: 600;
    }
    table tr td.productColumn h3 a,
    table tr td.productColumn h3 a:visited {
        color: #313030;
    }
    table tr td.productColumn h3 a:hover,
    .stockStatus,
    a.ctaLinks,
    a.ctaLinks:hover,
    a.ctaLinks:visited {
        color: #000;
    }
    table tr td.productColumn p.variant {
        font-size: 0.9rem;
    }
    .btn {
        background-color: var(--primary-color);
    }
    .productQtyControl {
        background-color: rgb(234, 235, 236);
        color: black;

        width: 4vw;
        height: 5vh;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
    }
    table .input-field {
        margin: 0;
        display: flex;
        align-items: center;
        width: 40%;
    }
    table .input-field input {
        text-align: center;
        margin-bottom: 0;
        border-bottom: unset;
    }
    table input[type="number"]:not(.browser-default):focus:not([readonly]) {
        border-bottom: unset;
        box-shadow: unset;
    }
    table input::-webkit-outer-spin-button,
    table input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    table input[type="number"] {
        -moz-appearance: textfield; /* Firefox */
    }
    .flex {
        display: flex;
    }
    .flexCenter {
        justify-content: center;
    }
    .spaceBetween {
        justify-content: space-between;
    }
    .alignItemsCenter {
        align-items: center;
    }
    .pagination li {
        height: 5.3vh;
        line-height: 5.3vh;
    }
    .pagination li.paginationLi {
        margin: 0 0.5vw;
        border-radius: 50%;
        border: thin solid #e7e7e7;
        height: 5.3vh;
        width: 2.5vw;
        line-height: 5.3vh;
    }
    .pagination li.paginationLi.active {
        background-color: var(--primary-color);
    }
    .lineHeightOneThree {
        line-height: 1.3;
    }
    .underWishlist {
        padding: 3vh 1vw;
    }

    .checkoutCard .input-field input {
        padding: 1.2vh 0.5vw;
        border: thin solid #e7e7e7;
        position: relative;
        top: 0.4vh;
        margin-right: 0.5vw;
    }

    .checkoutCard textarea {
        height: 15vh;
        border: thin solid #e7e7e7;
    }
    .checkoutCard h4 {
        font-size: 1.1rem;
        margin-top: 0;
        font-weight: 500;
    }
    .mgBtm-3 {
        margin-bottom: 3vh;
    }

    .mgBtm-5 {
        margin-bottom: 5vh;
    }

    /* LatestProducts */
    .noMarginTop {
        margin-top: 0;
    }
    #latestProducts .container {
        width: 98%;
        max-width: unset;
    }
    #latestProducts h2 {
        margin-top: 0;
        font-size: 2rem;
        border-bottom: thin solid #e7e7e7;
        padding-bottom: 1.5vh;
    }
    img.responsive-img {
        width: 100%;
    }
    .mgBtm-5 {
        margin-bottom: 5vh;
    }
    .mgBtm-3 {
        margin-bottom: 3vh;
    }
    .buttons .btn {
        font-size: 1rem;
        margin-right: 0.6vw;
        padding: 0 1vw;
        color: #9e9e9e;
        background-color: #fff;
    }
    .buttons .btn:hover {
        background-color: var(--primary-color);
        color: #fff;
    }
    .buttons .btn i {
        font-size: 0.95rem;
    }
    i.left {
        margin-right: 0.5vw;
    }
    h3 {
        margin-top: 0;
        margin-bottom: 1vh;
        font-size: 1.3rem;
        font-weight: 600;
    }
    h3 a {
        color: #000;
    }
    h3 a:hover {
        color: var(--primary-color);
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
</style>
  