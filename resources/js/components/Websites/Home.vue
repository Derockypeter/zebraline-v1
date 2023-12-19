<template>
  <div>
    <component :is="NavbarComponent" :brandname="brandname" :categories="categories" :loggedIn="loggedIn" @showEditNavMenu="showEditNavMenu($event)" :email="email" :branddesc="branddesc"/>

    <component :is="HeroComponent"
      :loggedIn="loggedIn"
      @showHeroEditor="showHeroEditor($event)"
      :hero="hero"
      :timestamp="timestamp"
      id="heroSection"
      :class="{styleEditHover: loggedIn, isEditing: isEditingHero}" :title="loggedIn ? `Click to Edit Hero Section` : ``"
    />
    <component :is="CategoryComponent"
      id="productCategorySection"
      :categories="categories"
      :loggedIn="loggedIn"
      @showEditNavMenu="showEditNavMenu($event)"
      :catetimestamp="catetimestamp"
      :class="{styleEditHover: loggedIn, isEditing: isEditingCategory}" :title="loggedIn ? `Click to Edit Category Section` : ``"
    />
    <component :is="FeaturedProductsComponent"
      :products="products"
      @showProductEditor="showProductEditor"
      :loggedIn="loggedIn"
      id="featuredProductSection"
      :class="{styleEditHover: loggedIn, isEditing: isEditingProduct}" :title="loggedIn ? `Click to add/edit product section` : ``"
    />
    <component :is="BlogComponent"
      @showEditBlogMenu="showEditBlogMenu"
      :loggedIn="loggedIn"
      :blogs="blogs"
    />
    <component :is="ReviewsComponent" v-if="reviews.length != 0"
      :reviews="reviews"
    />
    <component :is="OffersComponent"
      :loggedIn="loggedIn"
      @showOffersEditor="showOffersEditor($event)"
      :hero="hero"
      :timestamp="timestamp"
      :offers="offers"
      id="offersSection"
      :class="{styleEditHover: loggedIn, isEditing: isEditingOffers}" :title="loggedIn ? `Click to offers section` : ``"
    />
    <component :is="SellingPointComponent"
      @showSellingPointEditor="showSellingPointEditor($event)"
      :loggedIn="loggedIn"
      :sellPoint="sellPoint"
      id="sellingPoint"
      :class="{styleEditHover: loggedIn, isEditing: isEditingSellingPoint}" :title="loggedIn ? `Click to edit sell point section` : ``"
    />
    <component :is="FooterComponent"
      :brandname="brandname"
      :categories="categories"
      @showSocialEditor="showSocialEditor"
      :socials="socials"
      :loggedIn="loggedIn"
      id="socialHandleSection"
      :branddesc="branddesc"
      :class="{styleEditHover: loggedIn, isEditing: isEditingFooter}" :title="loggedIn ? `Click to edit social handles point section` : ``"
    />
  </div>
</template>

<script>
import { markRaw } from 'vue';

export default {
  data() {
    return {
      NavbarComponent: null,
      HeroComponent: null,
      CategoryComponent: null,
      FeaturedProductsComponent: null,
      BlogComponent: null,
      OffersComponent: null,
      ReviewsComponent: null,
      SellingPointComponent: null,
      FooterComponent: null,
    }
  },
  props: {
    loggedIn: Boolean,
    brandname: String,
    sellPoint: Array,
    categories: Array,
    hero: Object,
    offers: Array,
    products: Array,
    reviews: Array,
    blogs: Array,
    timestamp: Number,
    catetimestamp: Number,
    email: String,
    socials: Object,
    branddesc: String,

    isEditingHero: Boolean,
    isEditingCategory: Boolean,
    isEditingProduct: Boolean,
    isEditingFooter: Boolean,
    isEditingOffers: Boolean,
    isEditingSellingPoint: Boolean,

    storeTypeId: Number,
    navbarTemplateId: Number,
    heroTemplateId: Number,
    categoryTemplateId: Number,
    featuredPrdTemplateId: Number,
    blogTemplateId: Number,
    offerTemplateId: Number,
    reviewTemplateId: Number,
    sellingPointTemplateId: Number,
    footerTemplateId: Number,
  },
  methods: {
    showEditNavMenu(evt) {
      this.$emit(`showEditNavMenu`, evt);
    },
    showHeroEditor(evt) {
      this.$emit(`showHeroEditor`, evt);
    },
    showOffersEditor(evt) {
      this.$emit(`showOffersEditor`, evt);
    },
    showSellingPointEditor(evt) {
      this.$emit(`showSellingPointEditor`, evt);
    },
    showProductEditor(evt) {
      this.$emit(`showProductEditor`, evt);
    },
    showEditBlogMenu(evt) {
      this.$emit(`showEditBlogMenu`, evt);
    },
    showSocialEditor(evt) {
      this.$emit(`showSocialEditor`, evt);
    },
    loadComponents() {
      // Dynamically import NavbarComponent based on templateId
      import(`../templates/${this.storeTypeId}/template${this.navbarTemplateId}/NavbarComponent.vue`)
        .then(component => {
          this.NavbarComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading NavbarComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.heroTemplateId}/HeroComponent.vue`)
        .then(component => {
          this.HeroComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading HeroComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.categoryTemplateId}/CategoryComponent.vue`)
        .then(component => {
          this.CategoryComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading CategoryComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.featuredPrdTemplateId}/FeaturedProductsComponent.vue`)
        .then(component => {
          this.FeaturedProductsComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading FeaturedProductsComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.blogTemplateId}/BlogComponent.vue`)
        .then(component => {
          this.BlogComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading BlogComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.offerTemplateId}/OffersComponent.vue`)
        .then(component => {
          this.OffersComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading OffersComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.reviewTemplateId}/ReviewsComponent.vue`)
        .then(component => {
          this.ReviewsComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading ReviewsComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.sellingPointTemplateId}/SellingPointComponent.vue`)
        .then(component => {
          this.SellingPointComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading SellingPointComponent:', error);
        });
      import(`../templates/${this.storeTypeId}/template${this.footerTemplateId}/FooterComponent.vue`)
        .then(component => {
          this.FooterComponent = markRaw(component.default || component);
        })
        .catch(error => {
          // Handle import error
          console.error('Error loading FooterComponent:', error);
        });
    }
  },
  mounted() {
    M.AutoInit();
  },
  created() {
    this.loadComponents()
  }
};
</script>
<style>
:root {
  /* --primary-color: #fff; */
  --secondary-color: #000;

  --primary-color: #d91a1a;
}
/* fixes design issues with dark mode */
body {
  background-color: #fff;
}
</style>
