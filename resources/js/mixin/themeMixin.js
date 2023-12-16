export default {
    data() {
        return {
            mode: 0,
        }
    },
    methods: {
        checkOSSelectTheme() {
            // Check for dark mode preference at the OS level
            const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");

            // Get the user's theme preference from local storage, if it's available
            const currentTheme = localStorage.getItem("theme");
            // If the user's preference in localStorage is dark...
            if (currentTheme == "dark") {
                // ...let's toggle the .dark-theme class on the body
                document.body.classList.toggle("dark-theme");
                this.mode = 1;
                // Otherwise, if the user's preference in localStorage is light...
            } else if (currentTheme == "light" || currentTheme === null) {
                // ...let's toggle the .light-theme class on the body
                document.body.classList.toggle("light-theme");
                this.mode = 0;
            }
        },
        switchMode() {
            if (this.mode === 0) {
                document.querySelector('body').classList.toggle('dark-theme');
                document.querySelector('body').classList.remove('light-theme');
                this.mode = 1;
            } else if (this.mode === 1) {
                document.querySelector('body').classList.remove('dark-theme');
                document.querySelector('body').classList.toggle('light-theme');
                this.mode = 0;
            }
            localStorage.setItem("theme", this.mode === 0 ? 'light' : 'dark');
        },
    },
    mounted() {
        this.checkOSSelectTheme()
    }
  }
  