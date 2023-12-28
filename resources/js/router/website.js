import {createRouter, createWebHistory} from 'vue-router';

import Dashboard from '../components/user/Dashboard.vue';
import Order from '../components/user/Order.vue';
import Wishlist from '../components/user/Wishlist.vue';
import RecentViewed from '../components/user/History.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/products',
            name: 'Account',
            component: Dashboard,
        },
        {
            path: '/logout',
            name: 'Logout',
            component: '',
        }
    ]
})

export default router;