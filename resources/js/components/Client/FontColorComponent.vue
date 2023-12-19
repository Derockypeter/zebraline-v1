<template>
    <div class="container">
        <div class="row">
            <div class="col s12">
                <form>
                    <div class="row">
                        <h5>Font Choice</h5>
                        <div class="input-field col s12">
                            <p for="">Headlines</p>
                            <select class="browser-default" v-model="font.headline">
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Montesarrat">Montesarrat</option>
                                <option value="Poppins">Poppins</option>
                            </select>
                        </div>
            
                        <div class="input-field col s12">
                            <p for="">Body</p>
                            <select class="browser-default" v-model="font.body">
                                <option value="" disabled selected>Choose your option</option>
                                <option value="Montesarat">Montesarat</option>
                                <option value="Poppins">Poppins</option>
                                <!-- <option value="3">More Fonts</option> -->
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <h5>Color Choice</h5>
                        <div class="input-field col s12">
                            <p for="">Background</p>
                            <select class="browser-default" v-model="color.background">
                                <option value="" disabled selected>Choose your option</option>
                                <option value="White">White</option>
                                <option value="Blue">Blue</option>
                            </select>
                        </div>
            
                        <div class="input-field col s12">
                            <p for="">Body</p>
                            <select class="browser-default" v-model="color.buttons">
                                <option value="" disabled selected>Choose your option</option>
                                <option value="White">White</option>
                                <option value="Blue">Blue</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn" @click.prevent="saveDetailForSiteData">Next</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import fetchData from "@/mixin/apiMixin";
export default {
    data() {
        return {
            font: {
                body: "",
                headlines: "",
            },
            color: {
                background: "",
                buttons: ""
            }
        }
    },
    mixins: [fetchData],
    methods: {
        async saveDetailForSiteData() {
            try {
                let data = {
                    bodyFont: this.font.body,
                    headlineFont: this.font.headlines,
                    backgroundColor: this.color.background,
                    buttonColor: this.color.buttons,
                }
                const response = await this.fetchData('/font_color', 2, 'post', data);

                console.log(response);

                this.$emit('handleView', 1);
                
            } catch (error) {
                
            }
        }
    }
    
}
</script>