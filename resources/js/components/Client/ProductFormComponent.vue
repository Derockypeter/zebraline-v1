<template>
    <div>
        <form class="row" @submit.prevent="saveOrUpdateProduct">
            <p class="center">Upload product information about your product</p>
            <div class="col s5">
                <div class="centerAlign">
                    <div class="imageHolder">
                        <span>Drag or Drop Images here</span>
                    </div>
                </div>

                <div class="center mt-5">
                    <div class="file">
                        <label class="file-label">
                            <input class="file-input" type="file" name="resume" style="display: none;" multiple accept="image/*" @change="handleFileChanges">
                            <span class="file-cta">
                                <a class="file-label btn black btn-large">
                                    Upload product photos
                                </a>
                            </span>
                        </label>
                    </div>
                    <p>Upload up to 8 products</p>
                </div>
                <div class="row">
                    <div class="col s4" v-for="image in showImages" :key="image">
                        <img :src="image" alt="Your product image" class="responsive-img img"/>
                    </div>
                </div>
                <div class="row" v-if="product.images">
                    <div class="col s4" v-for="image in product.images" :key="image">
                        <div class="flex flex-col">
                            <img :src="image.url" alt="Your product image" class="responsive-img img"/>
                            <span v-if="deleting">Deleting <i class="fa fa-spinner fa-spin" ></i></span>
                            <button class="center red darken-4 white-text btn btn-small mt-2" @click="deletePrdImage(image)" v-else>Remove</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col s7">
                <div class="row">
                    <div class="input-field col s12">
                        <input type="text" class="validate" v-model="product.title">
                        <label for="Product Name">Product Name</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea id="textarea1" class="materialize-textarea" v-model="product.description"></textarea>
                        <label for="Product Description">Product Description</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <input type="text" class="validate" v-model="product.price">
                        <label for="Product Price">Product Price</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="text" class="validate" v-model="product.compareAtPrice">
                        <label for="Compare-at-price">Compare-at-price</label>
                    </div>
                    <div class="input-field col s4">
                        <input type="text" class="validate" v-model="product.tags">
                        <label for="Product Tags">Product Tags</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s4">
                        <input type="text" class="validate" v-model="product.stock">
                        <label for="Product Quantity">Product Quantity</label>
                    </div>
                    <div class="input-field col s4">
                        <select class="browser-default" v-model="product.category_id">
                            <option value="" disabled selected>Pick a category</option>
                            <option value="1">Physical</option>
                            <option value="2">Gifts</option>
                            <option value="3">Digital Products</option>
                        </select>
                    </div>
                    <div class="input-field col s4">
                        <p>
                            <label>
                                <input type="checkbox" v-model="product.taxed"/>
                                <span>Charge tax on this product</span>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <div class="flex">
                            <div class="switch">
                                <label>
                                    <input type="checkbox" v-model="product.visible">
                                    <span class="lever"></span>
                                </label>
                            </div>
                            <div>
                                <h5>Not visible on website</h5>
                                <small>This product is hidden. 
                                Press the toggle to change this option.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <div class="flex">
                            <div class="switch">
                                <label>
                                    <input type="checkbox" v-model="lowthresh">
                                    <span class="lever"></span>
                                </label>
                            </div>
                            <div>
                                <h5>Low Stock Alerts</h5>
                                <small>Receive an email alert when product’s stock drops to a certain threshold</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-field" v-if="lowthresh">
                    <div class="row">
                        <div class="col s2">
                            <a class="" href="#!" @click="decrement">-</a>
                        </div>
                        <div class="col s8">
                            <input type="number" v-model="product.lowstockthreshold" class="center">
                        </div>
                        <div class="col s2">
                            <a class="" href="#!" @click="increment">+</a>
                        </div>
                    </div>
                </div>

                <button class="btn right">
                    <i class="fa fa-spinner fa-spin" v-if="processing"></i>
                    <span v-else-if="!processing && product.id == undefined">Finish product setup</span>
                    <span v-else-if="!processing && product.id != undefined">Update</span>
                </button>
            </div>
        </form>
    </div>
