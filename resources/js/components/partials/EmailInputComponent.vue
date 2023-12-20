<template>
    <div>
        <div class="input-container mt-1">
            <label>Custom Email</label>
            <input
                type="search"
                v-model="customEmail"
                class="validate browser-default customTextInput"
                placeholder="Custom Email"
                data-no-label="true"
                @input="validateInput"
            />
            <div class="row" v-if="customEmail != ''">
                <p>
                    <label>
                        <input type="radio" @change="$emit('newCustomEmail', customEmail)"/>
                        <span>{{ displayValue }}</span>
                    </label>
                </p>
            </div>
            <small>Suggestions</small>
            <p>
                <span v-for="(suggestion, index) in suggestions" :key="index" class="suggestionsEmail" @click="prefillInput(suggestion)">{{ suggestion }}</span>
            </p>
            <small>Name you choose will be prepended with your choice of domain name ie <b>marketing</b>@yourchoicedomain.com</small>
        </div>
    </div>
</template>
<script>
    export default {
        computed: {
            displayValue() {
                return `${this.customEmail}@${this.domain}`;
            }
        },
        data() {
            return {
                customEmail: "",
                suggestions: [
                    'inquiry',
                    'info',
                    'help',
                    'support',
                    'helpdesk',
                    'sales'
                ],
                domainPrefill: ""
            };
        },
        methods: {
            validateInput(event) {
                // Use a regular expression to allow only letters and numbers
                const regex = /^[a-zA-Z0-9]*$/;

                if (!regex.test(this.customEmail)) {
                    // If the input contains characters other than letters and numbers, remove them
                    this.customEmail = this.customEmail.replace(/[^a-zA-Z0-9]/g, "");
                    this.customEmail = event.target.value;
                }
            },
            prefillInput(suggestion) {
                this.customEmail = `${suggestion}`;
            }
        },
        props: {
            domain: String
        },
        watch: {
            domain: {
                handler(val) {
                    this.domainPrefill = val;
                },
            },
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
    .suggestionsEmail {
        background: rgba(211, 211, 211, 0.252);
        border: 1px solid rgba(0, 0, 0, 0.274);
        padding: .5vw;
        border-radius: 2vw;
        margin-right: 1vw;
        cursor: pointer;
    }
</style>