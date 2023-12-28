<template>
    <div>
        <div class="" v-show="fetching">
            <div class="flex justify-center items-center h-100 flex-col">
                <div class="loader-container">
                    <div class="loader"></div>

                </div>
                <p class="loading-text" v-if="loggedIn">Fetching store data</p>
            </div>
        </div>
        <div v-show="!fetching">
            <!-- <div class="row" v-if="canEdit"> -->
                <!-- <div class="col s2 l3 m4 edits"> -->
                    <!-- <SideNav
                        :onEditNavMenu="editNavMenu"
                        :onEditHeroSection="editHeroSection"
                        :onEditProductSection="editProductSection"
                        :onEditSellingPointSection="editSellingPointSection"
                        :onEditOffersSection="editOffersSection"
                        :onEditBlogSection="editBlogSection"
                        :onEditSocialSection="editSocialEditor"
                        :categories="categories"
                        :hero="hero"
                        :offers="offers"
                        :products="products"
                        :sellPoint="sellPoint"
                        :blogs="blog"
                        :brandname="brandname"
                        :socials="socials"
                        @heroUpdate="heroUpdate"
                        @newOfferData="newOfferData"
                        @closeEditionNavMenu="showEditNavMenu($event)"
                        @closeHeroEditor="showHeroEditor($event)"
                        @closeOffersEditor="showOffersEditor"
                        @closeSellingPointEditor="
                            showSellingPointEditor($event)
                        "
                        @categoryUpdate="categoryUpdate"
                        @closeSocialEditor="showSocialEditor"
                        @closeBlogSection="showEditBlogMenu"
                        @closeProductEditor="showProductEditor"
                        @newProduct="newProduct"
                        @sellingPointUpdate="sellingPointUpdate"
                        @selectedImages="selectedImages"
                        @emitVariant="onEmitVariant"
                        :updatedCombinations="combinations"
                        :branddesc="branddesc"
                        :width="width"
                        :height="height"
                        :isUserSubscribed="localisSub"
                        :meta="meta"
                    /> -->
                <!-- </div> -->
                <div class="col s12 l12 m12 main" @click="checkIfLoggedIn">
                    <!-- <PublishHeadComponent :brandname="brandname" :user="user" @isSubscribed="isSubscribed"/> -->
                    <HomeComponent
                        :brandname="brandname"
                        :themecolor="themecolor"
                        :categories="categories"
                        :offers="offers"
                        :hero="hero"
                        :timestamp="timestamp"
                        :catetimestamp="catetimestamp"
                        :loggedIn="loggedIn"
                        :sellPoint="sellPoint"
                        :socials="socials"
                        @showEditNavMenu="showEditNavMenu($event)"
                        @showHeroEditor="showHeroEditor($event)"
                        @showOffersEditor="showOffersEditor($event)"
                        @showSocialEditor="showSocialEditor"
                        :products="products"
                        @showProductEditor="showProductEditor"
                        @showSellingPointEditor="showSellingPointEditor($event)"
                        @showEditBlogMenu="showEditBlogMenu"
                        :branddesc="branddesc"
                        :blogs="blog"
                        :reviews="reviews"
                        :isEditingHero="editHeroSection"
                        :isEditingCategory="editNavMenu"
                        :isEditingProduct="editProductSection"
                        :isEditingOffers="editOffersSection"
                        :isEditingFooter="editSocialEditor"
                        :isEditingSellingPoint="editSellingPointSection"
                        :navbarTemplateId="navbarTemplateId"
                        :heroTemplateId="heroTemplateId"
                        :categoryTemplateId="categoryTemplateId"
                        :blogTemplateId="blogTemplateId"
                        :featuredPrdTemplateId="featuredPrdTemplateId"
                        :offerTemplateId="offerTemplateId"
                        :reviewTemplateId="reviewTemplateId"
                        :sellingPointTemplateId="sellingPointTemplateId"
                        :footerTemplateId="footerTemplateId"
                        :storeTypeId="storeTypeId"
                    />
                </div>
            <!-- </div> -->
            <!-- <div v-show="!canEdit"> -->
                <!-- <RouterView
                    :brandname="brandname"
                    :hero="hero"
                    :themecolor="themecolor"
                    :categories="categories"
                    :products="products"
                    :email="email"
                    :offers="offers"
                    :sellPoint="sellPoint"
                    :socials="socials"
                    :branddesc="branddesc"
                    :blogs="blog"
                    :reviews="reviews"
                    :navbarTemplateId="navbarTemplateId"
                    :heroTemplateId="heroTemplateId"
                    :categoryTemplateId="categoryTemplateId"
                    :blogTemplateId="blogTemplateId"
                    :featuredPrdTemplateId="featuredPrdTemplateId"
                    :offerTemplateId="offerTemplateId"
                    :reviewTemplateId="reviewTemplateId"
                    :sellingPointTemplateId="sellingPointTemplateId"
                    :footerTemplateId="footerTemplateId"
                    :storeTypeId="storeTypeId"
                /> -->
            <!-- </div> -->
        </div>
        <!-- <div id="signinmodal" class="modal">
            <SignupComponent />
        </div> -->
        <!-- <div>
            <ProductVariantsEditModalComponent
                :selectedImages="productImages"
                :productVariants="productVariants"
                @combinations="updateCombinations"
            />
        </div> -->
    </div>
