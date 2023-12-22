<template>
    <div>
        <NavbarComponent />
        <div class="container">
            <h1>Payments</h1>

            <div class="row">
                <div class="col s12 l6">
                    <StripeCollectorComponent />
                </div>
                <div class="col s12 l6 border-left">
                    <PayoutProcessorComponent class=""/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import NavbarComponent from './partials/NavbarComponent.vue';
import PayoutProcessorComponent from './partials/PayoutProcessorComponent.vue';
import StripeCollectorComponent from './partials/StripeCollectorComponent.vue';
export default {
    components: { NavbarComponent, StripeCollectorComponent, PayoutProcessorComponent },
    mounted() {
        console.log('Component is mounted');
    }
}
</script>
<style scoped>
    .border-left {
        border-left: 1px solid rgb(52, 51, 51);
    }
</style>