<template>
    <div class="container">

        <h4>Branding</h4>
        <form action="#">
            <div class="row">
                <p  v-for="choice in choices" :key="choice.id">
                    <label>
                        <input class="with-gap" type="radio" name="choice" @change="choiced = choice.id" />
                        <span>{{ choice.title }}</span>
                    </label>
                </p>
            </div>
            
            <div class="row" v-if="choiced === 1 || choiced === 2">
                <p>Brand Name</p>
                <div class="row">
                    <div class="input-field col s12">
                        <input value="Alvin" id="first_name2" type="text" class="validate" v-model="branding.name">
                    </div>
                </div>
            </div>
    
            <div class="row" v-if="choiced === 3 || choiced === 2">
                <p>Brand Logo</p>
                <div class="input-field col s12">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>File</span>
                            <input type="file" @change="handleLogoChange">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </div>
            </div>
    
            <p>Favicon</p>
            <div class="row">
                <div class="input-field col s12">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>File</span>
                            <input type="file" @change="handleFavIcon">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn purple darken-2" @click.prevent="saveDetailForSiteData">Next</button>
        </form>
    </div>
</template>
<script>
import fetchData from "@/mixin/apiMixin";

export default {
    data() {
        return {
            choiced: 2,
            choices: [
                {
                    id: 1,
                    title: "Only Shop name",
                },
                {
                    id: 2,
                    title: "Shop Name & Icon"
                },
                {
                    id: 3,
                    title: "Only Shop Icon"
                }
            ],
            branding: {
                name: "",
                favicon: null,
                logo: null,
            }
        }
    },
    mixins: [fetchData],
    methods: {
        handleLogoChange(evt) {
            console.log(evt);
            this.branding.logo = evt.target.files[0];
        },
        handleFavIcon(evt) {
            this.branding.favicon = evt.target.files[0];
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
                this.$emit('brandName', this.branding.name);
                
            } catch (error) {
                
            }
        }
    }
}
</script>