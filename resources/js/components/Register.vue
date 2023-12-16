<template>
    <div class="container">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo">
                    <img src="/media/img/zebraLogo.png" class="responsive-img logo" alt="Logo Image of Zebraline.ai"/>
                </a>
            </div>
        </nav>

        <div
            class="flex justify-center flex-col items-center p-24"
            v-if="otpView"
        >
            <OtpComponent
                :verificationError="verificationError"
                @verificationError="verifyReturn"
                @otp="otp"
                @resendOtp="otpSend"
            />
        </div>

        <div v-else>
            <div class="center">
                <h1 class="header">Sign Up</h1>
                <small>Already have an account? <a href="/auth/login" class="purple-text darken-1">Log in</a></small>
            </div>
            <div class="row">
                <div class="col s7">
                    <div class="row">
                        <form class="col s12" @submit.prevent="sendOtp">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" class="validate" required v-model="user.email">
                                    <label for="first_name">Email</label>
                                </div>
                                <div class="input-field col s12">
                                    <input type="password" class="validate" required v-model="user.password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*?([^\w\s]|[_])).{8,}$" :style="{ borderColor: !checkMinLength || !checkUpperNLowercase || !checkSpecialCharNNumber ? 'red' : '' }">
                                    <label for="last_name">Password</label>
                                </div>
                                <div class="self-start" v-if="showErrors">
                                    
                                    <p
                                        class="stepper"
                                        :class="{
                                            stepperPass: checkUpperNLowercase,
                                        }"
                                        v-show="!checkUpperNLowercase"
                                    >
                                        * At least one Upper and Lowercase letter (a - Z)
                                    </p>
                                    <p
                                        class="stepper"
                                        :class="{
                                            stepperPass:
                                                checkSpecialCharNNumber,
                                        }"
                                        v-if="!checkSpecialCharNNumber"
                                    >
                                        * Add a special characters _-&!,? & Number (0 - 9)
                                    </p>
                                    <p
                                        class="stepper"
                                        :class="{ stepperPass: checkMinLength }"
                                        v-if="!checkMinLength"
                                    >
                                        * Minimum of 8 characters in length
                                    </p>
                            </div>
                                <div class="input-field col s12">
                                    <input type="password" class="validate" required v-model="user.password_confirm">
                                    <label for="last_name">Confirm Password</label>
                                </div>
                                <span
                                    id="passwordMatchMessage"
                                    class="passReq"
                                ></span>
                            </div>
                            <div class="flex justify-center">
                                <button type="submit" class="btn btn-large">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        class="fa-spin"
                                        v-if="signingUp"
                                    >
                                        <path
                                            d="M12 1.5C12.1989 1.5 12.3897 1.57902 12.5303 1.71967C12.671 1.86032 12.75 2.05109 12.75 2.25V6.75C12.75 6.94891 12.671 7.13968 12.5303 7.28033C12.3897 7.42098 12.1989 7.5 12 7.5C11.8011 7.5 11.6103 7.42098 11.4697 7.28033C11.329 7.13968 11.25 6.94891 11.25 6.75V2.25C11.25 2.05109 11.329 1.86032 11.4697 1.71967C11.6103 1.57902 11.8011 1.5 12 1.5ZM12 16.5C12.1989 16.5 12.3897 16.579 12.5303 16.7197C12.671 16.8603 12.75 17.0511 12.75 17.25V21.75C12.75 21.9489 12.671 22.1397 12.5303 22.2803C12.3897 22.421 12.1989 22.5 12 22.5C11.8011 22.5 11.6103 22.421 11.4697 22.2803C11.329 22.1397 11.25 21.9489 11.25 21.75V17.25C11.25 17.0511 11.329 16.8603 11.4697 16.7197C11.6103 16.579 11.8011 16.5 12 16.5ZM22.5 12C22.5 12.1989 22.421 12.3897 22.2803 12.5303C22.1397 12.671 21.9489 12.75 21.75 12.75H17.25C17.0511 12.75 16.8603 12.671 16.7197 12.5303C16.579 12.3897 16.5 12.1989 16.5 12C16.5 11.8011 16.579 11.6103 16.7197 11.4697C16.8603 11.329 17.0511 11.25 17.25 11.25H21.75C21.9489 11.25 22.1397 11.329 22.2803 11.4697C22.421 11.6103 22.5 11.8011 22.5 12ZM7.5 12C7.5 12.1989 7.42098 12.3897 7.28033 12.5303C7.13968 12.671 6.94891 12.75 6.75 12.75H2.25C2.05109 12.75 1.86032 12.671 1.71967 12.5303C1.57902 12.3897 1.5 12.1989 1.5 12C1.5 11.8011 1.57902 11.6103 1.71967 11.4697C1.86032 11.329 2.05109 11.25 2.25 11.25H6.75C6.94891 11.25 7.13968 11.329 7.28033 11.4697C7.42098 11.6103 7.5 11.8011 7.5 12ZM4.575 4.575C4.71565 4.4344 4.90638 4.35541 5.10525 4.35541C5.30412 4.35541 5.49485 4.4344 5.6355 4.575L8.82 7.758C8.95662 7.89945 9.03221 8.0889 9.03051 8.28555C9.0288 8.4822 8.94992 8.67031 8.81086 8.80936C8.67181 8.94842 8.4837 9.0273 8.28705 9.02901C8.0904 9.03071 7.90095 8.95512 7.7595 8.8185L4.575 5.6355C4.4344 5.49485 4.35541 5.30412 4.35541 5.10525C4.35541 4.90638 4.4344 4.71565 4.575 4.575ZM15.1815 15.1815C15.3221 15.0409 15.5129 14.9619 15.7118 14.9619C15.9106 14.9619 16.1014 15.0409 16.242 15.1815L19.425 18.3645C19.5616 18.506 19.6372 18.6954 19.6355 18.8921C19.6338 19.0887 19.5549 19.2768 19.4159 19.4159C19.2768 19.5549 19.0887 19.6338 18.8921 19.6355C18.6954 19.6372 18.506 19.5616 18.3645 19.425L15.1815 16.242C15.0409 16.1014 14.9619 15.9106 14.9619 15.7118C14.9619 15.5129 15.0409 15.3221 15.1815 15.1815ZM19.425 4.5765C19.5651 4.71708 19.6438 4.90749 19.6438 5.106C19.6438 5.30451 19.5651 5.49492 19.425 5.6355L16.242 8.82C16.1005 8.95662 15.9111 9.03221 15.7144 9.03051C15.5178 9.0288 15.3297 8.94992 15.1906 8.81086C15.0516 8.67181 14.9727 8.4837 14.971 8.28705C14.9693 8.0904 15.0449 7.90095 15.1815 7.7595L18.3645 4.5765C18.5051 4.4359 18.6959 4.35691 18.8948 4.35691C19.0936 4.35691 19.2844 4.4359 19.425 4.5765ZM8.8185 15.1815C8.9591 15.3221 9.03809 15.5129 9.03809 15.7118C9.03809 15.9106 8.9591 16.1014 8.8185 16.242L5.6355 19.425C5.49405 19.5616 5.3046 19.6372 5.10795 19.6355C4.9113 19.6338 4.72319 19.5549 4.58414 19.4159C4.44508 19.2768 4.3662 19.0887 4.36449 18.8921C4.36279 18.6954 4.43838 18.506 4.575 18.3645L7.758 15.1815C7.89865 15.0409 8.08938 14.9619 8.28825 14.9619C8.48712 14.9619 8.67785 15.0409 8.8185 15.1815Z"
                                            fill="white"
                                        />
                                    </svg>
                                    <span v-else> Sign up </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col s1 relative">
                    <div class="wrapper">
                        <div class="line"></div>
                        <div class="wordwrapper">
                            <div class="word">or</div>                                        
                        </div>
                    </div>​
                </div>
                <div class="col s4">
                    <div class="mt-5">
                        <a class="btn btn-large blue darken-1" href="/auth/google">
                                <i class="fa-brands fa-google fa-3x "></i>
                            Continue with Google
                        </a>
                    </div>
                    <div class="mt-5">
                        <a class="btn btn-large indigo darken-2" href="/auth/facebook">
                            <i class="fa-brands fa-facebook-f fa-3x"></i>
                            Continue with Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .fbLogo {
        padding: 1vh 1.3vw 0 0;
    }
    .google {
        color: #3876f8;
    }
    nav {
        background-color: transparent;
        box-shadow: none;
        color: #000;
    }
    nav .brand-logo, nav ul a {
        color: #000;
    }
    .logo {
        width: 10vw;
    }
    .btn {
        box-shadow: none;
        text-transform: capitalize;
        color: #fff;
        font-weight: 700;
    }
    .header {
        font-weight: 500;
        margin-bottom: 0;
    }
    .flex {
        display: flex;
    }
    .justify-center {
        justify-content: center;
    }

    .relative {
        position: relative;
    }
    .wrapper {
        position: relative;
        height: 300px;
        margin: 10px;
    }

    .line {
        position: absolute;
        left: 49%;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #ccc;
        z-index: 1;
    }

    .wordwrapper {
        text-align: center;
        height: 12px;
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        margin-top: -12px;
        z-index: 2;
    }

    .word {
        color: #ccc;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 3px;
        font: bold 12px arial,sans-serif;
        background: #fff;
    }

    .mt-5 {
        margin-top: 5vh;
    }

    .gLogo {
        width: 3vw;
    }

