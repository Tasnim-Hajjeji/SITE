const { defineConfig } = require("@vue/cli-service");
module.exports = defineConfig({
  transpileDependencies: [],
  chainWebpack: config => {
    config.module
      .rule('pdf')
      .test(/\.pdf$/)
      .use('file-loader')
      .loader('file-loader')
      .options({
        name: '[path][name].[ext]', // Preserve original path and name
      });
  },
});