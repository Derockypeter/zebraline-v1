<template>
    <div class="container">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo">
                    <img src="/media/img/zebraLogo.png" class="responsive-img logo" alt="Logo Image of Zebraline.ai"/>
                </a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li><a href="sass.html">Home</a></li>
                    <li><a href="collapsible.html">About</a></li>
                    <li><a href="/auth/login" class="btn purple darken-1">Login</a></li>
                </ul>
            </div>
        </nav>

        <div class="row">
            <div class="col s7">
                <h1 class="header">Build an e-commerce website with ease</h1>
                <small>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tempora iure facere quaerat perspiciatis ipsum. Vero praesentium, nemo totam quia architecto officia dolorum possimus nihil numquam. Reiciendis modi ab repellendus explicabo!</small>
            </div>
            <div class="col s1"></div>
            <div class="col s3">
                <img src="https://placehold.co/400x400" alt="Imae Placeholder"/>
            </div>
        </div>

    </div>
</template>
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
    .btn {
        box-shadow: none;
        text-transform: capitalize;
        color: #fff;
        width: 6vw;
        font-weight: 700;
    }
    .header {
        font-weight: 500;
        margin-bottom: 0;
    }
</style>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
