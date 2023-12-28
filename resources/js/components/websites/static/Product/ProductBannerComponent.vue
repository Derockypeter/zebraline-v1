<template>
    <div class="row noMarginBottom" id="banner">
        <div class="container">
            <div class="col l12 s12 m12 center-align">
                <a href="/" class="breadcrumb">Home</a>
                <router-link :to="{ name: 'product-search-category', params: { category_name: category.name ?? 'category' }, query: { additionalData: category.id ?? 'category_id' } }" class="breadcrumb" :loggedIn="loggedIn">{{ category.name }}</router-link>
                <a href="#!" class="breadcrumb">{{ product.title }}</a>
            </div>
        </div>
    </div>
</template>
  
<script>
    export default {
        mounted() {},
        methods: {
            navigateToHome() {
                // Programmatically navigate to the root route '/'
                this.$router.push("/");
            },
        },
        props: {
            product: Object,
			category: Object,
            loggedIn: Boolean
        },
    };
</script>
  
<style scoped>
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    #banner {
        background-color: var(--primary-color);
        padding: 4vh 0;
    }
    .breadcrumb {
        font-size: 1.7rem;
    }
    .breadcrumb::before {
        font-size: 1.9rem;
    }
</style>
  