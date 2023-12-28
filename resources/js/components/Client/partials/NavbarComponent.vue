<template>
    <div>
        <div class="container">
            <div v-if="isLoading" class="overlay">
                <div class="loader"></div>
            </div>
            <nav>
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo">
                        <img src="/media/img/zebraLogo.png" class="responsive-img logo" alt="Logo Image of Zebraline.ai"/>
                    </a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="/client/design">Design</a></li>
                        <li><a href="client/products">Products</a></li>
                        <li><a href="/client/orders">Orders</a></li>
                        <li><a href="/client/analytics">Analytics</a></li>

                        <li><a class='dropdown-trigger btn indigo darken-1 white-text' href='#' data-target='dropdown1'>Publish</a></li>
                    </ul>
                    <ul id='dropdown1' class='dropdown-content'>
                        <li>
                            <a href="#!" @click="makePublic">
                                <b>Public</b>
                                <small class="block">Anyone can see the site</small>
                            </a>
                        </li>
                        <li class="divider" tabindex="-1"></li>
                        <li>
                            <a href="#!" @click="showModalToEnterPassword">
                                <b>Password</b>
                                <small class="block">Anyone with the password can see the site</small>
                            </a>
                        </li>
                        <!-- <li class="divider" tabindex="-1"></li> -->
                        <!-- <li>
                            <a href="#!"><b>Public</b></a>
                            <p>Anyone can see the site</p>
                        </li> -->
                    </ul>
                </div>
            </nav>

            <div id="passwordModal" class="modal">
                <div class="modal-content">
                    <h4>Password Protect Your site</h4>
                    <div class="row">
                        <form class="col s12" @submit.prevent="submitForm">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="password" class="validate" v-model="password" required>
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="password" class="validate" v-model="confirmPassword" required>
                                    <label for="confirm_password">Confirm Password</label>
                                    <small class="red-text" v-if="passwordMatch">Passwords don't match!</small>
                                </div>
                            </div>
                            <button class="btn indigo darken-2 waves waves-effect col s12">
                                <i class="fas fa-spinner fa-spin" v-if="isLoading"></i>
                                <span v-else>Save</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import fetchData from "@/mixin/apiMixin";
export default {

    data() {
        return {
            password: "",
            confirmPassword: "",
            passwordMatch: false,
            visibility: "",
            isLoading: false,
        }
    },
    mounted() {
        M.AutoInit();
    },
    mixins: [fetchData],
    methods: {
        showModalToEnterPassword() {
            let text = "You are about to publish your site making it password protected, only you and users you share password with can access the website. \nDo you wish to proceed?";
            if (confirm(text) == true) {
                text = "You pressed OK!";
                var elem = document.querySelector('#passwordModal');
                var instance = M.Modal.getInstance(elem);
                this.visibility = 'password';
                instance.open();
            } else {
                text = "You canceled!";
            }
            console.log(text);
        },

        async submitForm() {
            if (this.password === this.confirmPassword) {
                this.isLoading = !this.isLoading;
                try {
                    let data = {
                        siteVisibility: this.visibility,
                        password: this.password,
                    }
                    const response = await this.fetchData('/site_visibility', 2, 'post', data);
                    if (response.status === 200 || response.status === 201) {
                        M.toast({
                            classes: 'green darken-1',
                            html: 'Site visibility has been set to Password protected',
                        });
                        this.isLoading = !this.isLoading;
                    }

                    console.log(response);
                } catch (error) {
                    console.log(error);
                    this.isLoading = !this.isLoading;
                }
            } else {
                this.passwordMatch = true;
            }
        },

        async makePublic() {
            try {
                this.isLoading = !this.isLoading;
                let data = {
                    siteVisibility: 'public',
                }
                const response = await this.fetchData('/site_visibility', 2, 'post', data);
                if (response.status === 200 || response.status === 201) {
                    M.toast({
                        classes: 'green darken-1',
                        html: 'Site visibility has been set to public',
                    });
                    this.isLoading = !this.isLoading;
                }
            } catch (error) {
                console.log(error);
                this.isLoading = !this.isLoading;
            }
        }
    }
}
</script>
<style scoped>
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
    #dropdown1 {
        width: 15vw !important;
    }
    .dropdown-content li {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .dropdown-content li a {
        text-align: center;
    }
    #dropdown1 li a:hover {
        background-color: transparent !important;
    }
    .block {
        display: block;
    }
    .modal {
        width: 35%;
    }

    /* Style the overlay to cover the entire page */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7); /* semi-transparent white */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999; /* Ensure it's above other elements */
    }

    /* Style the loader */
    .loader {
        border: 6px solid #f3f3f3; /* Light grey */
        border-top: 6px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>