</template>
<script>
    // import PublishHeadComponent from "./editor/PublishHeadComponent.vue";
    // import SideNav from "./editor/SideNav.vue";
    import HomeComponent from "./Home.vue";
    // import SignupComponent from "../partials/SignupComponent.vue";
    // import ProductVariantsEditModalComponent from "../partials/ProductVariantsEditModalComponent.vue";

    import { useCartStore } from "../../store/store";
    import apiMixin from "@/mixin/apiMixin";
    import { toInteger, toNumber } from 'lodash';

    export default {
        data() {
            return {
                canEdit: 0,
                categories: [],
                hero: [],
                products: [],
                loggedIn: false,
                fetching: false,
                blog: [],
                sellPoint: [],
                offers: [],
                socials: {},
                reviews: [],

                // Edits
                editOffersSection: false,
                editNavMenu: false,
                editHeroSection: false,
                editProductSection: false,
                editSellingPointSection: false,
                editBlogSection: false,
                editSocialEditor: false,
                timestamp: Date.now(),
                catetimestamp: Date.now(),
                sellingPSeeder: [
                    {
                        title: "Free Shipping",
                        image_or_icon:
                            "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping1.png",
                        description: "From handpicked sellers",
                    },
                    {
                        title: "Support",
                        image_or_icon:
                            "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping2.png",
                        description: "From handpicked sellers",
                    },
                    {
                        title: "60 days Return",
                        image_or_icon:
                            "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping3.png",
                        description: "From handpicked sellers",
                    },
                ],
                user: null,
                height: null,
                width: null,
                productImages: [],
                productVariants: null,
                combinations: null,
                localisSub: false,
                meta: this.metadata !== "" ? JSON.parse(this.metadata) : this.metadata,
                webpageGettingReady: false,

                storeTypeId: toNumber(this.storetypeid),
                navbarTemplateId: toNumber(this.navbartempid),
                heroTemplateId: toNumber(this.herotempid),
                categoryTemplateId: toNumber(this.categorytempid),
                featuredPrdTemplateId: toNumber(this.featuredprdtempid),
                blogTemplateId: toNumber(this.blogtempid),
                offerTemplateId: toNumber(this.offertempid),
                reviewTemplateId: toNumber(this.reviewtempid),
                sellingPointTemplateId: toNumber(this.sellingpointtempid),
                footerTemplateId: toNumber(this.footertempid),
            };
        },
        emits: {
            onEmitVariant: null,
        },
        mixins: [apiMixin],
        components: {
            HomeComponent,
            // PublishHeadComponent,
            // SideNav,
            // SignupComponent,
            // ProductVariantsEditModalComponent,
        },
        props: {
            themecolor: String,
            brandname: String,
            email: String,
            foldername: String,
            branddesc: String,
            metadata: String,

            storetypeid: String,
            navbartempid: String,
            herotempid: String,
            categorytempid: String,
            featuredprdtempid: String,
            blogtempid: String,
            offertempid: String,
            reviewtempid: String,
            sellingpointtempid: String,
            footertempid: String,
        },
        mounted() {
            this.checkURLHasEditFlag();
            this.fetchUser();
            this.fetchDatas();
            let loggedIn = localStorage.getItem("loggedIn");
            let editFlag = localStorage.getItem("editFlag");
            if (loggedIn != null && editFlag !== null) {
                this.loggedIn = true;
            }

            const cartStore = useCartStore();
            cartStore.getIPFromAmazon();
            this.failSafe();
        },
        methods: {
            isSubscribed (evt) {
                this.localisSub = evt;
            },
            // Edits
            showEditNavMenu(evt) {
                this.editNavMenu = evt.evt;
                this.width = evt.width;
                this.height = evt.height;

                this.editOffersSection = false;
                this.editHeroSection = false;
                this.editProductSection = false;
                this.editSellingPointSection = false;
                this.editBlogSection = false;
                this.editSocialEditor = false;
            },
            selectedImages(evt) {
                this.productImages = evt;
            },
            onEmitVariant(evt) {
                this.productVariants = evt.combinations;
                this.selectedImages(evt.images)
            },
            // onEmitVariant(evt) {
            //     this.productVariants = evt;
            // },
            updateCombinations(evt) {
                this.combinations = evt;
            },
            showHeroEditor(evt) {
                this.editHeroSection = evt.evt;
                this.width = evt.width;
                this.height = evt.height;

                this.editOffersSection = false
                this.editNavMenu = false;
                this.editProductSection = false;
                this.editSellingPointSection = false;
                this.editBlogSection = false;
                this.editSocialEditor = false;
            },
            showOffersEditor(evt) {
                this.editOffersSection = evt.evt;
                this.width = evt.width;
                this.height = evt.height;

                this.editNavMenu = false;
                this.editHeroSection = false;
                this.editProductSection = false;
                this.editSellingPointSection = false;
                this.editBlogSection = false;
                this.editSocialEditor = false;
            },
            showSellingPointEditor(evt) {
                this.editSellingPointSection = evt;

                this.editOffersSection = false
                this.editNavMenu = false;
                this.editHeroSection = false;
                this.editProductSection = false;
                this.editBlogSection = false;
                this.editSocialEditor = false;
            },
            showSocialEditor(evt) {
                this.editSocialEditor = evt;

                this.editOffersSection = false
                this.editNavMenu = false;
                this.editHeroSection = false;
                this.editProductSection = false;
                this.editSellingPointSection = false;
                this.editBlogSection = false;
            },
            showEditBlogMenu(evt) {
                this.editBlogSection = evt.evt;
            },
            heroUpdate(evt) {
                this.hero = evt;
                this.timestamp = Date.now();
            },
            categoryUpdate(evt) {
                this.categories = evt;
                this.catetimestamp = Date.now();
            },
            newOfferData(evt) {
                this.offers = evt;
                this.timestamp = Date.now();
            },
            sellingPointUpdate(evt) {
                this.sellPoint = evt;
            },
            showProductEditor(evt) {
                this.editProductSection = evt;

                this.editOffersSection = false
                this.editNavMenu = false;
                this.editHeroSection = false;
                this.editSellingPointSection = false;
                this.editBlogSection = false;
                this.editSocialEditor = false;
            },
            checkIfLoggedIn() {
                if (!this.loggedIn) {
                    var elems = document.querySelector("#signinmodal");
                    var instance = M.Modal.init(elems);
                    instance.open();
                }
            },
            newProduct(evt) {
                this.products.push(evt);
            },

            getQueryParameterByName(name = "categories") {
                const queryString = window.location.search;
                name = name.replace(/[[]]/g, "\\$&");
                const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)");
                const results = regex.exec(queryString);
                if (!results) return null;
                if (!results[2]) return "";
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            },

            checkURLHasEditFlag() {
                if (window.location.search.includes("?edit=1")) {
                    this.saveCategory();
                    localStorage.setItem("editFlag", 1);
                    this.canEdit = localStorage.getItem("editFlag");
                } else if (window.location.pathname === "/edit") {
                    // You are on the /edit page, so you can use localStorage here
                    this.canEdit = localStorage.getItem("editFlag");
                } else {
                    // You are not on the /edit page, so do not use localStorage here
                    console.log("LocalStorage is not available on this page.");
                }
            },
            async saveCategory() {
                try {
                    let categories = this.getQueryParameterByName();
                    const res = await this.fetchData("/api/category", 3, "post", {
                        categories: categories,
                    });
                    if (res.data[0] === "ok") {
                        const newURL = window.location.href.replace(
                            `?edit=1&categories=${categories}`,
                            ""
                        );
                        window.location.href = newURL;
                    }
                } catch (error) {
                    M.toast({
                        html: `${error}`,
                        classes: "errorNotifier",
                    });
                }
            },
            async fetchDatas() {
                this.fetching = true;
                this.fetchData("/api/category", 3, "get")
                    .then((firstResponse) => {
                        this.categories = firstResponse.data.categories;
                        // Make a second request
                        return this.fetchData("/api/hero_section", 3, "get");
                    })
                    .then((secondResponse) => {
                        this.hero = secondResponse.data;
                        return this.fetchData("/api/product", 3, "get");
                    })
                    .then((thirdResponse) => {
                        this.products = thirdResponse.data;
                        return this.fetchData("/api/blog", 3, "get");
                    })
                    .then((fourthResponse) => {
                        this.blog = fourthResponse.data.blogposts.data;
                        return this.fetchData("/api/selling_point", 3, "get");
                    })
                    .then((fifthResponse) => {
                        this.sellPoint = fifthResponse.data.services;
                        if (this.sellPoint.length == 0) {
                            console.log(this.sellingPSeeder);
                            this.sellPoint = this.sellingPSeeder;
                        }
                        return this.fetchData("/api/coupons", 3, "get");
                    })
                    .then((seventhResponse) => {
                        this.offers = seventhResponse.data.coupons;
                        return this.fetchData("/api/social", 3, "get");
                    })
                    .then((sixthResponse) => {
                        this.socials = sixthResponse.data.social;
                        return this.fetchData("/api/reviews", 3, "get");
                    })
                    .then((eightResponse) => {
                        this.reviews = eightResponse.data.reviews.data;
                        this.fetching = !this.fetching;
                    })
                    .catch(() => {
                        // Handle errors for any of the requests
                        M.toast({
                            html: "Failed to fetch Data",
                            classes: "red",
                        });
                        this.fetching = !this.fetching;
                    });
            },
            async fetchUser() {
                const cartStore = useCartStore();
                await this.fetchData("/user", 3, "get")
                    .then((res) => {
                        if (res.data === "") {
                            cartStore.updateUser(false, null);
                            localStorage.removeItem("loggedIn");
                        } else {
                            if (res.role === "Admin") {
                                localStorage.setItem("loggedIn", true);
                            }
                            this.user = res.data.id;
                            cartStore.updateUser(true, res.data);
                            this.getCartNWishes();
                        }
                    })
                    .catch(() => {
                        // Handle errors for any of the requests
                        M.toast({
                            html: "Network Error, refresh page",
                            classes: "red",
                        });
                        this.fetching = !this.fetching;
                    });
            },
            getCartNWishes() {
                const cartStore = useCartStore();
                cartStore.fetchCart();
                cartStore.fetchUserWishlists();
            },

            async failSafe () {
                // Checks if one of the classes or id is available
                const crawler = document.getElementById('heroSection');
                if (crawler == null) {
                    // Show a loader, getting your webpage ready
                    this.webpageGettingReady = true;
                    // Settimeout to another 5o secs
                    // Reload and recrawl
                }
            },
        },
    };
