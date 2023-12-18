<template>
    <div>
        <div class="container">
            <nav>
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo">
                        <img src="/media/img/zebraLogo.png" class="responsive-img logo" alt="Logo Image of Zebraline.ai"/>
                    </a>
                </div>
            </nav>
        </div>

        <div class="container" v-if="view == 0">
            <h1>Website Settings</h1>
            <div class="row">
                <div class="col s12 l6">
                    <connect-domain-component @domainConnect="domainConnect"/>
    
                    <div
                        class="flex justify-center items-center h-80 flex-col"
                    >
                        <p>Register a domain</p>
                        <domain-name-input-component
                            @newDomain="newDomain($event)"
                            @status="status($event)"
                            @change="checkout($event)"
                            :domainSelected="domainSelected"
                            @domainCheck="domainCheck"
                        />
                        <div class="flex flex-col gap-10 mt-4">
                            <button
                                class="publish button btn"
                                type="submit"
                                @click="saveNContinue"
                            >
                                Save and Continue
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col s12 l6">
                    <email-input-component :domain="domain" @newCustomEmail="newCustomEmail"/>
                </div>
            </div>
        </div>
        <div v-if="view == 1">
            <h1>Select A theme</h1>
        </div>
    </div>
</template>
<script>
    import DomainNameInputComponent from "../partials/DomainNameInputComponent.vue";
    import fetchData from "@/mixin/apiMixin";
    import ConnectDomainComponent from '../partials/ConnectDomainComponent.vue';
    import EmailInputComponent from '../partials/EmailInputComponent.vue';

    export default {
        mounted() {
            console.log('Dashboard');
        },
        components: {
            DomainNameInputComponent,
            ConnectDomainComponent,
            EmailInputComponent
        },
        data() {
            return {
                spinning: 0,
                view: 0,
                domainSelected: "",
                domain: "",
                user: "",
                brand: "",
                tenantId: "",
                email: "",
                plan: "",
                domainCheckPassed: false,
                plans: [],
                customEmail: "",
                domainConnect: "",
                // selectedPlanPrice: "",
            };
        },
        mixins: [fetchData],
        methods: {
            newDomain(evt) {
                this.domain = evt;
            },
            newCustomEmail(evt) {
                this.customEmail = evt;
            },
            domainConnect(evt) {
                this.domainConnect = evt;
            },
            async saveNContinue (){
                this.spinning = !this.spinning;
                let data = {
                    domain: this.domain,
                    domainConnect: this.domainConnect != '' ? '1' : '0',
                    customEmail: this.customEmail,
                }
                try {
                    const response = await this.fetchData(
                        "/tenant_make",
                        1,
                        "post",
                        data
                    );
                    if (response.status === 201) {
                        const responseData = response.data;
                        this.spinning = !this.spinning;
                        this.view = 1;
                    } else {
                        // Handle unexpected status codes if necessary
                        console.error(`Unexpected Status Code: ${response.status}`);
                    }
                } catch (error) {
                    this.spinning = !this.spinning;
                    M.toast({
                        html: `Error: ${error.message}`,
                        classes: "errorNotifier",
                    });
                    console.error(error);
                }
            }

            
        },
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
</style>