<template>
    <div class="container">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo">
                    <img src="/media/img/zebraLogo.png" class="responsive-img logo" alt="Logo Image of Zebraline.ai"/>
                </a>
            </div>
        </nav>

        <div class="center">
            <h1 class="header">Sign In</h1>
            <small>Don't have an account yet? <a href="/auth/signup" class="purple-text darken-1">Sign up</a></small>
        </div>
        <div class="row">
            <div class="col s7">
                <div class="row">
                    <form class="col s12" @submit.prevent="signin">
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="email" class="validate" v-model="user.email" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field col s12">
                                <input type="password" class="validate" v-model="user.password">
                                <label for="last_name">Password</label>
                            </div>
                        </div>
                        <a href="/auth/forgot-password" class="forgotpass right"
                                >Forgot Password?</a
                            >
                        <div class="flex justify-center">
                            <button type="submit" class="btn btn-large">
                                <span
                                    v-if="loading"
                                    class="fa-solid fa-circle-notch fa-spin"
                                ></span>
                                <span v-else>Log in</span>
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
    export default {
        data() {
            return {
                loading: false,
                user: {
                    email: "",
                    password: "",
                    rememberme: "",
                },
                showPassword: false,
            };
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            setCookie(cname, cvalue, exdays) {
                const d = new Date();
                d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
                let expires = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            },
            async signin() {
                this.loading = !this.loading;
                const previousPage = localStorage.getItem("previousPage") || "/";
                try {
                    this.user.previousPage = previousPage;
                    const response = await axios.post("/auth/login", this.user);
                    if (response.status === 200) {
                        if (response.data.status == 200) {
                            let redirectUrl = response.data.redirectUrl;
                            // if (response.data.user.role == "Admin") {
                            //     localStorage.setItem("loggedIn", true);
                            //     localStorage.setItem("editFlag", 1);
                            //     location.href = '/edit';
                            // }
                            // else {
                                location.href = redirectUrl;
                            // }
                            // this.setCookie(
                            //     "_token",
                            //     response.data.access_token,
                            //     2
                            // );
                            // window.location.href = "/dashboard";
                        } else if (response.data.status == 404) {
                            M.toast({
                                html: response.data.error,
                                classes: "errorNotifier",
                            });
                        }
                        this.loading = !this.loading;
                    }
                    if (response.data.status === 422) {
                        if (response.data.message == "Device changed") {
                            this.otpPrompt = true;
                            this.otp = response.data.otp;
                        }
                    }
                } catch (error) {
                    this.loading = !this.loading;
                    console.error("Error Logging in:", error);
                }
            },
        },
    }
</script>
