<template>
    <div>
        <div class="container">
            <nav>
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo">
                        <img src="/media/img/zebraLogo.png" class="responsive-img logo" alt="Logo Image of Zebraline.ai"/>
                    </a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                        <li><a href="/client/design">Design</a></li>
                        <li><a href="client/products">Products</a></li>
                        <li><a href="/client/orders">Orders</a></li>
                        <li><a href="/client/analytics">Analytics</a></li>
                    </ul>
                </div>
            </nav>


        </div>
    </div>
</template>
<script>
export default {
    
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