</style>
<script>
    import OtpComponent from "./partials/OtpComponent.vue";
    import fetchData from "@/mixin/apiMixin";

    export default {
        components: {
            OtpComponent,
        },
        computed: {
            checkUpperNLowercase() {
                if (this.user.password.length >= 8) {
                    return /^(?=.*[a-z])(?=.*[A-Z])/.test(this.user.password);
                } 
                return true;
            },
            checkSpecialCharNNumber() {
                if (this.user.password.length >= 8) {
                    return /^(?=.*\d)(?=.*?([^\w\s]|[_]))/.test(this.user.password);
                } 
                return true;
            },
            checkMinLength() {
                let pLenght = this.user.password.length;
                if (pLenght >= 8) {
                    return true;
                } else {
                    return true;
                } 
            },
            showErrors() {
                let pLenght = this.user.password.length;
                if (pLenght >= 4) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        data() {
            return {
                user: {
                    email: "",
                    password: "",
                    password_confirm: "",
                },
                showPassword: false,
                signingUp: false,
                otpView: false,
                verificationError: null,
                previousPage: null,
            };
        },
        mixins: [fetchData],
        methods: {
            checkPasswordMatch() {
                var message = document.getElementById("passwordMatchMessage");
                if (this.user.password === this.user.password_confirm) {
                    message.innerHTML = "";
                    return true;
                } else {
                    message.innerHTML = "Passwords do not match!";
                    message.style.color = "red";
                    return false;
                }
            },

            checkPasswordCriteria() {
                // this.checkMinLength = this.user.password.length >= 8;
            },

            // Update the verificationError
            verifyReturn(evt) {
                this.verificationError = evt;
            },

            async signup() {
                // After user inputs
                // Show the oTP component to verify email **End of Demo**
                // On verify logs in the user
                // All registration from here goes to centralDomain.com
                try {
                    this.user.previousPage = this.previousPage;
                    const response = await this.fetchData(
                        `/auth/signup`,
                        1,
                        "post",
                        this.user
                    );
                    if (response.status == 201) {
                        let redirectUrl = response.data.redirectUrl;
                        if (response.data.user.role == "Admin") {
                            localStorage.setItem("loggedIn", true);
                            location.reload();
                        } else if (response.data.redirectUrl) {
                            location.href = redirectUrl;
                        }
                    } else if (response.status === 401) {
                        M.toast({
                            html: `${response.data.msg}`,
                            classes: "errorNotifier",
                        });
                    }
                } catch (error) {
                    console.error("Error saving data:", error);
                }
            },

            async sendOtp() {
                if (this.checkPasswordMatch()) {
                    this.signingUp = true;
                    await this.otpSend();
                } else {
                    M.toast({
                        html: "Passwords do not match!",
                        classes: "errorNotifier white-text",
                    });
                }
            },

            async otpSend() {
                try {
                    this.previousPage = localStorage.getItem("previousPage") || "/";
                    let email = {
                        email: this.user.email,
                        previousPage: this.previousPage,
                    };
                    const response = await this.fetchData(
                        `/api/save_n_send_otp`,
                        1,
                        "post",
                        email
                    );

                    // Check if the response status is 200 (OK)
                    if (response.status === 200) {
                        this.signingUp = false;
                        this.otpView = true;
                    } else if (response.status === 401) {
                        // Handle 401 Unauthorized status
                        M.toast({
                            html: `${response.data.msg}`,
                            classes: "errorNotifier",
                        });
                    } else {
                        // Handle other non-200 statuses
                        this.verificationError = "Error sending OTP!";
                    }
                } catch (error) {
                    // Handle errors
                    this.signingUp = false;
                    if (error.response && error.response.status === 401) {
                        // If it's a 401 error, access the error message in the response data
                        M.toast({
                            html: `${error.response.data.msg}`,
                            classes: "errorNotifier",
                        });
                    } else {
                        // Handle other errors or log them
                        console.log(`Error: ${error}`);
                    }
                }
            },

            togglePasswordVisibility() {
                this.showPassword = !this.showPassword;
            },

            otp(evt) {
                this.verifyOtp(evt);
            },

            async verifyOtp(otp) {
                try {
                    let data = {
                        email: this.user.email,
                        otp: otp,
                    };
                    const response = await axios.post("/api/verify_otp", data);
                    if (response.data.status === 200) {
                        this.signup();
                        M.toast({
                            html: "Email Verified Successfully!",
                            classes: "green darken-2",
                        });
                    } else if (response.data.status) {
                        this.verificationError =
                            "Invalid OTP: please check your mail for the correct OTP!!!";
                    }
                    console.log(response);
                } catch (error) {
                    console.error("Error saving data:", error);
                }
            },
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
