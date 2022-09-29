module.exports = {
    content: [
        "./resources/**/*.antlers.html",
        "./resources/**/*.blade.php",
        "./resources/**/*.vue",
        "./content/**/*.md",
    ],
    theme: {
        extend: {
            fontFamily: {
                body: ["Montserrat", "sans-serif"],
            },
            spacing: {
                22: "5.5rem",
                30: "7.5rem",
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        // require('@tailwindcss/aspect-ratio')
    ],
};