</template>
<style scoped>
    .imageHolder {
        border: 2px dotted rgba(128, 128, 128, 0.354);
        height: 25vh;
        width: 20vw;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .centerAlign {
        display: flex;
        justify-content: center;
    }
    .mt-5 {
        margin-top: 5vh;
    }
    .flex {
        display: flex;
        align-items: center;
    }
    h5 {
        margin-bottom: 0;
        font-weight: bold;
        font-size: 1rem;
    }
    .img {
        height: 10vh;
        width: 100%;
    }
    .flex-col {
        flex-direction: column;
    }
    .mt-2 {
        margin-top: 2vh;
    }
</style>
<script>
    import fetchData from "@/mixin/apiMixin";

export default {
    computed: {
        showImages () {
            if (this.selectedImages.length > 0) {
                let co = [];
                for (let i = 0; i < this.selectedImages.length; i++) {
                    co.push(URL.createObjectURL(this.selectedImages[i]));
                }
                return co;
            }
        }
    },
    data () {
        return {
            product: {
                variants: [],
                images: [],
                lowstockthreshold: 0,
                category_id: ""
            },
            processing: false,
            imageName: {},
            typedText: "",
            selectedImages: [],
            photo: null,
            uploaded: null,
            deleting: false,
            deletingPrd: false,
            images: [],
            id: 1,
            lowthresh: false,
        }
    },
    props: {
        productToUpdate: Object,
    },
    mixins: [fetchData],
    methods: {
        increment() {
            this.product.lowstockthreshold = this.product.lowstockthreshold + 1;
        },

        decrement() {
            if (parseInt(this.product.lowstockthreshold) > 0) {
                this.product.lowstockthreshold = parseInt(this.product.lowstockthreshold) - 1; ;
            }
        },

        async deletePrdImage(img_data) {
            this.deleting = true;
            try {
                const response = await this.fetchData(
                    "/unlink_img",
                    3,
                    "post",
                    img_data
                );
                if (response.status === 204) {
                    this.product.images.splice(
                        this.product.images.findIndex(
                            (rol) => rol.id === img_data.id
                        ),
                        1
                    );
                    this.deleting = false;
                }
            } catch (error) {
                this.deleting = !this.deleting;
                M.toast({
                    html: `Error deleting product image in: ${error}`,
                    classes: "errorNotifier",
                });
            }
        },

        handleFileChanges(event) {
            this.selectedImages = event.target.files;
        },

        async saveProduct() {
            this.processing = true;
            try {
                let product = this.product;
                let formData = new FormData();

                for (let i = 0; i < this.selectedImages.length; i++) {
                    formData.append(`images[${i}]`, this.selectedImages[i]);
                }
                formData.append("title", product.title);
                formData.append("brand", product.brand);
                formData.append("price", product.price);
                formData.append("description", product.description);
                formData.append("category_id", product.category_id);
                formData.append("stock", product.stock);
                formData.append("tags", product.tags);

                this.product.compareAtPrice != "" ? formData.append("compareAtPrice", product.compareAtPrice) : null;
                this.product.lowstockthreshold != "" ? formData.append("lowstockthreshold", product.lowstockthreshold) : null;
                formData.append("taxed", product.taxed == true ? 1 : 0);
                formData.append("visible", product.visible == true ? 1 : 0);
                const response = await this.fetchData(
                    "/product",
                    3,
                    "post",
                    formData
                );
                if (response.status === 201) {
                    this.processing = false;
                    this.product = {
                        variants: [],
                        images: [],
                        lowstockthreshold: 0
                    };
                    this.selectedImage = [];
                    M.toast({
                        classes:'green',
                        html: 'Product data saved'
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            } catch (error) {
                this.processing = !this.processing;
                M.toast({
                    html: `Error creating product in: ${error}`,
                    classes: "errorNotifier",
                });
                // if (error.response.status == 422) {
                //     const errors = error.response.data.errors; // Access the errors object in the response
                //     if (errors && errors["images.0"]) {
                //         const errorMessage = errors["images.0"][0]; // Get the first error message
                //         M.toast({
                //             html: errorMessage,
                //             classes: "errorNotifier",
                //         });
                //     }
                // }
                
            }
        },

        saveOrUpdateProduct() {
            if (!this.product.id) {
                this.saveProduct();
            } else {
                this.updateProduct();
            }
        },
        generateRandom() {
            const min = 1;
            const max = 100;
            const randomInt = Math.floor(Math.random() * (max - min + 1)) + min;
            return randomInt;
        },
        async updateProduct() {
            this.processing = true;
            try {
                let product = this.product;
                let formData = new FormData();
                if (this.selectedImages.length > 0) {
                    for (let i = 0; i < this.selectedImages.length; i++) {
                    formData.append(`images[${i}]`, this.selectedImages[i]);
                }
                }
                formData.append("title", product.title);
                formData.append("brand", product.brand);
                formData.append("price", product.price);
                formData.append("description", product.description);
                formData.append("category_id", product.category_id);
                formData.append("_method", "PUT");
                formData.append("stock", product.stock);

                this.product.compareAtPrice != "" ? formData.append("compareAtPrice", product.compareAtPrice) : null;
                this.product.lowstockthreshold != null ? formData.append("lowstockthreshold", product.lowstockthreshold) : null;
                formData.append("taxed", product.taxed == true ? 1 : 0);
                formData.append("visible", product.visible == true ? 1 : 0);

                const response = await this.fetchData(
                    `/product/${this.product.id}`,
                    3,
                    "post",
                    formData
                );
                if (response.status === 200) {
                    this.processing = false;
                    this.product = {
                        description: "",
                        brand: "",
                        category_id: null,
                        price: "",
                        lowstockthreshold: 0,
                    };
                    this.selectedImages = [];
                    M.toast({
                        classes: 'green',
                        html: 'Product updated successfuly!'
                    })
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            } catch (error) {
                this.processing = !this.processing;
                console.error("Error creating product in:", error);
            }
        },
    },
    watch: {
        productToUpdate(newVal) {
            if (newVal !== null) {
                this.product = newVal;
                this.product.visible === 1 ? this.product.visible = true : false;
                this.product.taxed === 1 ? this.product.taxed = true : false;
                this.product.lowstockthreshold !== null ? this.lowthresh = true : false;
            }
        },
    }
}
</script>