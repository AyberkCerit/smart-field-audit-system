tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                primary: "#1b5fc5",
                accent: "#1fc9dd",
                "background-light": "#f4f4f5",
                "background-dark": "#171717",
                "card-dark": "#262626",
                text: '#dedede',
                background: '#171717',
                secondary: '#666666',
            },
            fontFamily: {
                display: ["Orbitron", "sans-serif"],
                sans: ["Inter", "sans-serif"],
            },
            borderRadius: {
                DEFAULT: "0.5rem",
                'xl': '1rem',
                '2xl': '2rem',
                '3xl': '3.5rem',
            },
        },
    },
};
