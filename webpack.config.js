var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')

    // allow legacy applications to use $/jQuery as a global variable
    //.autoProvidejQuery()

    .cleanupOutputBeforeBuild()

    .enableSourceMaps()
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/main', './assets/js/main.js')
    .addStyleEntry('css/main', './assets/sass/main.scss')

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()
    // enable vue
    .enableVueLoader()
;

module.exports = Encore.getWebpackConfig();