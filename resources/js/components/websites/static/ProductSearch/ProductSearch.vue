<template>
    <NavbarComponent :categories="categories" :brandname="brandname" :branddesc="branddesc" />
    <div class="row noMarginBottom" id="productSearchHeader">
        <div class="col s12 center-align">
            <h1 class="noMarginTop capitalize">{{ searchParam }}</h1>
        </div>
        <div class="col s12 center-align">
            <a href="#!" @click="goBack" class="breadcrumb">Home</a>
            <a href="#!" class="breadcrumb capitalize">{{ searchParam }}</a>
        </div>
    </div>

    <div class="row noMarginBottom" id="productFilter">
        <div class="container">
            <div class="col l6 m12 s12">
                <div class="col l4 flex flexSpaceBetween">
                    <h6>Page Count:</h6>
                    <a
                        class="dropdown-trigger btn pageCountDropdown"
                        href="#!"
                        data-target="pageCountDropdown"
                    >
                        {{ pageCount }}
                        <i class="material-icons right">expand_more</i>
                    </a>
                </div>
                <div class="col l4 flex flexSpaceBetween">
                    <h6>Sort By:</h6>
                    <a
                        class="dropdown-trigger btn sortByDropdown"
                        href="#!"
                        data-target="sortByDropdown"
                    >
                        {{ sortBy }}
                        <i class="material-icons right">expand_more</i>
                    </a>
                </div>
            </div>
            <div class="col l6 m12 s12">
                <p class="grey-text right-align">
                    Showing {{ products.from }} to {{ products.to }} of
                    {{ products.total }}
                </p>
            </div>
        </div>
        <!-- Dropdown Structure -->
        <ul id="pageCountDropdown" class="dropdown-content">
            <li v-for="number in [10, 20, 30, 40, 50]" :key="number">
                <a href="#!" @click="changePageCount(number)">{{ number }}</a>
            </li>
        </ul>
        <ul id="sortByDropdown" class="dropdown-content">
            <li><a href="#!" @click="changeSortBy('Latest')">Latest</a></li>
            <li><a href="#!" @click="changeSortBy('Rating')">Rating</a></li>
            <li><a href="#!" @click="changeSortBy('Price')">Price</a></li>
        </ul>
    </div>

    <div class="row" id="productSearchContent">
        <div class="container">
            <div class="col l3 filterParams">
                <!-- Categories and Price Filter Here -->
                <div class="card noMarginTop">
                    <div class="card-content">
                        <div class="row">
                            <h2>Categories</h2>
                            <ul class="collection">
                                <router-link
                                    :to="{
                                        name: `product-search-category`,
                                        params: {
                                            category_name: `all`,
                                        },
                                        query: {
                                            additionalData: null,
                                        },
                                    }"
                                    class="collection-item"
                                    :class="{ active: searchParam == 'all' }"
                                    >All
                                    <span
                                        class="secondary-content"
                                        v-show="searchParam === 'all'"
                                        ><i class="material-icons"
                                            >check</i
                                        ></span
                                    ></router-link
                                >
                                <router-link
                                    :to="{
                                        name: `product-search-category`,
                                        params: {
                                            category_name:
                                                category.name ?? `category`,
                                        },
                                        query: {
                                            additionalData:
                                                category.id ?? `category_id`,
                                        },
                                    }"
                                    class="collection-item"
                                    :class="{
                                        active: searchParam === category.name,
                                    }"
                                    v-for="(category, index) in categories"
                                    :key="index"
                                    >{{ category.name }}
                                    <span
                                        class="secondary-content"
                                        v-show="searchParam === category.name"
                                        ><i class="material-icons"
                                            >check</i
                                        ></span
                                    >
                                </router-link>
                            </ul>
                        </div>

                        <div class="row">
                            <h2>Offers</h2>
                            <ul class="collection">
                                <router-link
                                    :to="{
                                        name: `product-search-category`,
                                        params: {
                                            category_name:
                                                offer.title ?? `offer`,
                                        },
                                        query: {
                                            additionalOfferData:
                                                offer.id ?? `offer_id`,
                                        },
                                    }"
                                    class="collection-item"
                                    :class="{
                                        active: searchParam === offer.title,
                                    }"
                                    v-for="(offer, index) in offers"
                                    :key="index"
                                    >{{ offer.title }}
                                    <span
                                        class="secondary-content"
                                        v-show="searchParam === offer.title"
                                        ><i class="material-icons"
                                            >check</i
                                        ></span
                                    >
                                </router-link>
                            </ul>
                        </div>

                        <div class="row">
                            <h2>Filter By Price</h2>
                            <div class="priceFilter mgBtm-3">
                                <div class="priceFrom">
                                    <h3>From</h3>

                                    <div class="wrapInputAndCurrency">
                                        <i class="fas fa-dollar-sign"></i>
                                        <input
                                            type="number"
                                            class="browser-default"
                                            v-model="min_price"
                                            placeholder="0"
                                        />
                                    </div>
                                </div>
                                <div class="priceDash">
                                    <p>-</p>
                                </div>
                                <div class="priceTo">
                                    <h3>To</h3>

                                    <div class="wrapInputAndCurrency">
                                        <i class="fas fa-dollar-sign"></i>
                                        <input
                                            type="number"
                                            class="browser-default"
                                            placeholder="250.00"
                                            v-model="max_price"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a
                                    class="waves-effect waves-light btn priceFilterBtn"
                                    >Filter</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="loader flex items-center justify-center h-50"
                v-if="loading"
            >
                <i class="fas fa-circle-notch fa-spin fa-4x"></i>
            </div>
            <div v-else>
                <div class="col l9" v-if="displayedProducts.length > 0">
                    <div class="row">
                        <!-- Loop through your products here -->
                        <div
                            class="col l3 m6 s6 mgBtm-5"
                            v-for="product in displayedProducts"
                            :key="product.id"
                        >
                            <img
                                :src="imageDisplayed(product)"
                                alt="Product Image"
                                loading="lazy"
                                class="responsive-img image"
                            />
                            <p class="grey-text">{{ product.category.name }}</p>
                            <h3>
                                <router-link
                                    :to="{
                                        name: 'product-details',
                                        params: { product_name: product.title },
                                    }"
                                >
                                    {{ product.title }}
                                </router-link>
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
                                    <i class="fas fa-shopping-cart left"></i>
                                    Add to cart
                                </button>
                                <button class="btn" @click="wishlist(product)">
                                    <i
                                        class="fas fa-heart"
                                        :class="{
                                            'red-text': checkWishlist(product),
                                        }"
                                    ></i>
                                </button>
                            </div>
                        </div>
                        <!-- End product loop -->
                    </div>
                    <!-- Pagination Here -->
                    <ul class="pagination">
                        <li
                            class="waves-effect"
                            :class="{ disabled: currentPage === 1 }"
                        >
                            <a
                                href="#!"
                                @click.prevent="changePage(currentPage - 1)"
                            >
                                <i class="material-icons lineHeightOneThree"
                                    >arrow_back</i
                                >
                            </a>
                        </li>
                        <li
                            v-for="page in totalPages"
                            :key="page"
                            class="paginationLi"
                            :class="{ active: page === currentPage }"
                        >
                            <a href="#!" @click.prevent="changePage(page)">{{
                                page
                            }}</a>
                        </li>
                        <li
                            class="waves-effect"
                            :class="{ disabled: currentPage === totalPages }"
                        >
                            <a
                                href="#!"
                                @click.prevent="changePage(currentPage + 1)"
                            >
                                <i class="material-icons lineHeightOneThree"
                                    >arrow_forward</i
                                >
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col l9" v-else>
                    <p class="center">No product found</p>
                </div>
            </div>
        </div>
    </div>

    <SellPointComponent :sellPoint="sellPoint" />

    <FooterComponent :brandname="brandname" :socials="socials" :categories="categories" />
