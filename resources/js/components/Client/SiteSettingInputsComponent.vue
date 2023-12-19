<template>
    <div class="container">
        <h2>Website Settings</h2>
        <div class="row">
            <div class="col l6">
                <div class="row">
                    <div class="input-field col s12">
                        <p for="">Language</p>
                        <select class="browser-default" v-model="site.language">
                            <option value="" disabled selected>Choose your option</option>
                            <option value="English">English</option>
                            <option value="French">French</option>
                            <option value="German">German</option>
                        </select>
                    </div>
        
                    <div class="input-field col s12">
                        <p for="">Currency</p>
                        <select class="browser-default" v-model="site.currency">
                            <option value="" disabled selected>Choose your option</option>
                            <option value="USD">USD</option>
                            <option value="NGN">NGN</option>
                            <option value="YEN">YEN</option>
                        </select>
                    </div>
        
                    <div class="input-field col s12">
                        <p for="">Meaursement</p>
                        <select class="browser-default" v-model="site.measurement">
                            <option value="" disabled selected>Choose your option</option>
                            <option value="Imperial">Imperial</option>
                            <option value="Linear">Linear</option>
                        </select>
                    </div>
        
                    <div class="input-field col s12">
                        <p for="">Country</p>
                        <select class="browser-default" v-model="site.country"  @change="getCities">
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

                    <div class="input-field col s12">
                        <p for="">State</p>
                        <select class="browser-default" v-model="site.state">
                            <option value="" disabled selected>
                                    Select country to load cities
                            </option>
                            <option
                                v-for="city in cities"
                                :value="city"
                                :key="city.id"
                            >
                                {{ city }}
                            </option>
                        </select>
                    </div>

                    <div class="input-field col s12">
                        <p for="">Timezone</p>
                        <select class="browser-default" v-model="site.timezone">
                            <option value="" disabled selected>
                                    Select Option
                            </option>
                            <option
                               value="California(PST)"
                            >
                                California(PST)
                            </option>
                        </select>
                    </div>

                    <div class="input-field col s12">
                        <input type="text" v-model="major">
                        <label class="active" for="first_name2">What do you plan to sell?</label>
                    </div>
                </div>
            </div>
            <div class="col l6">
                <SEOInputComponent :brand="brand" :country="site.country" :state="site.state" :whatyousell="major" />
            </div>
        </div>
        <button class="btn purple darken-2" @click.prevent="saveDetailForSiteData">Next</button>
    </div>
</template>
<script>
import SEOInputComponent from './SEOInputComponent.vue';
import fetchData from "@/mixin/apiMixin";

export default {
    components: { SEOInputComponent },
    data() {
        return {
            site: {
                language: "",
                currency: "",
                measurement: "",
                country: "",
                state: "",
                timezone: "",
            },
            major: "",
            countries: [],
            cities: [],
        }
    },
    props: {
        brand: String,
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
                    this.fetchUserDetails();
                }
                // Send An email to the admin for a failed reverse request
            } catch (err) {
                // M.toast({
                //     html: "Failed to fetch Countries",
                //     classes: "errorNotifier",
                // });
            }
        },

        getCities() {
            let cities = this.countries.find(
                (country) => country.country == this.site.country
            );
            this.cities = cities.cities;
        },
        async saveDetailForSiteData() {
            try {
                let formData = new FormData();
                formData.append('name', this.branding.name);
                formData.append('logo', this.branding.logo);
                formData.append('favicon', this.branding.favicon)
                const response = await this.fetchData('/favicon_logo', 2, 'post', formData);

                console.log(response);

                this.$emit('handleView', 2);
                
            } catch (error) {
                
            }
        }
    },
    mounted() {
        this.fetchCountries();
    },
}
</script>