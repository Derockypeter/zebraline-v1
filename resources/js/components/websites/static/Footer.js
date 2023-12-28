let FooterComponent;

const folderProp = document
    .getElementById("app")
    .getAttribute("data-folder-prop");
const trimmed = folderProp !== null ? folderProp.replace(/\s+/g, "") : null;
if (folderProp !== null) {
    try {
        const module = await import(`../${trimmed}/FooterComponent.vue`);
        FooterComponent = module.default;
    } catch (error) {
        console.error('Error importing component Footer:', error);
    }
} 

export default FooterComponent;
