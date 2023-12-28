<template>
    <div>
        <NavbarComponent />
        Screen in progress
    </div>
</template>
<script>
import NavbarComponent from '../templates/template1/NavbarComponent.vue';
export default {
    components: { NavbarComponent },
    mounted() {
        console.log('Dashboard');
    }
}
</script>