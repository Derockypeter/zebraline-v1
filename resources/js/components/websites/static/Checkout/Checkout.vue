<template class="relative">
    <div class="container">
        <div class="row">
            <div class="col l7 m12 s12 borderRight pdTop-7">
                <div class="row">
                    <div class="col s12">
                        <img
                            src="/media/imgs/logoipsum-295.svg"
                            loading="lazy"
                        />
                        <div class="col s12">
                            <a href="#!" class="breadcrumb completed">Cart</a>
                            <a
                                href="#!"
                                class="breadcrumb"
                                :class="computeShippingBreadcrumb"
                                >Information</a
                            >
                            <a
                                href="#!"
                                class="breadcrumb"
                                :class="computeShippingBreadcrumb"
                                >Shipping</a
                            >
                            <a
                                href="#!"
                                class="breadcrumb"
                                :class="computePaymentBreadcrumb"
                                >Payment</a
                            >
                        </div>
                    </div>
                </div>
                <div v-if="view === 0">
                    <div class="row">
                        <div class="col l12">
                            <h2>Contact Informaton</h2>
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <input
                                type="email"
                                placeholder="Email"
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.email == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.email"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2>Shipping Address</h2>
                                    <span class="fw-600">{{}}</span>
                                </div>
                                <!-- <button class="change">Change</button> -->
                            </div>
                        </div>

                        <div class="input-field col l12 m12 s12">
                            <input
                                type="text"
                                placeholder="Names"
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.names == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.names"
                            />
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <input
                                type="text"
                                placeholder="Phone"
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.phone == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.phone"
                            />
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <input
                                type="text"
                                placeholder="Address"
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.address == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.address"
                            />
                        </div>
                        <div class="input-field col l6 m12 s12">
                            <select
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.countryName == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.countryName"
                                @change="getCities"
                            >
                                <option value="" disabled selected>
                                    {{ countryLoadingState }}
                                </option>
                                <option
                                    v-for="country in countries"
                                    :value="country.country"
                                    :key="country.id"
                                >
                                    {{ country.country }}
                                </option>
                            </select>
                        </div>
                        <div class="input-field col l6 m12 s12">
                            <select
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.cityName == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.cityName"
                            >
                                <option value="" disabled selected>
                                    Select country to load cities
                                </option>
                                <option
                                    v-for="city in cities"
                                    :value="city"
                                    :key="city.id"
                                >
                                    {{ city }}
                                </option>
                            </select>
                        </div>

                        <div class="input-field col l12 m12 s12">
                            <input
                                type="email"
                                readonly
                                placeholder="Email"
                                class="browser-default"
                                v-model="user.shipping.email"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col l12 m12 s12" v-if="!loading">
                            <a
                                class="waves-effect waves-light btn"
                                @click="moveToShipping()"
                                >Continue To Shipping</a
                            >
                            <a
                                class="waves-effect waves-light btn-flat"
                                href="/cart"
                                >Return to Cart</a
                            >
                        </div>
                        <div class="col l12 m12 s12" v-else>
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect x="1" y="6" width="2.8" height="12">
                                    <animate
                                        id="spinner_CcmT"
                                        begin="0;spinner_IzZB.end-0.1s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="0;spinner_IzZB.end-0.1s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="5.8" y="6" width="2.8" height="12">
                                    <animate
                                        begin="spinner_CcmT.begin+0.1s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.1s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="10.6" y="6" width="2.8" height="12">
                                    <animate
                                        begin="spinner_CcmT.begin+0.2s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.2s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="15.4" y="6" width="2.8" height="12">
                                    <animate
                                        begin="spinner_CcmT.begin+0.3s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.3s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="20.2" y="6" width="2.8" height="12">
                                    <animate
                                        id="spinner_IzZB"
                                        begin="spinner_CcmT.begin+0.4s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.4s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                            </svg>
                        </div>
                    </div>
                </div>
                <div v-if="view === 1">
                    <div class="row">
                        <div class="col l12">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="grey-text">Contact</td>
                                        <td>{{ user.shipping.email }}</td>
                                        <td class="right-align">
                                            <a href="#" class="themeText"
                                                >Change</a
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="grey-text">Ship to</td>
                                        <td>
                                            {{ user.add }} {{ user.city }}
                                            {{ user.shipping.countryName }}
                                        </td>
                                        <td class="right-align">
                                            <a href="#" class="themeText"
                                                >Change</a
                                            >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="grey-text">Methods</td>
                                        <td>
                                            {{ shippingMethods[0]["method"] }}:
                                            <b
                                                >${{
                                                    shippingMethods[0].cost
                                                }}</b
                                            >
                                        </td>
                                        <td class="right-align">
                                            <a href="#" class="themeText"
                                                >Change</a
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col l12 m12 s12" v-if="!processingCheckout">
                            <a
                                class="waves-effect waves-light btn"
                                v-if="provider === 'flwave'"
                                @click.prevent="makePayment()"
                                >Pay</a
                            >
                            <a
                                class="waves-effect waves-light btn modal-trigger"
                                href="#paymentCheckout"
                                v-if="provider === 'stripe'"
                                >Pay</a
                            >
                            <paystack
                                v-if="provider === 'paystack'"
                                buttonClass="'button-class btn btn-primary'"
                                buttonText="Pay"
                                :publicKey="publicKey"
                                :email="email"
                                :amount="subtotal"
                                :reference="reference"
                                :onSuccess="onSuccessfulPayment"
                                :onCanel="onCancelledPayment"
                            >
                            </paystack>
                            <a
                                class="waves-effect waves-light btn-flat"
                                @click="returnToShipping()"
                                >Return to Shipping</a
                            >
                        </div>
                        <div v-else>
                            <svg
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect x="1" y="6" width="2.8" height="12">
                                    <animate
                                        id="spinner_CcmT"
                                        begin="0;spinner_IzZB.end-0.1s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="0;spinner_IzZB.end-0.1s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="5.8" y="6" width="2.8" height="12">
                                    <animate
                                        begin="spinner_CcmT.begin+0.1s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.1s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="10.6" y="6" width="2.8" height="12">
                                    <animate
                                        begin="spinner_CcmT.begin+0.2s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.2s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="15.4" y="6" width="2.8" height="12">
                                    <animate
                                        begin="spinner_CcmT.begin+0.3s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.3s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                                <rect x="20.2" y="6" width="2.8" height="12">
                                    <animate
                                        id="spinner_IzZB"
                                        begin="spinner_CcmT.begin+0.4s"
                                        attributeName="y"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="6;1;6"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                    <animate
                                        begin="spinner_CcmT.begin+0.4s"
                                        attributeName="height"
                                        calcMode="spline"
                                        dur="0.6s"
                                        values="12;22;12"
                                        keySplines=".36,.61,.3,.98;.36,.61,.3,.98"
                                    />
                                </rect>
                            </svg>
                        </div>
                    </div>
                </div>
                <div v-if="view === 2" id="successfullPaymentView">
                    <div class="row welcome">
                        <div class="col l2">
                            <i class="material-icons">check</i>
                        </div>
                        <div class="col l10">
                            <div class="col l12">
                                <p class="noMarginTop noMarginBottom">
                                    Order #{{ orderID }}
                                </p>
                            </div>
                            <div class="col l12">
                                <h2 class="noMarginTop">
                                    Thank you for your patronage.
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col l12">
                            <h3>Your Order is confirmed</h3>
                            <p class="grey-text">
                                You,ll receive a confirmation email with your
                                order number shortly
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col l12">
                            <h3>Customer Information</h3>
                        </div>
                        <div class="col l6">
                            <h4>Email</h4>
                            <p>{{ user.shipping.email }}</p>
                        </div>
                        <div class="col l6">
                            <h4>Payment Details</h4>
                            <p>Card ends with {{ cardEnd }}</p>
                        </div>
                        <div class="col l6">
                            <h4>Shipping Details</h4>
                            <p>
                                {{ user.shipping.address }},
                                {{ user.shipping.cityName }},
                                {{ user.shipping.countryName }}
                            </p>
                        </div>
                        <div class="col l6">
                            <h4>Shipping Method</h4>
                            <p>
                                {{ shippingMethods[0].method }}: ${{
                                    shippingMethods[0].cost
                                }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col l12 m12 s12">
                            <router-link
                                :to="{
                                    name: 'product-search-category',
                                    params: { category_name: 'all' },
                                    query: { additionalData: null },
                                }"
                                class="waves-effect waves-light btn"
                                @click="continueShopping()"
                                >Continue Shopping</router-link
                            >
                            <a
                                href="/your_account/orders"
                                class="waves-effect waves-light btn-flat"
                                @click="gotoOrders()"
                                >View Orders</a
                            >
                        </div>
                    </div>
                </div>
                <div id="payment-failed" style="display: none">
                    Payment Failed
                </div>
                <div class="row pdTop-3">
                    <div class="col l12 center-align">
                        <small> Copyright &copy; 2023 {{ brandname }} </small>
                    </div>
                </div>
            </div>
            <div class="col l5 m12 s12 paddingLeft pdTop-7">
                <table class="responsive-table">
                    <tbody v-if="cartItems.length > 0">
                        <tr v-for="prdt in cartItems" :key="prdt.id">
                            <td class="productColumn">
                                <img
                                    class="border-radius-5"
                                    :src="prdt.product.images[0].url"
                                    alt="cart-product"
                                />
                                <span>{{ prdt.qty }}</span>
                                <div>
                                    <h3>
                                        <a href="#">{{ prdt.product.title }}</a>
                                    </h3>
                                </div>
                            </td>

                            <td>
                                <ul>
                                    <li
                                        v-for="(option, key) in JSON.parse(
                                            prdt.options
                                        )"
                                        :key="option"
                                    >
                                        {{ key }} - {{ option }}
                                    </li>
                                </ul>
                            </td>

                            <td class="right-align addTocartColumn">
                                <b
                                    >{{
                                        formatPrice(prdt.product.amount * prdt.quantity, fx, location)
                                    }}</b
                                >
                            </td>
                        </tr>
                        <tr>
                            <td>SubTotal</td>
                            <td class="right-align">
                                <b>{{ formatPrice(subtotal,  fx, location) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>Coupon</td>
                            <td>
                                <div class="input-field flexInput">
                                    <input
                                        type="text"
                                        placeholder="Coupon Code"
                                        class="browser-default"
                                        v-model="couponCode"
                                    />
                                    <!-- <a class="waves-effect waves-light btn">Apply Coupon</a> -->
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Shipping</td>
                            <td class="right-align">
                                <small>Calculated at the next step</small>
                            </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td class="right-align total">
                                <b>{{ formatPrice(subtotal,  fx, location) }}</b>
                            </td>
                        </tr>
                    </tbody>

                    <tbody v-else>
                        <tr>
                            <td colspan="4" class="center-align grey-text">
                                <small
                                    ><em>No product in Shopping Cart</em></small
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal" id="">
                <div class="modal-content">
                    <div class="row">
                        <div class="col l12">
                            <h2>Shipping Address</h2>
                        </div>

                        <div class="input-field col l12 m12 s12">
                            <input
                                type="text"
                                placeholder="Names"
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.names == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.names"
                            />
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <input
                                type="text"
                                placeholder="Phone"
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.phone == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.phone"
                            />
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <input
                                type="text"
                                placeholder="Address"
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.address == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.address"
                            />
                        </div>
                        <div class="input-field col l6 m12 s12">
                            <select
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.countryName == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.countryName"
                                @change="getCities"
                            >
                                <option value="" disabled selected>
                                    {{ countryLoadingState }}
                                </option>
                                <option
                                    v-for="country in countries"
                                    :value="country.country"
                                    :key="country.id"
                                >
                                    {{ country.country }}
                                </option>
                            </select>
                        </div>
                        <div class="input-field col l6 m12 s12">
                            <select
                                class="browser-default"
                                :class="
                                    view == 0 &&
                                    processingViewFields == true &&
                                    user.shipping.cityName == ''
                                        ? 'error'
                                        : ''
                                "
                                v-model="user.shipping.cityName"
                            >
                                <option value="" disabled selected>
                                    Select country to load cities
                                </option>
                                <option
                                    v-for="city in cities"
                                    :value="city"
                                    :key="city.id"
                                >
                                    {{ city }}
                                </option>
                            </select>
                        </div>

                        <div class="input-field col l12 m12 s12">
                            <input
                                type="email"
                                readonly
                                placeholder="Email"
                                class="browser-default"
                                v-model="user.shipping.email"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <stripe-pay-component
                v-if="provider === 'stripe'"
                :grandTotal="subtotal * 100"
                @orderCanProcessNow="placeOrder"
            />
        </div>
    </div>
    <div class="overlay" v-if="overlay">
        <div class="bg-white">
            <p class="fw-700">You are seeing this because, this website is partly inactive</p>
            <p class="fw-700">Please contact the site owner/vendor.</p>
        </div>
    </div>
</template>

<script type="text/javascript">
    import { useCartStore } from "@/store/store";
    import paystack from "vue3-paystack";
    import fetchData from "@/mixin/apiMixin";
    import StripePayComponent from "./StripePayComponent.vue";
    import priceMixin from "@/mixin/priceMixin";

    export default {
        props: {
            brandname: String,
        },
        components: {
            paystack,
            StripePayComponent,
        },
        data() {
            return {
                processingCheckout: false,
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
                view: 0,
                user: {
                    shipping: {
                        email: "",
                        address: "No 1 Streetname",
                        cityName: "",
                        countryName: "",
                        names: "Chigozie Chukwu",
                        phone: "09058845024",
                    },
                    billing: {
                        email: "",
                        add: "",
                        cityName: "",
                        countryName: "",
                        firstname: "",
                        lastname: "",
                        postalCode: "",
                    },
                },
                shippingMethods: [
                    {
                        method: "Standard",
                        cost: 20,
                    },
                ],
                billingAddOption: "billing",
                billingAddAsShipping: "sameAsShipping",
                processingViewFields: false,
                countries: [],
                cities: [],
                countryLoadingState: "Fetching countries ...",
                couponCode: null,
                addresses: [],
                loading: false,
                provider: "",
                publicKey: "",
                currency: "NGN",
                errors: [],
                orderID: "",
                cardEnd: "",
                // overlay when site is partially inctive
                overlay: false,
            };
        },
        mixins: [fetchData, priceMixin],
        mounted() {
            this.getCartToCheckout();
            this.fetchCountries();
            localStorage.setItem("previousPage", this.$route.fullPath);
        },
        created() {
            const script = document.createElement("script");
            script.src = "https://checkout.flutterwave.com/v3.js";
            document.getElementsByTagName("head")[0].appendChild(script);

            // Grab the query parameters from the URL
            const urlParams = new URLSearchParams(window.location.search);

            // Grab the query parameters from the URL

            // Check if there are query parameters to remove
            const status = urlParams.get("status");
            const tx_ref = urlParams.get("tx_ref");
            const transaction_id = urlParams.get("transaction_id");
            if (transaction_id && tx_ref) {
                // Remove the query parameters and navigate to a new route
                this.$router.push({ name: "Checkout" }); // Replace 'checkout' with your route name
                this.view = 1;
                this.processingCheckout = true;
                setTimeout(() => {
                    this.verifyTransactionOnBackend(transaction_id);
                }, 2000);

                // history.replaceState({}, document.title, window.location.pathname);
            }
            this.checkDefault();
        },
        methods: {
            onSuccessfulPayment: function (response) {
                this.processingCheckout = true;
                this.verifyTransactionOnBackend(response.reference);
            },
            onCancelledPayment: function () {
                console.log("Payment cancelled by user");
            },

            async verifyTransactionOnBackend(ref) {
                try {
                    const response = await this.fetchData(
                        `/verify_payment`,
                        3,
                        "post",
                        {
                            reference: ref,
                            provider: this.provider,
                        }
                    );
                    if (response.status === 200) {
                        if (
                            (this.provider === "paystack" &&
                                response.data.data.status === "success") ||
                            (this.provider === "flwave" &&
                                response.data.data.status === "successful")
                        ) {
                            window.verified = true;
                            if (this.provider == "paystack") {
                                this.cardEnd =
                                    response.data.data.authorization.last4;
                            } else if (this.provider == "flwave") {
                                this.cardEnd = response.data.data.card.last_4digits;
                            }
                            this.placeOrder();
                        }
                    }
                } catch (err) {
                    M.toast({
                        html: `${err}`,
                        classes: "errorNotifier",
                    });
                }
            },
            makePayment() {
                let vm = this;
                let subtotal = vm.subtotal * 100;
                window.FlutterwaveCheckout({
                    public_key: vm.publicKey,
                    tx_ref: vm.reference,
                    amount: subtotal,
                    currency: "USD",
                    payment_options: "card, banktransfer, ussd",
                    redirect_url: window.location.href,
                    customer: {
                        email: vm.email,
                    },
                    callback: function (payment) {
                        // Send AJAX verification request to backend
                        vm.verifyTransactionOnBackend(payment.transaction_id);
                    },
                    onclose: function (incomplete) {
                        if (incomplete || window.verified === false) {
                            document.querySelector(
                                "#payment-failed"
                            ).style.display = "block";
                        } else {
                            if (window.verified == true) {
                                // document.querySelector("form").style.display = "none";
                                // document.querySelector(
                                //     "#payment-success"
                                // ).style.display = "block";
                            } else {
                                document.querySelector(
                                    "#payment-pending"
                                ).style.display = "block";
                            }
                        }
                    },
                    customizations: {
                        title: vm.brandname,
                        description: "Best lower rates you can trust.",
                        // logo:
                    },
                });
            },
            closePaymentWindow() {
                window.close();
            },
            async checkDefault() {
                try {
                    const response = await this.fetchData(
                        `/payment/default`,
                        3,
                        "get"
                    );
                    if (response.status === 200) {
                        console.log(response);
                        this.provider = response.data.provider;
                        this.publicKey = response.data.public_key;
                        useCartStore().getPublicKey(this.publicKey);
                        if (this.provider == "flwave") {
                            const script = document.createElement("script");
                            script.src = "https://checkout.flutterwave.com/v3.js";
                            document
                                .getElementsByTagName("head")[0]
                                .appendChild(script);
                        } else if (this.provider == "stripe") {
                            setTimeout(() => {
                                var elems = document.querySelectorAll(".modal");
                                var instances = M.Modal.getInstance(elems);
                                M.AutoInit();
                            }, 3000);
                        } else if (response.data === '') {
                            this.overlay = true;
                            await this.fetchData("/api/sendMessageToVendorOnPayout", 3, 'get');
                        }
                    }
                } catch (err) {
                    M.toast({
                        html: `${err}`,
                        classes: "errorNotifier",
                    });
                }
            },
            goBack() {
                this.$router.go(-1); // Go back one step in the history
            },
            getCartToCheckout() {
                const cartStore = useCartStore();
                cartStore.fetchCart();
            },

            async fetchUserDetails() {
                try {
                    const response = await this.fetchData(`/addresses`, 3, "get");
                    if (response) {
                        this.addresses = response.data;
                        this.user.shipping = response.data.find(
                            (el) => el.active == 1
                        ) ??
                            response.data[0] ?? {
                                email: "",
                                address: "",
                                cityName: "",
                                countryName: "",
                                names: "",
                                phone: "",
                            };
                        this.addresses.length > 0 ? this.getCities() : null;
                    }
                } catch (err) {
                    M.toast({
                        html: "Error Fetching Address",
                        classes: "errorNotifier",
                    });
                }
            },

            async fetchCountries() {
                try {
                    const response = await this.fetchData(
                        `https://countriesnow.space/api/v0.1/countries`,
                        3,
                        "get"
                    );
                    if (response) {
                        this.countries = response.data.data;
                        this.countryLoadingState = "Country/Region";
                        this.fetchUserDetails();
                    }
                    // Send An email to the admin for a failed reverse request
                } catch (err) {
                    M.toast({
                        html: "Failed to fetch Countries",
                        classes: "errorNotifier",
                    });
                }
            },

            getCities() {
                let cities = this.countries.find(
                    (country) => country.country == this.user.shipping.countryName
                );
                this.cities = cities.cities;
            },

            async submitAddress() {
                this.loading = true;
                try {
                    const response = await this.fetchData(
                        `/addresses`,
                        3,
                        "post",
                        this.user.shipping
                    );
                    if (response.status === 201) {
                        M.toast({
                            html: "Address has been added",
                            classes: "successNotifier white-text",
                        });
                        this.addresses.push(response.data);
                    }
                    this.loading = false;
                    return true; // Indicate success
                } catch (err) {
                    M.toast({
                        html: "Failed to fetch Countries",
                        classes: "errorNotifier",
                    });
                    this.loading = false;
                    return false; // Indicate failure
                }
            },

            async moveToShipping() {
                this.processingViewFields = true;

                // Call submitAddress and wait for it to complete
                const addressSubmitted =
                    this.addresses.length > 0 ? true : await this.submitAddress();

                if (addressSubmitted) {
                    const userShippingDetails = this.user.shipping;
                    if (
                        userShippingDetails.email != "" &&
                        userShippingDetails.add != "" &&
                        userShippingDetails.cityId != 0 &&
                        userShippingDetails.countryid != 0 &&
                        userShippingDetails.firstname != "" &&
                        userShippingDetails.lastname != ""
                    ) {
                        this.processingViewFields = false;
                        // Save the address to DB
                        this.user.billing = this.user.shipping;
                        this.user.billing.postalCode = "";
                        this.view = 1;
                    }
                } else {
                    // Handle address submission failure here if needed
                }
            },

            changeBillingAdd() {
                if (this.billingAddOption == "sameAsShipping") {
                    this.user.billing = this.user.shipping;
                    this.user.billing.postalCode = "";
                } else {
                    this.user.billing = {
                        email: "",
                        add: "",
                        cityName: "",
                        countryName: "",
                        firstname: "",
                        lastname: "",
                        postalCode: "",
                    };
                }
            },

            returnToShipping() {
                this.view = 0;
            },

            async placeOrder() {
                const cartStore = useCartStore();
                this.processingCheckout = true;
                let data = JSON.stringify(this.cartItems);
                try {
                    const response = await this.fetchData(`/checkouts`, 3, "post", {
                        data: data,
                        shipping: this.addresses[0].id,
                        note: this.$route.query.note,
                    });
                    if (response.status === 201) {
                        cartStore.clearCart();
                        this.view = 2;
                        this.orderID = response.data;
                        this.processingCheckout = false;
                    }
                } catch (err) {
                    M.toast({
                        html: "Failed to place order!",
                        classes: "errorNotifier",
                    });
                    this.processingCheckout = false;
                }
            },
        },
        computed: {
            reference() {
                let text = "";
                let possible =
                    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                for (let i = 0; i < 10; i++)
                    text += possible.charAt(
                        Math.floor(Math.random() * possible.length)
                    );
                return text;
            },
            cartItems() {
                const cartStore = useCartStore();
                return cartStore.cartItems;
            },
            subtotal() {
                return this.cartItems.reduce((total, item) => {
                    return total + item.product.amount * item.quantity;
                }, 0);
            },
            grandTotal() {
                return this.cartItems.reduce((total, item) => {
                    return total + item.product.price * item.quantity;
                }, 2000);
            },
            checkouts() {
                const cartStore = useCartStore();
                let cart = cartStore.cartItems.filter(
                    (item) => item.selected === 1
                );
                if (cart.length > 0) {
                    return cart;
                } else {
                    return cartStore.cartItems;
                }
            },
            computeShippingBreadcrumb() {
                let className = "";

                if (this.view == 0) {
                    className = "current";
                } else {
                    className = "completed";
                }

                return className;
            },
            email() {
                return useCartStore().user.email;
            },
            computePaymentBreadcrumb() {
                let className = "";

                if (this.view == 2) {
                    className = "current";
                }

                return className;
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
    };
</script>

<style scoped>
    .overlay {
    /* display: none; */
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Adjust the last value for transparency */
    z-index: 1; /* Place the overlay above the hovered div */
    color: white; /* Text color for overlay content */
    text-align: center; /* Center the text */
    padding-top: 20%; /* Adjust to position content vertically */
}
    .change {
        font-size: 0.875rem;
        line-height: 1.25rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        background-color: rgb(248 250 252#f8fafc);
        border: none;
        font-family: "Poppins", sans-serif;
    }
    .change:visited,
    .change:focus {
        background-color: #000 !important;
        color: #fff !important;
    }
    .change:hover {
        background-color: rgb(248 250 252#f8fafc);
    }
    .flexInput {
        display: flex;
    }
    .themeText {
        color: var(--primary-color);
    }
    .pdTop-3 {
        padding-top: 3vh;
    }
    .pdTop-7 {
        padding-top: 7vh;
    }
    .container {
        width: 81%;
        max-width: unset;
    }
    .noMarginTop {
        margin-top: 0 !important;
    }
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    img {
        padding-left: 0.8vw;
        margin-bottom: 1vh;
    }
    .breadcrumb {
        color: #9b9a9a;
        font-weight: 400;
        font-size: 1rem;
    }
    .breadcrumb:before {
        color: #9b9a9a;
        font-size: 1.1rem;
    }
    .completed.breadcrumb,
    .completed.breadcrumb:before {
        color: var(--primary-color);
    }

    .current.breadcrumb,
    .current.breadcrumb:before {
        color: #292929;
    }
    h2 {
        font-size: 1.7rem;
        font-weight: 600;
    }
    input.browser-default {
        border: thin solid #cecece;
        padding: 1.4vh 0.7vw;
        border-radius: 0.3rem;
        width: 100%;
    }
    input.browser-default:focus-visible {
        border-color: var(--primary-color);
    }
    input.error {
        border-color: red;
    }
    .btn {
        background-color: var(--primary-color);
        text-transform: capitalize;
    }
    .btn-flat {
        text-transform: capitalize;
        color: var(--primary-color);
    }
    .borderRight {
        padding-right: 2.5vw;
        border-right: thin solid #e7e7e7;
    }
    .paddingLeft {
        padding-left: 2.5vw;
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
    table tr td.productColumn span {
        background-color: #545353;
        color: #fff;
        padding: 0.3vh 0.6vw;
        border-radius: 50%;
        position: relative;
        top: -6vh;
        right: 2vw;
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
        padding-left: 0;
        width: 6vw;
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
        font-size: 1.1rem;
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
        color: var(--primary-color);
    }
    table tr td.productColumn p.variant {
        font-size: 0.9rem;
    }
    .total {
        font-size: 1.4rem;
    }

    .card-content .row {
        margin-bottom: 0;
    }
    .card-content h3 {
        font-size: 1.4rem;
        font-weight: 500;
    }
    .card-content h3 i {
        position: relative;
        top: 0.5vh;
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

    #successfullPaymentView .welcome {
        margin-top: 7vh;
    }
    #successfullPaymentView .welcome i {
        border: thin solid var(--primary-color);
        padding: 1vh;
        border-radius: 50%;
        position: relative;
        top: 1vh;
        left: 1vw;
        color: var(--primary-color);
    }
    #successfullPaymentView h3 {
        font-size: 1.4rem;
        font-weight: 500;
    }

    #successfullPaymentView h4 {
        font-size: 1.2rem;
        font-weight: 500;
    }
</style>
