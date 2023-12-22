/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { defineAsyncComponent } from "vue";

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import WelcomeComponent from './components/Welcome.vue';
app.component('welcome-component', WelcomeComponent);

import RegisterComponent from './components/Register.vue';
app.component('register-component', RegisterComponent);


import LoginComponent from './components/Login.vue';
app.component('login-component', LoginComponent);

import DashboardComponent from './components/Client/Dashboard.vue';
app.component('dashboard-component', DashboardComponent);

import DesignComponent from './components/Client/Design.vue';
app.component('design-component', DesignComponent);

import ProductComponent from './components/Client/Product.vue';
app.component('product-component', ProductComponent);

import PaymentComponent from './components/Client/Payment.vue';
app.component('payment-component', PaymentComponent);

import Onboarding from './components/Onboarding/Onboarding.vue';
app.component('onboarding-component', Onboarding);

let checkTenantRenderer = document.getElementById('app').getAttribute('data-renederer-prop');
if (checkTenantRenderer !== null) {
    app.component(`renderer-website-component`, defineAsyncComponent(() => import(`./components/Websites/Index.vue`)));
    const pinia = createPinia();
    app.use(pinia)
    // app.use(router);
    // app.use(metaManager)
    // app.use(metaPlugin)
}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.mount('#app');
