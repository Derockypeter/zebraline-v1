<template>
    <div>
        <div class="container">
            <h1>Website Settings</h1>
            <div class="row">
                <div class="col s12 l6">
                    <ConnectDomainComponent @domainConnect="domainConnecticut"/>
    
                    <div
                        class="flex justify-center items-center h-80 flex-col"
                    >
                        <p>Register a domain</p>
                        <DomainNameInputComponent @newDomain="newDomain($event)" />
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
                    <EmailInputComponent :domain="domain" @newCustomEmail="newCustomEmail"/>
                </div>
            </div>
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
            domainConnecticut(evt) {
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
                        this.$emit('changeView', 1);
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