</template>

<script>
import FooterComponent from "../Footer";
import NavbarComponent from "../Navbar";
import SellPointComponent from "../SellingPoint";
import fetchData from "@/mixin/apiMixin";
import { useCartStore } from "@/store/store";
import priceMixin from "@/mixin/priceMixin";

export default {
    components: {
        FooterComponent,
        NavbarComponent,
        SellPointComponent,
    },
    computed: {
        // Calculate the end index of the displayed products based on current page and page count
        endIndex() {
            return this.currentPage * this.pageCount;
        },
        // Calculate the start index of the displayed products based on current page and page count
        startIndex() {
            return this.endIndex - this.pageCount + 1;
        },
        // Filter and paginate products based on current page and page count
        filteredProducts() {
            return this.products.data;
        },
        addingCartItem() {
            return useCartStore().addingCartItem;
        },
        addedItemToCart() {
            const cartStore = useCartStore();
            return cartStore.addedItemToCart;
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
        }
    },
    mixins: [fetchData, priceMixin],
    data() {
        return {
            start: 1,
            products: [],
            displayedProducts: [],
            sortBy: "Latest",
            currentPage: "",
            totalPages: "",
            pageCount: 10,
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
            ], // Initialize an empty array for related products
            searchParam: "",
            min_price: 0,
            max_price: 0,
            loading: false,
        };
    },
    beforeRouteUpdate(to, from, next) {
        // Check if the route is changing
        if (to.path !== from.path) {
            // Fetch data based on the new route
            this.searchParam = this.$route.params.category_name;
            if (this.$route.query.hasOwnProperty('additionalOfferData')) {
                this.getOffers(to);
            } else if (
                this.$route.query.hasOwnProperty('additionalData') ||
                this.$route.query.additionalData == null
            ) {
                this.getProducts(to);
            }
        }

        // Continue the route navigation
        next();
    },
    mounted() {
        // Initialize Materialize components
        M.AutoInit();

        const options = {
            alignment: "right",
            constrainWidth: true,
            coverTrigger: false,
        };
        const elems = document.querySelectorAll(".dropdown-trigger");
        M.Dropdown.init(elems, options);
        if (this.$route.query.hasOwnProperty('additionalOfferData')) {
            this.getOffers();
        } else if (
            this.$route.query.hasOwnProperty('additionalData') ||
            this.$route.query.additionalData == null
        ) {
            this.getProducts();
        }
        localStorage.setItem("previousPage", this.$route.fullPath);
        this.fetchData("https://checkip.amazonaws.com/", 3, "get").then(res => res.text()).then(data => console.log(data))

    },
    methods: {
        goBack() {
            this.$router.go(-1); // Go back to the previous page in the history
        },
        addToCart(product) {
            if (product.variants.length > 0) {
                if (product.variants.length > 0) {
                    this.$router.push({
                        name: "product-details",
                        params: { product_name: product.title },
                    });
                } else {
                    this.addingToCart(product);
                }
            } else {
                this.addingToCart(product);
            }
        },
        addingToCart(product) {
            product.quantity = 1;
            // product.options = this.selectedOptions;
            useCartStore().addToCart(product);
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
        classObject(prd) {
            return { "is-active": this.checkWishlist(prd) };
        },
        imageDisplayed(prd) {
            return prd.images[0].url;
        },
        async getProducts(to) {
            const category_id =
                to != undefined
                    ? to.query.additionalData
                    : this.$route.query.additionalData;
            this.searchParam =
                to != undefined
                    ? to.params.category_name
                    : this.$route.params.category_name;
            // console.log(category_id);
            this.loading = true;

            try {
                // Clear existing data before fetching new data
                this.products = []; // or an empty array

                const response = await this.fetchData(
                    `/api/search/${category_id}?sort_by=${this.sortBy}&per_page=${this.pageCount}&page=${this.currentPage}`,
                    3,
                    "get"
                );

                if (response.status === 200) {
                    this.products = response.data.products;
                    this.totalPages = this.products.last_page;
                    this.currentPage = this.products.current_page;
                    this.pageCount = this.products.per_page;
                    this.loading = false;
                    this.filterAndPaginate();
                }
            } catch (err) {
                M.toast({
                    html: `${err}`,
                    classes: "errorNotifier",
                });
            }
        },

        async getOffers(to) {
            const offer_id =
                to != undefined
                    ? to.query.additionalOfferData
                    : this.$route.query.additionalOfferData;
            this.searchParam =
                to != undefined
                    ? to.params.category_name
                    : this.$route.params.category_name;
            this.loading = true;

            try {
                const response = await this.fetchData(
                    `/api/searchOffer/${offer_id}?sort_by=${this.sortBy}&per_page=${this.pageCount}&page=${this.currentPage}`,
                    3,
                    "get"
                );
                if (response.status === 200) {
                    this.products = response.data.products;
                    this.totalPages = this.products.last_page;
                    this.currentPage = this.products.current_page;
                    this.pageCount = this.products.per_page;
                    this.loading = false;
                    this.filterAndPaginate();
                }
            } catch (err) {
                M.toast({
                    html: `${err}`,
                    classes: "errorNotifier",
                });
            }
        },

        async filterByPrice() {
            const category_id = this.$route.query.additionalData;
            this.searchParam = this.$route.params.category_name;
            this.loading = true;
            try {
                // Make the API request using the product name
                const response = await axios.get(
                    `/api/product/filter/${category_id}?sort_by=${this.sortBy}&per_page=${this.pageCount}&page=${this.currentPage}`,
                    {
                        params: {
                            min_price: this.min_price,
                            max_price: this.max_price,
                        },
                    }
                );
                this.products = response.data.products;
                this.totalPages = this.products.last_page;
                this.currentPage = this.products.current_page;
                this.pageCount = this.products.per_page;
                this.loading = false;
                this.filterAndPaginate();
            } catch (error) {
                console.error("Error fetching product:", error);
            }
        },
        // Update the page count and re-filter products
        changePageCount(count) {
            this.pageCount = count;
            this.currentPage = 1; // Reset to the first page
            this.getProducts();
        },
        // Update the sort by option and re-filter products
        changeSortBy(option) {
            this.sortBy = option;
            this.getProducts();
        },
        // Change the current page and re-filter products
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.getProducts();
                // this.filterAndPaginate();
            }
        },
        // Filter and paginate products based on sorting and pagination options
        filterAndPaginate() {
            // Apply sorting logic here based on this.sortBy

            // Calculate the total number of pages based on the filtered products
            // this.totalPages = Math.ceil(this.products.data.length / this.pageCount);
            this.totalPages = this.products.last_page;

            // Update displayed products based on sorting and pagination options
            this.displayedProducts = this.filteredProducts;

            // Update start and end indices for display
            this.start = this.startIndex;
            this.end = Math.min(this.endIndex, this.products.length);

            // Scroll to the top of the product content
            window.scrollTo(
                0,
                document.getElementById("productSearchContent").offsetTop
            );
        },
    },
    props: {
        categories: Array,
        brandname: String,
        socials: Object,
        sellPoint: Array,
        email: String,
        themecolor: String,
        offers: Array,
        branddesc: String,
        blogs: Array,
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

#productSearchHeader {
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

/* Filter */
.noMarginBottom {
    margin-bottom: 0 !important;
}

#productFilter {
    padding: 5vh 0 7vh;
}

