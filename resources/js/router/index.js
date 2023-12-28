import { createRouter, createWebHistory } from 'vue-router';
import { useCartStore } from '../store/store.js';

const folderProp = document.getElementById('app').getAttribute('data-folder-prop');
const trimmed = folderProp !== null ? folderProp.replace(/\s+/g, '') : null;
// import HomeComponent from '../../js/components/websites/Home.vue';
const routes = [
    {
        path: '/cart',
        name: 'Cart',
        component: () => import(`../components/websites/static/Cart/ShoppingCartComponent.vue`),
    },
    {
        path: '/checkout',
        name: 'Checkout',
        component: () => import(`../components/websites/static/Checkout/Checkout.vue`),
        meta: { requiresAuth: true } // Add a meta field to specify that this route requires authentication
    },
    {
        path: '/search/:category_name',
        name: 'product-search-category',
        component: () => import(`../components/websites/static/ProductSearch/ProductSearch.vue`),
    },
    {
        path: '/blog_details/:blog-name',
        name: 'blog-detail',
        component: () => import(`../components/websites/static/Blog/BlogComponent.vue`),
    },
    // For PAGES with no auth like products, search,
    {
        path: '/products/:product_name',
        name: 'product-details',
        component: () => import(`../components/websites/static/Product/Product.vue`),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
router.beforeResolve(async (to, from, next) => {

    if (to.matched.some(record => record.meta.requiresAuth)) {
        try {
            // Make a server request to check authentication status
            const response = await axios.get('/user'); // Replace with your authentication check function

            if (response.data != "") {
                next();
            } else {
                // User is not authenticated, redirect to the login page
                next({ name: 'Login' });
            }
        } catch (error) {
            console.error('Authentication check failed:', error);
            // Handle the error (e.g., show an error message)
            next(false); // Prevent navigation if there's an error
        }
    } else {
        next();
    }
});

export default router;
