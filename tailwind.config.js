
const graphColors = ['blue','gray','red','green','yellow','purple','pink', 'orange']
const alertColors = ['green','red','blue','gray']

module.exports = {
    important: true,
    purge: {
        content: [
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './resources/**/*.vue'
        ],
        safelist: [
            /.*(bg|border)-(blue|gray|red|green).*/, // Buttons
            ...alertColors.map((color)=>['bg-'+color+'-100', 'border-'+color+'-500']).flat(), // Alerts
            ...graphColors.map((color) => 'to-'+color+'-100') // Gradients widgets
        ]
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
