<template>
    <div>
        <img src="/media/img/seoguide.png" class="responive-img" alt="A description of what the SEO looks like" />
        <form action="">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" v-model="whatyousell" @keyup="handleTyping">
                    <label class="active" for="first_name2">What do you plan to sell?</label>
                </div>
                <div v-if="checkingGPT">
                    <i class="fa fa-spinner fa-spin right" />
                    <span class="right">Loading AI generated SEO</span>
                </div>
            </div>
            <div class="row">
                <p for="">SEO Description</p>
                <div class="input-field col s12">
                    <textarea class="materialize-textarea" v-model="seo.description"></textarea>
                </div>
            </div>
            <div class="row">
                <p for="">SEO Keyword</p>
                <div class="input-field col s12">
                    <textarea class="materialize-textarea" v-model="seo.keyword"></textarea>
                </div>
            </div>
            <div class="row">
                <p for="">SEO Title</p>
                <div class="input-field col s12">
                    <input type="text" class="validate" v-model="seo.title">
                </div>
            </div>
            <small v-if="seo.description !== ''">You can edit the content generated or click on next to continue</small>
        </form>
    </div>
</template>
<script>
import OpenAI from 'openai';
const key = import.meta.env.VITE_OPENAI_API_KEY;
const openai = new OpenAI({
    apiKey: key,
    dangerouslyAllowBrowser: true
});
const titleRegex = /Title: "(.*?)"/;
const keywordsRegex = /Keywords: "(.*?)"/;
const descriptionRegex = /Description: "(.*?)"/;
export default {
    data() {
        return {
            seo: {
                title: "",
                description: "",
                keyword: "",
            },
            checkingGPT: false,
            suggestion: "",
            typingTimer: null,
            doneTypingInterval: 2000,
            whatyousell: ""
        }
    },
    props: {
        state: String,
        country: String,
        brand: String,
    },
    methods: {
        handleTyping() {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                this.userStoppedTyping();
            }, this.doneTypingInterval);
        },
        userStoppedTyping() {
            this.mkReqToGPT();
        },
        async mkReqToGPT() {
            
            // let product = 'Kitchen Appliances'; 
            // let location = "Atlanta, USA";
            // let domainName = "Cook Fun";
            this.checkingGPT = true;
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

            // console.log(chatCompletion.choices[0].message.content);
            let content = chatCompletion.choices[0].message.content;
            console.log(content);
            this.content(content);
            this.checkingGPT = false;
        },
        extractContent(regex, inputText) {
            const match = inputText.match(regex);
            return match ? match[1] : '';
        },
        content(text) {
            this.seo.title = this.extractContent(titleRegex, text);
            this.seo.keyword = this.extractContent(keywordsRegex, text);
            this.seo.description = this.extractContent(descriptionRegex, text);
            console.log(this.seo, keywordsRegex, descriptionRegex, titleRegex);
            this.$emit('seoContent', this.seo);
        },
        

    },
}
</script>