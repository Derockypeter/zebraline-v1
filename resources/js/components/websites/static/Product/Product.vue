<template>
    <div>
        <div class="loader" v-show="loading"></div>
        <div v-show="!loading">
            <NavbarComponent :categories="categories" :brandname="brandname" :branddesc="branddesc"/>
            <ProductBannerComponent :product="product" :category="category" :loggedIn="loggedIn" />
            <product-desc-component :product="product" />
            <related-products :relatedProducts="related" v-if="relatedNotEmpty"/>
            <FooterComponent :brandname="brandname" :socials="socials" :categories="categories" />
        </div>
    </div>
</template>
<script>
    import NavbarComponent from '../Navbar.js';
    import FooterComponent from '../Footer';
    import ProductDescComponent from "./ProductDescComponent.vue";
    // import ShippingDetails from './shippingDetails.vue';
    import ProductBannerComponent from "./ProductBannerComponent.vue";
    import RelatedProducts from "./relatedProducts.vue";

    
    import axios from "axios";

    export default {
        components: {
            ProductBannerComponent,
            ProductDescComponent,
            RelatedProducts,
            NavbarComponent,
            FooterComponent,
        },
        computed: {
            relatedNotEmpty () {
                return true ? this.related.length > 0 : false;
            }
        },
        data() {
            return {
                product: {},
                category: {},
                loading: false,
                related: [],
            };
        },
        props: {
            loggedIn: Boolean,

            socials:Object,

            themecolor: String,
            brandname: String,
            categories: Array,
            foldername: String,
            branddesc: String
        },
        methods: {
            async getProduct() {
                const productName = this.$route.params.product_name;
                this.loading = true;
                try {
                    // Make the API request using the product name
                    const response = await axios.get(`/api/product/${productName}`);
                    this.product = response.data.product;
                    this.category = response.data.product.category;
                    this.related = response.data.related;
                    this.loading = false;
                    document.querySelector('title').innerHTML += ` - ${this.product.title}`;
                    document.querySelector(`meta[name='description']`).setAttribute('content', this.product.description);

                    document.querySelector(`meta[property='og:description']`).setAttribute('content', this.product.description);
                    document.querySelector(`meta[property='og:title']`).setAttribute('content', this.product.title);
                    document.querySelector(`meta[property='og:image']`).setAttribute('content', location.host+this.product.images[0].url);

                    document.querySelector(`meta[name='twitter:title']`).setAttribute('content', this.product.title);
                    document.querySelector(`meta[name='twitter:description']`).setAttribute('content', this.product.description);
                    document.querySelector(`meta[name='twitter:image']`).setAttribute('content', location.host+this.product.images[0].url);
                } catch (error) {
                    console.error("Error fetching product:", error);
                }
            },
        },
        created() {
            this.getProduct();
            localStorage.setItem("previousPage", this.$route.fullPath);
        },
    };
</script>