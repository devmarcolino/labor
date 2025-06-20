const preline = require('preline/plugin') // CommonJS mesmo, não ES module

module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './node_modules/preline/**/*.js',
  ],
  theme: {
    extend: {},
  },
  plugins: [preline],
}
