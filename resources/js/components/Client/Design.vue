<template>
    <div>
        <NavbarComponent />

        <div class="row">
            <div class="col l6" :class="{'col l12': view === 2}">
                <FontColorComponent @handleView="handleViewChange" v-if="view == 0"/>
                <BrandingComponent @handleView="handleViewChange" v-if="view == 1" @brandName="brandName"/>
                <SiteSettingInputsComponent @handleView="handleViewChange" v-if="view == 2" :brand="brand"/>
            </div>
            <div class="col l6 s12" v-if="view !== 2">
                <h3>Preview</h3>
                <iframe :src="domain" width="100%" height="500px" frameborder="0"></iframe>
            </div>
        </div>

    </div>
</template>
<script>
import BrandingComponent from './BrandingComponent.vue';
import FontColorComponent from './FontColorComponent.vue';
import NavbarComponent from './NavbarComponent.vue';
import fetchData from "@/mixin/apiMixin";
import SiteSettingInputsComponent from './SiteSettingInputsComponent.vue';

export default {
    components: { NavbarComponent, FontColorComponent, BrandingComponent, SiteSettingInputsComponent },
    data() {
        return {
            view: 0,
            domain: "https://www.example.com",
            brand: "",
        }
    },
    mixins: [fetchData],
    methods: {
        async getSiteToPreview() {
            const response = await this.fetchData('/getdomainName', 1, 'get');
            if (response.status == 200) {
                let domain = `http://${response.data.domain}.localhost:8000`
                this.domain = domain;
            }
        },
        handleViewChange(evt) {
            this.view = evt;
        },
        brandName(evt) {
            this.brand = evt;
        }
    },
    created() {
        this.getSiteToPreview()
    }
    
}
</script>