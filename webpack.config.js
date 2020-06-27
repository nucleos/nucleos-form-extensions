const Encore = require('@symfony/webpack-encore');

Encore
  .setOutputPath('src/Bridge/Symfony/Resources/public')
  .setPublicPath('/bundles/nucleosform')
  .setManifestKeyPrefix('')
  .cleanupOutputBeforeBuild()
  .disableSingleRuntimeChunk()
  .enableSourceMaps(!Encore.isProduction())
  .enableEslintLoader()
  .addEntry('widget', './assets/widget.js')
;

module.exports = Encore.getWebpackConfig();
