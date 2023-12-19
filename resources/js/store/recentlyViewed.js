// store.js
import { defineStore } from 'pinia';

export const useRecentlyViewedStore = defineStore('recentlyViewed', {
    state: () => ({
        viewedItems: [],
    }),
    actions: {
        addViewedItem(item) {
            this.viewedItems.unshift(item);
        },
        removeViewedItem(index) {
            this.viewedItems.splice(index, 1);
        },
    },
});
