<template>
    <div>
        <NavbarComponent />

        <div class="container">
            <h1>Products Setup</h1>
            <div v-show="!form">
                <!-- Categories -->
                <div class="">
                    <input type="search" />
                </div>
                <div class="">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Visibility</th>
                                <!-- <th>SKU</th> -->
                                <th>Stock</th>
                                <th>Item Price</th>
                            </tr>
                        </thead>
    
                        <tbody>
                            <tr v-for="product in products" :key="product.id">
                                <img :src="renderImage(product)" class="w-5vh" />
                                <td>{{ product.visible == 1 ? 'Public' : 'Private' }}</td>
                                <!-- <td>{{ product.id }}</td> -->
                                <td>{{ product.stock }}</td>
                                <td>${{ product.price }}</td>
                                <td><i class="fa fa-edit pointer" @click="showForm(1, product)"></i></td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-if="products.length == 0" class="center">You have not added any product yet</p>
                </div>
                <p v-if="data">
                    {{ data.from }} - {{ data.to }} of {{ data.total }} products
                </p>
    
                <div class="flex flex-end mt-5">
                    <button class="btn purple darken-2" @click="showForm(1)">Add Product</button>
                </div>
            </div>
            <div v-show="form">
                <ProductFormComponent :productToUpdate="productToUpdate"/>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .flex {
        display: flex;
    }
    .flex-end {
        justify-content: flex-end;
    }
    .w-5vh {
        width: 6vw;
        height: 5vh;
    }
    .mt-5 {
        margin-top: 5vh;
    }
    .pointer {
        cursor: pointer;
    }
</style>
<script>
    import NavbarComponent from './NavbarComponent.vue';
    import fetchData from "@/mixin/apiMixin";
    import priceMixin from "@/mixin/priceMixin";
    import ProductFormComponent from './ProductFormComponent.vue';
    export default {
        components: {NavbarComponent, ProductFormComponent},
        computed: {
            price() {
                if (this.product.id !== undefined) {
                    return this.product.price / 100;
                }
                return this.product.price !== undefined ? this.product.price : "";
            },
        },
        mixins: [fetchData, priceMixin],
        data() {
            return {
                products: [],
                form: 0,
                data: {},
                productToUpdate: {},
            };
        },
        methods: {
            renderImage(prd) {
                if (prd !== undefined && prd.images[0] !== undefined) {
                    return prd.images[0].url;
                }
            },
            showForm(evt, product = null) {
                this.form = evt;
                this.productToUpdate = product;
            },
        
            async getProducts() {
                try {
                    const response = await this.fetchData('/product', 2, 'get');
                    this.products = response.data.products.data;
                    this.data = response.data.products;
                } catch (error) {
                    M.toast({
                        classes: 'red',
                        html: 'Error Fetching products'
                    })
                }
            }
        },
        mounted() {
            this.getProducts();
        }
    }
</script>