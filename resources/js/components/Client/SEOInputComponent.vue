<template>
    <div>
        <img src="/media/img/seoguide.png" class="responive-img" alt="A description of what the SEO looks like" />
        <form action="">
            <div class="row">
                <div class="input-field col s12">
                    <textarea class="materialize-textarea" v-model="seo.description"></textarea>
                    <label for="">SEO Description</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" class="validate" v-model="seo.title">
                    <label for="">SEO Title</label>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
import OpenAI from 'openai';
const key = import.meta.env.VITE_OPENAI_KEY;
const openai = new OpenAI({
    apiKey: key,
    dangerouslyAllowBrowser: true
});
export default {
    data() {
        return {
            seo: {
                title: "",
                description: "",
            },
            checkingGPT: false,
            suggestion: "",
            // key: import.meta.env.VITE_OPENAI_KEY,
        }
    },
    props: {
        state: String,
        country: String,
        brand: String,
        whatyousell: String,
    },
    methods: {
        async mkReqToGPT() {
            
            // let product = 'Kitchen Appliances'; 
            // let location = "Atlanta, USA";
            // let domainName = "Cook Fun";
            let product = this.whatyousell;
            let location = `${this.state}, ${this.country}`;
            let domainName = this.brand;

            const chatCompletion = await openai.chat.completions.create({
                model: "gpt-4",
                messages: [
                // { role: "system", content: "You are a professional graphics designer" },
                { role: "system", content: "You are an SEO expert" },
                // { role: "assistant", content: "Can I have example of navbars to learn from." },
                // { role: "user", content: "Yes. Here they are: "+navBarExamplesContent.toString() },
                { role: "user", content: `Generate SEO title, keywords and description for an ecommerce website that sells ${product} in ${location}. The company name is ${domainName}` },
                // { role: "user", content: `whats the best DAAL-E prompt to produce a captivating image for a ${products} ecommerce website. Consider the fact that the site primary color is red.`},
                ],
            });

            console.log(chatCompletion.choices[0].message);
        },
        async saveMeta() {
            let formData = new FormData();
            formData.append("description", this.localmeta.description);
            formData.append("title", this.localmeta.title);
            try {
                const response = await this.fetchData(
                    "/api/meta_data",
                    3,
                    "post",
                    formData
                );
                if (response.status === 201) {
                    this.processing = false;
                    document.getElementById("onSuccessShow").innerText =
                        "Successfully Saved!";
                    setTimeout(() => {
                        document.getElementById("onSuccessShow").innerText = "";
                    }, 2000);
                }
            } catch (err) {
                M.toast({
                    html: `${err}`,
                    classes: "errorNotifier",
                });
            }
        },

    },
    watch: {
        whatyousell(newVal) {
            console.log(newVal);
            if (newVal.length > 3) {
                this.mkReqToGPT()
            }
        }
    }
}
</script>