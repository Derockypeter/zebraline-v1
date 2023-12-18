<template>
    <div>
        <div class="input-container mt-1">
            <label>Domain Name</label>
            <div class="row">
                <div class="col s8">
                    <input
                        type="search"
                        v-model="domain"
                        class="validate browser-default customTextInput"
                        placeholder="Domain"
                        @keyup="checkDomainAvailability($event)"
                        data-no-label="true"
                    />
                </div>
                <div class="col s4">
                    <button class="btn" @click="connect">Connect</button>
                </div>
            </div>
            <ol v-if="connectClick">
                <li>
                    Step 1: Change your DNS records in your third-party domain provider account
                </li>
                <li>
                    On your domain provider’s website, log in to your account.
                </li>
                <li>
                    Find the DNS settings or domain management area. <small>You'll have to change the Nameserver(NS) records on your dashboard to the ones to be sent to your email after registration</small> 
                </li>
                <li>Save your changes.</li>
            </ol>
        </div>
    </div>
</template>
<script>
    export default {
        computed: {
            
        },
        data() {
            return {
                domain: "",
                nameservers: [],
                connectClick: false,
            };
        },
        methods: {
            makeRequestToGetNameservers () {
                // This will be ensured at the end so the user will have made payments
            },
            connect() {
                this.connectClick = true;
                this.$emit('domainConnect', domain);
            }
            
        },
    };
</script>
<style scoped>
    .customTextInput {
        width: 100%;
        height: 4vh;
        border: 1px solid #212121;
        background: #fff;

        padding-left: 1vw;
    }
    .input-container {
        position: relative;
        width: 100%;
    }

    .input-container input {
        padding-right: 25px; /* Adjust the padding to make room for the close icon */
    }
</style>