module.exports = {
  lintOnSave: process.env.NODE_ENV !== "production",
      // proxy API requests to Valet during development
      devServer: {
        //proxy: 'http://localhost:8000'//с пушером траблы вызывает;
        proxy: {
            '^/api': {
              target: 'https://srs.marinet.ru/testk/registrations/rightSailM/back',
              ws: true,
              changeOrigin: true,
              secure: false,
            }
          }
    },
  publicPath: process.env.NODE_ENV === 'production' ?
  'testk/js/vue-cryptoPro-pdf-sign/dist/':'',
  assetsDir: "./", //По умолчанию: '' - Каталог (относительно outputDir) для хранения сгенерированных статических ресурсов (js, css, img, fonts).
  outputDir: "dist",
  indexPath: "index.html", //умолч -'index.html'-относительно outputDir
  filenameHashing: false,
  chainWebpack: config => {
    config.module
      .rule("vue")
      .use("vue-loader")
      .loader("vue-loader")
      .tap(options => {
        options["transformAssetUrls"] = {
          img: "src",
          image: "xlink:href",
          "b-img": "src",
          "b-img-lazy": ["src", "blank-src"],
          "b-card": "img-src",
          "b-card-img": "img-src",
          "b-carousel-slide": "img-src",
          "b-embed": "src"
        };
        return options;
      });
  },

};

  //'../../testk/js/vue-cryptoPro-pdf-sign/dist/' : '',
// https://bootstrap-vue.js.org/docs/reference/images/#vue-cli-3-support

/*
  lintOnSave: process.env.NODE_ENV !== 'production',
  runtimeCompiler: true,
  filenameHashing: false,
  configureWebpack: {
    devtool: 'source-map'
  }*/
