<template>
    <div>
        <h1>Select a theme</h1>

        <div class="row">
            <div class="col s12 l4 m4 borderRight cursor" :class="{selected: selectedTheme != null && selectedTheme === theme.id}" @click="selectTheme(theme.id)" v-for="(theme, index) in themes" :key="index">
                <img :src="theme.imageUrl" :alt="'Snapshot of '+theme.title" class="responsive-img">
                <p>{{ theme.title }}</p>

                <p>
                    {{ theme.description }}
                </p>
            </div>
        </div>

        <button @click="continueToDesign" class="btn btn-large">Continue</button>
    </div>
</template>
<script>
import fetchData from '@/mixin/apiMixin'
export default {
    data() {
        return {
            themes: [],
            selectedTheme: null,
        }
    },
    mixins: [fetchData],
    methods: {
        selectTheme(id) {
            this.selectedTheme = id;
        },
        async getAvailableThemes () {
            try {
                const response = await this.fetchData("/api/theme", 3, "get");
                if (response.status == 200) {
                    this.themes = response.data.themes;
                }
            } catch (error) {
                M.toast({
                    classes: 'error',
                    html: 'Error fetching themes, please try again later!'
                })
            }
        },
        async continueToDesign() {
            try {
                let data = {
                    navBarTemplateId: this.selectedTheme, 
                    heroTemplateId: this.selectedTheme, 
                    categoryTemplateId: this.selectedTheme, 
                    featuredProductsTemplateId: this.selectedTheme, 
                    blogTemplateId: this.selectedTheme, 
                    offersTemplateId: this.selectedTheme, 
                    reviewsTemplateId: this.selectedTheme, 
                    sellingPointTemplateId: this.selectedTheme, 
                    footerPointTemplateId: this.selectedTheme,
                }
                const response = await this.fetchData(`/create_template_ids`, 1, "post", data);
                if (response.status === 201) {
                    this.themes = response.data.themes;
                    location.href = '/client/design';
                }
            } catch (error) {
                M.toast({
                    classes: 'error',
                    html: 'Error fetching themes, please try again later!'
                })
            }
        },
    },
    mounted() {
        this.getAvailableThemes();
    }
}
</script>
<style scoped>
    .borderRight:not(:first-child) {
        border-left: 1px solid rgba(22, 22, 22, 0.234);
    }
    .cursor {
        cursor: pointer;
    }
    .cursor:hover {
        box-shadow: 1px 1px inset;
    }
    .selected {
        box-shadow: 1px 1px inset;
    }
</style>