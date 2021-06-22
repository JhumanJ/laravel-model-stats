module.exports = {
    important: true,
    purge: {
        content: [
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './resources/**/*.vue'
        ],
        options: {}
    },
    darkMode: 'media', // or 'media' or 'class'
    theme: {
        borderColor: theme => ({
            DEFAULT: theme('colors.gray.300', 'currentColor'),
        }),
        extend: {
            colors: {}
        }
    },
    variants: {
        extend: {
            animation: ['hover']
        }
    },
    plugins: []
}
