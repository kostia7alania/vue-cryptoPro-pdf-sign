module.exports = {
  lintOnSave: process.env.NODE_ENV !== 'production',
  baseUrl: process.env.NODE_ENV === 'production' ? './' : '',
  runtimeCompiler:true,
  filenameHashing: false,
  configureWebpack: {
    devtool: 'source-map'
  }
}