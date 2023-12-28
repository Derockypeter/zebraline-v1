let NavbarComponent;

const folderProp = document
    .getElementById("app")
    .getAttribute("data-folder-prop");
const trimmed = folderProp !== null ? folderProp.replace(/\s+/g, "") : null;
if (folderProp !== null) {
    try {
        const module = await import(`../${trimmed}/SellingPointComponent.vue`);
        NavbarComponent = module.default;
    } catch (error) {
        console.error('Error importing component Selling Point:', error);
    }

}

export default NavbarComponent;
