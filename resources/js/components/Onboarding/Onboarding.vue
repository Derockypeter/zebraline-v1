<template>
    <div>
        <div class="container">
            <nav>
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo">
                        <img src="/media/img/zebraLogo.png" class="responsive-img logo" alt="Logo Image of Zebraline.ai"/>
                    </a>
                </div>
            </nav>

            <DomainEmailComponent v-if="view == 0" @changeView="handleView"/>

            <TemplatePickerComponent v-if="view == 1" @changeView="handleView"/>
        </div>


    </div>
</template>
<script>
import DomainEmailComponent from './DomainEmailComponent.vue';
import TemplatePickerComponent from './ThemePickerComponent.vue';
export default {
    components: { DomainEmailComponent, TemplatePickerComponent },
    data() {
        return {
            view: 0,
        }
    },
    methods: {
        handleView(evt) {
            this.view = evt;
        }
    }
}
</script>
<style scoped>
    nav {
        background-color: transparent;
        box-shadow: none;
        color: #000;
    }
    nav .brand-logo, nav ul a {
        color: #000;
    }
    .logo {
        width: 10vw;
    }
</style>