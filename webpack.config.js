const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet
// by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

module.exports = Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './resources/assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .enableVueLoader(() => {}, {
        runtimeCompilerBuild: true,
    })
    .enablePostCssLoader()
    .enableIntegrityHashes(Encore.isProduction())
    .getWebpackConfig()
;
