// webpack.config.js
var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')

    .setPublicPath('/build')

    .cleanupOutputBeforeBuild()

    .addStyleEntry('front_style', './app/Resources/assets/scss/front/app.scss')
    .addEntry('front_js', './app/Resources/assets/js/front/app.js')

    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
    })

    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

    .enableVersioning()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();