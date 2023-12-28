import {createRouter, createWebHistory} from 'vue-router';

import Dashboard from '../components/user/Dashboard.vue';
import Order from '../components/user/Order.vue';
import Wishlist from '../components/user/Wishlist.vue';
import RecentViewed from '../components/user/History.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/your_account/dashboard',
            name: 'Account',
            component: Dashboard,
        },
        {
            path: '/your_account/orders',
            name: 'Orders',
            component: Order,
        },
        {
            path: '/your_account/favorites',
            name: 'Wishlist',
            component: Wishlist,
        },
        {
            path: '/your_account/recently_viewed',
            name: 'History',
            component: RecentViewed,
        },
        {
            path: '/logout',
            name: 'Logout',
            component: '',
        }
    ]
})

export default router;