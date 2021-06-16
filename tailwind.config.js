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
    extend: {
      colors: {
      }
    }
  },
  variants: {
    extend: {
      animation: ['hover']
    }
  },
  plugins: []
}
