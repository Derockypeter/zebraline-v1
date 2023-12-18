<template>
    <div>
        <div class="input-container mt-1">
            <label>Domain Name</label>
            <input
                type="search"
                v-model="domain"
                class="validate browser-default customTextInput"
                placeholder="Search"
                @keyup="checkDomainAvailability($event)"
                @input="validateInput"
                data-no-label="true"
            />
            <span
                class="close-icon red-text lighten-3"
                @click="clearDomainInput"
                v-show="domain != '' && !domainCheckPassed"
                >&times;</span
            >
            <span
                class="close-icon green-text lighten-3"
                @click="clearDomainInput"
                v-show="domain != '' && domainCheckPassed"
                ><i class="fa fa-check"></i
            ></span>
        </div>
    </div>

    <div class="row">
        <div class="col s12 domianMainDiv">

            <div v-show="!checkingSuggestion">
                <template
                    v-for="(suggestion, index) in availableFQDN"
                    :key="index"
                >
                    <div class="row">
                        <div class="col s1">
                            <p class="domainSelectP">
                                <label>
                                    <input
                                        class="with-gap"
                                        name="group1"
                                        type="radio"
                                        :value="suggestion.name"
                                        @change="newDomain(suggestion.name)"
                                    />
                                    <span>{{ suggestion.name }}</span>
                                </label>
                            </p>
                        </div>
                        
                    </div>
                </template>
            </div>
            <div class="flex center-align p-10">
                <div v-if="checkingSuggestion">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Loading Suggestions</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        computed: {
            isDomainValid() {
                return this.domain.trim().length >= 2;
            },
            domainTdName() {
                let data;
                if (this.domainType !== "") {
                    data = this.domainType;
                }
                else if (this.plans !== "") {
                    let selected = this.domains.find((el) => el.id == this.plan);
                    if(selected) {
                        data = selected.name;
                    };
                }
                return data;
            },
            inputValue () {
                let data;
                if (this.domainType !== "") {
                    data = `${this.domain}.${this.domainType}`;
                } else if (this.plan !== "") {
                    data = `${this.domain}.${this.domainTdName}`;
                }
                return data;
            }
        },
        data() {
            return {
                checkingSuggestion: false,
                domain: "",
                domainSuggestions: [],
                suggestionLoaded: false,
                loadingSuggestions: false,
                timer: null,
                domains: [
                    {
                        id: 1,
                        name: "store",
                    },
                    {
                        id: 2,
                        name: "com",
                    },
                    {
                        id: 3,
                        name: "shop",
                    },
                    {
                        id: 4,
                        name: "net",
                    },
                    {
                        id: 5,
                        name: "biz",
                    },
                ],
                availableFQDN: [],
            };
        },
        methods: {
            newDomain(domain) {
                this.$emit('newDomain', domain)
            },
            validateInput() {
                // Use a regular expression to allow only letters and numbers
                const regex = /^[a-zA-Z0-9]*$/;

                if (!regex.test(this.domain)) {
                    // If the input contains characters other than letters and numbers, remove them
                    this.domain = this.domain.replace(/[^a-zA-Z0-9]/g, "");
                }
            },
            checkDomainAvailability(event) {
                this.domainCheckPassed = false
                this.$emit('domainCheck', this.domainCheckPassed);
                // Check if the Backspace key was pressed
                if (event.keyCode === 8 || event.key === "Backspace") {
                    // Handle Backspace key event
                    // For example, clear the domain input value or perform any other desired action
                    this.domainCheckPassed = false;
                    return;
                }

                clearTimeout(this.timer);
                this.timer = setTimeout(() => {
                    // const inputValue = event.target.value.trim();
                    if (this.domain.length >= 2) {
                        this.checkingSuggestion = true;
                        
                        axios
                            .get(`/api/checklocaldomain?domain=${this.domain}`)
                            .then((res) => {
                                if (res.data.passed === 1) {
                                    this.domainSuggestions = [];
                                    this.domains.forEach(el => {
                                        this.domainSuggestions.push({name: `${this.domain}.${el.name}`})
                                    })

                                    let data = JSON.stringify(this.domainSuggestions);
                                    axios
                                        .post("/api/domain/check", {
                                            domain: data,
                                        })
                                        .then((res) => {
                                            console.log(res);
                                            if (res.data.status === 200) {
                                                this.availableFQDN = res.data.available;
                                            }
                                            this.checkingSuggestion = false;
                                        })
                                        .catch((err) => {
                                            console.log(err);
                                            this.checkingSuggestion = false;
                                        });
                                } else if (res.data.passed === 0) {
                                    this.checkingSuggestion = false;
                                    this.domainCheckPassed = false;
                                    this.$emit("status", false);
                                }
                            })
                            .catch((err) => {
                                console.log(err);
                                this.checkingSuggestion = false;
                            });
                    } else {
                        this.domainCheckPassed = null;
                    }
                    // this.removeLabel(el);
                }, 1000);
            },
            mounted() {
                
            }
        },
    };
</script>
<style scoped>
    .inliblock {
        display: flex;
        justify-content: center;
        gap: 5vh;
    }
    .static {
        position: static;
    }
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

    .close-icon {
        position: absolute;
        top: 0;
        right: 0;
        padding: 10px;
        cursor: pointer;
        font-size: 1.75rem;
    }
    .supportText {
        color: #000;
        font-family: "Montserrat", sans-serif;
        font-size: 0.875rem;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    table {
        width: 95%;
    }

    table tr {
        border: 1px solid #d9d9d9;
    }

    table tr td {
        padding: 0.5rem 1rem;
    }

    .domainTdName {
        font-family: "Montserrat", sans-serif;
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--black);
    }

    .domainTdState {
        font-family: "Montserrat", sans-serif;
        font-weight: 500;
        font-size: 0.9rem;
        color: #078523;
        text-align: right;
    }

    .domainSelectP {
        margin: 0.5vh 0 0 0;
    }

    .domianMainDiv {
        margin-bottom: 1vh;
    }

    .unAvailTitle,
    .availTitle {
        font-family: "Montserrat", sans-serif;
        font-size: 12px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
    .unAvailTitle {
        color: #ad1010;
    }
    .availTitle {
        color: #9adf4c;
    }
    @media only screen and (max-width: 767px) {
        .supportText {
            font-size: 0.75rem;
            margin-bottom: 0;
        }
        .customTextInput {
            width: 100% !important;
        }
        .input-container {
            width: 100% !important;
        }
    }
</style>