<template>
    <div>
        <div id="paymentCheckout" class="">
            <div class="">
                <h5 class="fw-500">Payment Information</h5>
                <h6>All your payment information is securely submitted</h6>
                <form @submit.prevent="saveDefaultPayment">
                    <small>CREDIT CARD</small>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" class="validate" v-model="billing.cardHolderName">
                            <label for="city">Card holder Name</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <div class="card-input-wrapper">
                            <img
                                :src="cardBrandImage"
                                alt="Card Brand"
                                v-if="cardBrandImage && cardBrandImage !== null"
                                class="card-brand-image"
                            />
                            <div
                                id="card-number"
                                ref="cardNumber"
                                class="form-control"
                            ></div>
                            <span class="error" id="card-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="card-expiry">Expiration Date</label>
                        <div
                            id="card-expiry"
                            ref="cardExpiry"
                            class="form-control"
                        ></div>
                        <span class="error" id="card-date"></span>
                    </div>
                    <div class="form-group">
                        <label for="card-cvc">CVC</label>
                        <div
                            id="card-cvc"
                            ref="cardCvc"
                            class="form-control"
                        ></div>
                        <span class="error" id="card-cvc-error"></span>
                    </div>
                    <p>By saving your credit card detail, you authorize zebraline to save your information for future purchases, subscriptions and renewals.</p>
                    
                    
                    <small>BILLING ADDRESS</small>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" class="validate" v-model="billing.streetAddress">
                            <label for="Street Address">Street Address</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" class="validate" v-model="billing.city">
                            <label for="city">City</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <p for="">Country</p>
                            <select class="browser-default" v-model="billing.country"  @change="getStates">
                                <option value="" disabled selected>Choose your option</option>
                            
                                <option
                                    v-for="country in countries"
                                    :value="country.country"
                                    :key="country.id"
                                >
                                    {{ country.country }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <p for="">State</p>
                            <select class="browser-default" v-model="billing.state">
                                <option value="" disabled selected>
                                    Select country to load states
                                </option>
                                <option
                                    v-for="state in states"
                                    :value="state"
                                    :key="state.id"
                                >
                                    {{ state }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="text" class="validate" v-model="billing.postalCode">
                            <label for="city">Postal Code</label>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="btn btn-large flex items-center"
                        :disabled="savingCard"
                    >
                        <i class="fa fa-spinner fa-spin" v-if="savingCard"></i>
                       <span v-else> Save </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
  
<script>
    import { loadStripe } from "@stripe/stripe-js";
    // var stripe = Stripe(publicKey);  
    import fetchData from "@/mixin/apiMixin";
    // import {
    //     CardNumberElement,
    //     CardExpiryElement,
    //     CardCvcElement,
    // } from "@stripe/stripe-js";

    const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_KEY);

    export default {
        mounted() {
            this.mountCardElements();
            this.fetchCountries();
            this.loadIntent();
        },
        data() {
            return {
                cardBrandImage: null,
                isCardNumberValid: false,
                isCardExpiryValid: false,
                isCardCvcValid: false,
                stripe: "",
                cardNumberElement: null,
                cardExpiryElement: null,
                cardCvcElement: null,
                savingCard: false,
                countries: [],
                states: [],
                billing: {
                    streetAddress: "",
                    city: "",
                    country: "",
                    state: "",
                    postalCode: ""
                },
                intentToken: "",
                URL: import.meta.env.VITE_APP_URL,
            };
        },
        mixins: [fetchData],
        
        methods: {
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
                    }
                } catch (err) {
                    console.log(err);
                }
            },

            mountCardElements() {
                stripePromise.then((stripe) => {
                    this.stripe = stripe;

                    const elements = this.stripe.elements();

                    const style = {
                        base: {
                            fontFamily: "Arial, sans-serif",
                            fontSize: "16px",
                            lineHeight: "24px",
                        },
                    };

                    this.cardNumberElement = elements.create("cardNumber", {
                        style,
                    });
                    this.cardNumberElement.mount(this.$refs.cardNumber);

                    this.cardExpiryElement = elements.create("cardExpiry", {
                        style,
                    });
                    this.cardExpiryElement.mount(this.$refs.cardExpiry);

                    this.cardCvcElement = elements.create("cardCvc", { style });
                    this.cardCvcElement.mount(this.$refs.cardCvc);

                    this.cardNumberElement.on("change", (event) => {
                        this.cardBrandImage =
                            event.brand &&
                            `/media/img/${event.brand.toLowerCase()}.png`;

                        const displayError = document.getElementById("card-errors");
                        if (event.error) {
                            displayError.textContent = event.error.message;
                            this.isCardNumberValid = false;
                        } else {
                            displayError.textContent = "";
                            this.isCardNumberValid = true;
                        }
                    });

                    this.cardExpiryElement.on("change", (event) => {
                        const displayError = document.getElementById("card-date");
                        if (event.error) {
                            displayError.textContent = event.error.message;
                            this.isCardExpiryValid = false;
                        } else {
                            displayError.textContent = "";
                            this.isCardExpiryValid = true;
                        }
                    });

                    this.cardCvcElement.on("change", (event) => {
                        const displayError =
                            document.getElementById("card-cvc-error");
                        if (event.error) {
                            displayError.textContent = event.error.message;
                            this.isCardCvcValid = false;
                        } else {
                            displayError.textContent = "";
                            this.isCardCvcValid = true;
                        }
                    });
                });
            },

            // Save Card for when click on publish
            // Not Initiate a payment
            async  saveDefaultPayment() {
                this.savingCard = true;
                this.saveCardForFutureUse();
            },

            async saveCardForFutureUse() {
                const cardElement = this.cardNumberElement;
                var vm = this;
                this.stripe
                    .confirmCardSetup(this.intentToken.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: vm.billing.cardHolderName,
                            },
                        },
                    })
                    .then(function(result) {
                        // Handle result.error or result.setupIntent
                        console.log(result, result.setupIntent.payment_method);
                        if (result.setupIntent.status === 'succeeded') {
                            vm.savePaymentMethod(result.setupIntent.payment_method);
                        } else if (result.error) {
                            // console.log(result.error);
                            vm.savingCard = false;
                        }
                    });
            },

            loadIntent() {
                this
                    .fetchData(`${this.URL}/v1/user/setup-intent`, 7, 'get')
                    .then(
                        function (response) {
                            this.intentToken = response.data;
                        }.bind(this)
                    );
            },

            processPayment() {
                if (
                    !this.isCardNumberValid ||
                    !this.isCardExpiryValid ||
                    !this.isCardCvcValid
                ) {
                    M.toast({
                        html: "Please fill in all the required card details.",
                        classes: "errorNotifier",
                    });
                    return;
                }
                this.paying = true;
                stripePromise.then((stripe) => {
                    const cardElement = this.cardNumberElement;
                    const names = this.user.firstname+' '+this.user.lastname;
                    const email = this.user.email;
                    stripe
                        .createPaymentMethod({
                            type: "card",
                            card: cardElement,
                            billing_details: {
                                name: names,
                                email: email,
                            },
                        })
                        .then((result) => {
                            if (result.error) {
                                console.error(result.error);
                            } else {
                                // Call your Laravel API endpoint to create a charge using the payment method ID
                                axios
                                    .post("/api/create-payment", {
                                        paymentMethodId: result.paymentMethod.id,
                                        amount: this.grandTotal,
                                    })
                                    .then((response) => {
                                        if (response.data.hasOwnProperty("clientSecret")) {
                                            // clientSecret property exists in the response.data object
                                            const clientSecret = response.data.clientSecret;
                                            this.paying = false;
                                            this.$emit('orderCanProcessNow', true);
                                            // axios.get(`/api/check-payment/${clientSecret}`).then(res => {
                                            //     console.log(res);
                                            // }).catch(err => {
                                            //     console.log(err);
                                            // })
                                            console.log(clientSecret);
                                        } else {
                                            // clientSecret property does not exist in the response.data object
                                            console.error("clientSecret not found in response data");
                                        }
                                    })
                                    .catch((error) => {
                                        console.error(error);
                                    });
                            }
                        });
                });
            },

            async savePaymentMethod(method) {
                var vm = this;
                await this
                    .fetchData(`${this.URL}/v1/user/payments`, 1, 'post', {
                        payment_method: method,
                    })
                    .then(
                        function (result) {
                            console.log('Success!!!', result);
                            if (result.status === 204) {
                                vm.saveBillingAddress()
                            } else {
                                vm.savingCard = false;
                            }
                        }.bind(this)
                    );
            },

            async saveBillingAddress() {
                try {
                    const response = await this.fetchData('/billing', 2, 'post', this.billing);
                    console.log(response);
                    if (response.status === 201) {
                        this.savingCard = false;
                        M.toast({
                            classes: 'green',
                            html: 'Card details saved!'
                        })
                    }
                } catch (error) {
                    console.log(error);
                }
            },

            getStates() {
                let states = this.countries.find(
                    (country) => country.country == this.billing.country
                );
                this.states = states.cities;
            },
        },
    };
</script>
<style scoped>
    .fw-400 {
        color: rgb(212, 212, 212);
    }
    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        font-weight: bold;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 12px;
    }

    .btn {
        color: var(--primary);
        /* padding: 10px 20px; */
        border: none;
        cursor: pointer;
    }

    .error {
        background-color: transparent;
    }
    .btn:hover {
        background-color: #0056b3;
    }

    .btn:disabled {
        background-color: #6c757d;
        cursor: not-allowed;
    }
    .card-input-wrapper {
        position: relative;
    }

    .card-brand-image {
        position: absolute;
        right: 0px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: auto;
    }

    @media only screen and (min-width: 1024px) {
        .modal {
            width: 30%;
        }
    }
</style>