</script>
<style scoped>
    #signinmodal {
        width: 87vh;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        background: #fff;
        box-shadow: 4px 4px 4px 2px rgba(197, 190, 190, 0.47);
        max-height: 80%;
    }
    .edits {
        position: fixed;
        overflow-y: scroll;
        height: 100vh;
    }
    .main {
        /* margin-left: 25% !important; */
    }
    .loader-container {
        position: relative;
        width: 32rem;
    }
    .loading-text {
        margin-top: 1rem; /* Add space between the loader and text */
        font-size: 1.5rem; /* Set your desired font size */
        color: #333; /* Set your desired text color */
        font-weight: bold; /* Add bold font weight if desired */
    }

    .edits::-webkit-scrollbar {
        width: 1px;
    } /* this targets the default scrollbar (compulsory) */
    .edits::-webkit-scrollbar-track {
        /* box-shadow: inset 0 0 7px rgba(0,0,0,0.8);*/
        background-color: rgba(175, 173, 173, 0.589);
        /*border-radius: 8px;*/
    } /* the new scrollbar will have a flat appearance with the set background color */
    .edits::-webkit-scrollbar-thumb {
        box-shadow: inset 0 0 100px rgba(175, 173, 173, 0.589);
        background-color: rgba(175, 173, 173, 0.589);
        /*border-radius: 8px;*/
    } /* this will style the thumb, ignoring the track */

    .edits::-webkit-scrollbar-button {
        height: 0;
        /* background-color: rgba(0,151,0,1);*/
    } /* optionally, you can style the top and the bottom buttons (left and right for horizontal bars) */

    .edits::-webkit-scrollbar-corner {
        background-color: rgba(175, 173, 173, 0.589);
    } /* if both the vertical and the horizontal bars appear, then perhaps the right bottom corner also needs to be styled */
    .edits::-webkit-scrollbar-track-piece {
        background-color: rgba(175, 173, 173, 0.589);
    }
    .edits::-webkit-scrollbar-button:horizontal {
        width: 1px;
        /* Select the down or left scroll button when it's being hovered by the mouse */
    }
    /* Loader from UI?UX */
    .loader {
        width: 60vh;
        height: 6px;
        border-radius: 20px;
        background: linear-gradient(90deg, var(--primary-color) 0%, #d96cff 100%);
        min-width: 0;
        background-color: white; /* Loader bar color during processing */
        animation: loaderAnimation 2s infinite; /* Adjust the animation duration as needed */
    }

    @keyframes loaderAnimation {
        0% {
            width: 0;
        }
        100% {
            width: 100%;
        }
    }
    @media only screen and (min-width: 768px) and (max-width: 1023px) {
        .main {
            margin-left: 33.33333% !important;
        }
    }
</style>