.container {
    width: 93%;
    max-width: unset;
}

.flex {
    display: flex;
}

.flexSpaceBetween {
    justify-content: space-between;
}

.dropdown-trigger {
    border: thin solid #e7e7e7;
}

.btn.pageCountDropdown,
.btn.sortByDropdown {
    background-color: #fff;
    color: #242424;
    font-size: 1.1rem;
    padding: 0 0.6vw;
    text-transform: unset;
}

.pageCountDropdown i,
.btn.sortByDropdown i {
    font-size: 1.4rem;
    margin-left: 0.2vw;
    line-height: 2.6rem;
}

/* ProductSearchContent */
.noMarginTop {
    margin-top: 0;
}

.container {
    width: 93%;
    max-width: unset;
}

.filterParams h2 {
    margin-top: 0;
    font-size: 2rem;
    border-bottom: thin solid #e7e7e7;
    padding-bottom: 1.5vh;
}

.collection a.collection-item {
    color: #2c2b2b;
}

.collection .collection-item.active,
.collection .collection-item.active .secondary-content {
    background-color: #fff;
    color: var(--primary-color);
}

.priceFilter {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.priceFilter .priceFrom,
.priceFilter .priceTo {
    width: 40%;
}

.priceFilter .priceDash {
    height: 1vh;
    font-weight: 700;
    font-size: 1.5rem;
}

.priceFilter .priceFrom h3,
.priceFilter .priceTo h3 {
    margin: 0 0 1vh;
    font-size: 1.3rem;
    font-weight: 500;
}

.priceFilter .priceFrom .wrapInputAndCurrency,
.priceFilter .priceTo .wrapInputAndCurrency {
    display: flex;
    border: thin solid #e7e7e7;
    padding: 0.4vh 1vh;
}

.priceFilter .priceFrom .wrapInputAndCurrency input,
.priceFilter .priceTo .wrapInputAndCurrency input {
    width: 100%;
    border: unset;
    outline: unset;
    margin-left: 0.3vw;
    font-size: 1.2rem;
}

.priceFilter .priceFrom .wrapInputAndCurrency i,
.priceFilter .priceTo .wrapInputAndCurrency i {
    line-height: 2.5rem;
    font-size: 1rem;
}

.btn.priceFilterBtn {
    background-color: var(--primary-color);
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0;
    /* <-- Apparently some margin are still there even though it's hidden */
}

input[type="number"] {
    -moz-appearance: textfield;
    /* Firefox */
}

.mgBtm-5 {
    margin-bottom: 5vh;
}

.mgBtm-3 {
    margin-bottom: 3vh;
}

.buttons .btn {
    font-size: 0.7rem;
    margin-right: 0.6vw;
    padding: 0 0.7vw;
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

.pagination li {
    height: 5.3vh;
    line-height: 5.3vh;
}

.pagination li.paginationLi {
    margin: 0 0.5vw;
    border-radius: 50%;
    border: thin solid #e7e7e7;
    height: 3vw;
    width: 3vw;
    line-height: 3vw;
}

.pagination li.paginationLi.active {
    background-color: var(--primary-color);
}

.lineHeightOneThree {
    line-height: 1.3;
}

/* transactionDetails */

#transactionDetails {
    padding: 5vh 0;
}

#transactionDetails .container {
    width: 80%;
    max-width: unset;
}

