module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        // colors: {
        //     teal: '#323232',
        // },

        fontFamily: {
            sans: ['Roboto', 'sans-serif'],
        },

        extend: {
            spacing: {
                '128': '32rem',
                '144': '36rem',
            },
            borderRadius: {
                '4xl': '2rem',
            }
        }
    },

    plugins: [
        require('flowbite/plugin')
    ],

    darkMode: "class"
}
