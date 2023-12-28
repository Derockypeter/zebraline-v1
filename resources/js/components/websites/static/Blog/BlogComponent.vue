<template>
    <div>
        <div class="loader" v-show="loading"></div>
        <div v-show="!loading">
            <NavbarComponent
                :categories="categories"
                :brandname="brandname"
                :email="email"
                :loggedIn="loggedIn"
            />
            <div class="row noMarginBottom" id="banner">
                <div class="container">
                    <div class="col l12 center-align">
                        <a href="/" class="breadcrumb"
                            >Home</a
                        >
                        <a href="#!" class="breadcrumb">{{ sliceTitle }}</a>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col s12 l8 m8">
                        <h2 class="post__header--title mb-15">
                            {{ blog.title }}
                        </h2>
                        <p class="blog__post--meta">
                            Posted On :
                            {{
                                moment(blog.created_at).format("MMMM Do, YYYY")
                            }}
                            / In :
                            <a
                                class="blog__post--meta__link"
                                href="#!"
                                v-if="blog.category"
                                >{{ blog.category.name }}</a
                            >
                        </p>

                        <div class="blog__thumbnail mb-30" v-if="blog.images">
                            <img
                                class="blog__thumbnail--img border-radius-10 responsive-img"
                                :src="blog.images[0].url"
                                alt="blog-img"
                            />
                        </div>
                        <div class="blog-body">
                            <p v-html="blog.body"></p>
                        </div>
                    </div>
                    <div class="col s12 l4 m4 mt-7" v-if="related.length > 0">
                        <h3>Other Related Blog Articles</h3>
                        <div class="row" v-for="blog in related" :key="blog.id">
                            <div class="col s12">
                                <!-- <h6 class="header">{{ blog.title.slice(0, 10) }}...</h6> -->
                                <div class="card horizontal">
                                    <div class="card-image">
                                        <img
                                            :src="blog.images[0].url"
                                            :alt="blog.title"
                                            class="responsive-img"
                                        />
                                    </div>
                                    <div class="card-stacked">
                                        <div class="card-content">
                                            <p
                                                v-html="blog.body.slice(0, 70)"
                                            ></p>
                                        </div>
                                        <div class="card-action">
                                            <a
                                                class="link" href="#"
                                                @click="openBlog(blog)"
                                            >
                                                Read more
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <FooterComponent
                :categories="categories"
                :socials="socials"
                :loggedIn="loggedIn"
                :brandname="brandname"
            />
        </div>
    </div>
</template>
  
  <script>
    import FooterComponent from "../Footer";
    import NavbarComponent from "../Navbar";
    import moment from "moment";
    import fetchData from "@/mixin/apiMixin";

    export default {
        mounted() {
            this.getBlog();
        },
        data() {
            return {
                blog: {},
                loading: false,
                related: [],
            };
        },
        computed: {
            sliceTitle() {
                if (Object.entries(this.blog).length !== 0) {
                    if (this.blog.title.length > 30) {
                        return `${this.blog.title.slice(0, 30)}...`;
                    } else {
                        return this.blog.title;
                    }
                }
            }
        },
        mixins: [fetchData],
        methods: {
            openBlog(blog) {
                this.$router.replace({
                    name: "blog-detail",
                    query: {
                        additionalData: blog.id ?? "blog_id",
                    },
                    params: {
                        blog: blog.title,
                        query: {
                            additionalData: blog.id ?? "blog_id",
                        },
                    },
                });
                setTimeout(() => {
                    location.reload()
                }, 100);
            },
            moment(arg) {
                return moment(arg);
            },
            navigateToHome() {
                // Programmatically navigate to the root route '/'
                this.$router.push("/");
            },
            async getBlog() {
                const blogName = this.$route.params.blog;
                const blog_id = this.$route.query.additionalData;
                console.log(blogName);
                this.loading = true;
                try {
                    // Make the API request using the product name
                    const response = await this.fetchData(
                        `/api/blog/${blogName}?id=${blog_id}`,
                        3,
                        "get"
                    );
                    this.blog = response.data.blogpost;
                    this.related = response.data.related;
                    this.loading = false;
                } catch (error) {
                    console.error("Error fetching product:", error);
                }
            },
        },
        props: {
            categories: Array,
            socials: Object,
            loggedIn: Boolean,
            brandname: String,
            email: String,
        },
        components: { NavbarComponent, FooterComponent },
    };
</script>
  
  <style scoped>
    .link {
        color: var(--primary-color);
    }
    .card .card-content {
        padding: 1vh;
    }
    .mt-7 {
        margin-top: 18vh;
    }
    .container {
        font-family: "Jost", sans-serif;
    }
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    #banner {
        background-color: #0084d6;
        padding: 4vh 0;
    }
    .breadcrumb {
        font-size: 1.7rem;
    }
    .breadcrumb::before {
        font-size: 1.9rem;
    }
    .blog__thumbnail--img {
        width: 100%;
        display: block;
        height: 593px;
    }
    .border-radius-10 {
        border-radius: 1rem;
    }
    .blog-body {
        font-size: 1.6rem;
    }
    @media only screen and (min-width: 1600px) {
        .post__header--title {
            font-size: 3.5rem;
            line-height: 4rem;
            font-weight: 700;
        }
    }
    .blog__thumbnail {
        line-height: 1;
    }
    .blog__thumbnail {
        overflow: hidden;
    }
    @media only screen and (min-width: 1366px) {
        .mb-30 {
            margin-bottom: 3rem;
        }
    }
    @media only screen and (max-width: 767px) {
        h2 {
            font-size: 1.75rem;
        }
        h3 {
            font-size: 1.3rem;
        }
        .blog__thumbnail--img {
            height: unset;
        }
        .blog-body {
            font-size: 1.2rem;
        }
        .mt-7 {
            margin-top: 3.5vh;
        }
        .card .card-action {
            padding-top: 10px;
            padding-bottom: 10px;
        }
    }
</style>
  