.noMarginTop {
    margin-top: 0 !important;
}

.noMarginBottom {
    margin-bottom: 0 !important;
}

#transactionDetails .card-content .col {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

#transactionDetails .card-content .col .icon {
    width: 37%;
    display: flex;
    justify-content: flex-end;
}

#transactionDetails .card-content .col .icon i {
    font-size: 3rem;
    color: rgb(98, 98, 99);
}

#transactionDetails .card-content .col .detail {
    width: 58%;
}

#transactionDetails .card-content .col .detail h4 {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 0.5vh;
}

#transactionDetails .card-content .col .detail span {
    font-size: 0.8rem;
}

p.center {
    font-size: 1.2rem;
    color: grey;
}

/* Footer */
#footerInner .container {
    width: 88vw;
    max-width: unset;
}

.row .col.footer {
    border-top: thin solid rgb(213, 211, 211);
    border-bottom: thin solid rgb(213, 211, 211);
    padding: 10vh 0;
}

.noMarginTop {
    margin-top: 0 !important;
}

.noMarginBottom {
    margin-bottom: 0 !important;
}

.footerInner {
    width: 78vw;
    margin: 0 auto;
}

.footerInner .l5 h3 {
    margin-top: 0;
}

.footerInner .l5 h4 {
    font-size: 1.9rem;
}

.footerInner .l4 h2 {
    margin-top: 0;
    font-size: 1.7rem;
}

.footerInner .l4 ul li a,
.footerIconContainer a,
.footerInner .l4 ul li a:visited {
    color: rgba(0, 0, 0, 0.51) !important;
}

.footerInner .l4 ul li a:hover,
.footerInner .l4 ul li a:active,
.footerInner .l4 ul li a:visited,
.footerIconContainer a:hover,
.footerIconContainer a:active,
.footerIconContainer a:visited {
    color: var(--primary-color);
}

.footerInner .l4 .input-field:first-child {
    margin-bottom: 0.5rem;
}

.footerIconContainer {
    display: flex;
    justify-content: space-between;
}

@media only screen and (max-width: 767px) {
    .pagination li.paginationLi {
        height: 5vh;
        width: 5vh;
        line-height: 5vh;
    }
}
</style>
