<template>
    <div>
        <div id="paymentCheckout" class="modal">
            <div class="modal-content">
                <h5 class="fw-500">Complete Order</h5>
                <form @submit.prevent="processPayment">
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
                    <button
                        type="submit"
                        class="btn btn-primary flex items-center"
                        :disabled="paying"
                    >
                        <i class="fa fa-spinner fa-spin" v-if="paying"></i>
                       <span v-else> Place Order</span>
                    </button>
                </form>
                <div class="flex items-center justify-center">
                    <p class="fw-400">Powered By Stripe</p>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
    import { loadStripe } from "@stripe/stripe-js";
    import {
        CardNumberElement,
        CardExpiryElement,
        CardCvcElement,
    } from "@stripe/stripe-js";
    import { useCartStore } from "@/store/store";
    import fetchData from "@/mixin/apiMixin";

    
    // const stripePromise = loadStripe(useCartStore().publicKey);
    export default {
        computed: {
            stripePromise() {
                return loadStripe(useCartStore().publicKey);
            },
            user() {
                let user = useCartStore().user;
                if (user !== undefined) {
                    return user;
                }
            }
        },
        mounted() {
            this.mountCardElements();
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
                paying: false,
            };
        },
        mixins: [fetchData],
        methods: {
            mountCardElements() {
                this.stripePromise.then((stripe) => {
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
                this.stripePromise.then((stripe) => {
                    const cardElement = this.cardNumberElement;
                    const names = this.user.names;
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
                                this.fetchData("/create-payment", 2, "post", {
                                        paymentMethodId: result.paymentMethod.id,
                                        amount: this.grandTotal,
                                    })
                                    .then((response) => {
                                        if (response.data.hasOwnProperty("clientSecret")) {
                                            // clientSecret property exists in the response.data object
                                            const clientSecret = response.data.clientSecret;
                                            this.paying = false;
                                            var elems = document.querySelector('#paymentCheckout');
                                            this.$emit('orderCanProcessNow', true);
                                            var instances = M.Modal.init(elems);
                                            instances.close();
                                            // axios.get(`/api/check-payment/${clientSecret}`).then(res => {
                                            //     console.log(res);
                                            // }).catch(err => {
                                            //     console.log(err);
                                            // })
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
        },
        props: {
            grandTotal: Number,
        }
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
        position: static;
        font-weight: bold;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 6px 12px;
    }

    .btn {
        color: var(--white);
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        background-color: var(--primary-color);
    }

    .error {
        background-color: transparent;
    }
    .btn:hover {
        background-color: var(--primary-color);
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
<!-- if (error === undefined) {
    axios.post("/myz/payment/complete", {
        token: token.value,
    })
} else {
    axios.post("/myz/payment/failure", {
        token: token.value,
        code: error.code,
        description: error.message,
    })
} -->