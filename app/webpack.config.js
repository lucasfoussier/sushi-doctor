let Encore = require('@symfony/webpack-encore');
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .enableReactPreset()
    //.setManifestKeyPrefix('build/') // only needed for CDN's or sub-directory deploy
    .addEntry('app', './react/src/index.tsx')
    .enableTypeScriptLoader(function(tsConfig) {})
    .splitEntryChunks() // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .enableSingleRuntimeChunk() // will require an extra script tag for runtime.js but, you probably want this, unless you're building a single-page app
    .cleanupOutputBeforeBuild() // https://symfony.com/doc/current/frontend.html#adding-more-features
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .addAliases({
        '@components': `${__dirname}/react/src/components`,
        '@container': `${__dirname}/react/src/container`,
        '@services': `${__dirname}/react/src/services`,
        '@img': `${__dirname}/react/img`,
    })
    // .enableEslintLoader()
    // .enableSassLoader()
    .addLoader({
        test: /\.(js|tsx)$/,
        loader: 'eslint-loader',
        exclude: [/node_modules/],
        enforce: "pre",
        options: {
            configFile: './.eslintrc',
            emitWarning: true
        }
    })
;
module.exports = Encore.getWebpackConfig();
