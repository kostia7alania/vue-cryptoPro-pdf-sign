<template>
        <transition name="slide-fade">
          <div class="text-center">
            <button
              @click="clear_base64Binary"
              class="btn-3d-1"
              width="auto"
              target="_blank"
            >Подписать еще один документ</button>
            <br>
            <br>
            <iframe :src="base64Binary" width="790px" height="1290px" frameborder="0"></iframe>
            <br>
            <canvas ref="the-canvas"></canvas>
            <br>
           <!-- <button @click="openBase64" class="btn-3d-1" width="auto" target="_blank" >Открыть подписанный документ</button>-->
          </div>
        </transition>
</template>

<script>
export default {
  name: "Preview-signed",
  mounted() {
    window.PDFJS = require("./pdf.min.js");
    window.pdfjsWorker = require("./pdf.worker.min.js");
    PDFJS.GlobalWorkerOptions.workerSrc = pdfjsWorker;

    var BASE64_MARKER = ";base64,";
    function convertDataURIToBinary(dataURI) {
      var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
      var base64 = dataURI.substring(base64Index);
      var raw = window.atob(base64);
      var rawLength = raw.length;
      var array = new Uint8Array(new ArrayBuffer(rawLength));

      for (var i = 0; i < rawLength; i++) {
        array[i] = raw.charCodeAt(i);
      }
      return array;
    }

    //var pdfAsDataUri = "data:application/pdf;base64,JVBERi0xLjUK..."; // shortened
    const pdfAsDataUri = this.base64Binary;
    var pdfAsArray = convertDataURIToBinary(pdfAsDataUri);

    window.t = this;
    const that = this;
    //window.w = PDFJS.getDocument(pdfAsArray);
    // Using DocumentInitParameters object to load binary data.
    var loadingTask = PDFJS.getDocument({ data: pdfAsArray });
    loadingTask.promise.then(
      pdf => {
        console.log("PDF loaded");

        // Fetch the first page
        var pageNumber = 1;
        pdf.getPage(pageNumber).then(function(page) {
          console.log("Page loaded");

          var scale = 1.5;
          var viewport = page.getViewport({ scale: scale });

          // Prepare canvas using PDF page dimensions
          var canvas = that.$refs["the-canvas"];
          var context = canvas.getContext("2d");
          canvas.height = viewport.height;
          canvas.width = viewport.width;

          // Render PDF page into canvas context
          var renderContext = {
            canvasContext: context,
            viewport: viewport
          };
          var renderTask = page.render(renderContext);
          renderTask.promise.then(() => console.warn("Page rendered"));
        });
      },
      reason => {
        // PDF loading error
        console.error(reason);
      }
    );
  },
  methods: {
    openBase64() {
      var w = window.open("about:blank");
      setTimeout(() => {
        //FireFox seems to require a setTimeout for this to work.
        w.document.body.appendChild(
          w.document.createElement("iframe")
        ).src = this.base64Binary;
      }, 0);
    },
    clear_base64Binary() {
      this.$store.commit("SET_BASE64_BINARY", null);
    }
  },
  computed: {
    base64Binary() {
      return (
        "data:application/pdf;base64," +
        btoa(unescape(encodeURIComponent(this.$store.state.BASE64_BINARY)))
      );
    }
  }
};
</script>

<style scoped lang="scss">
.pechat {
  p,
  h3,
  button {
    text-align: center;
  }
}
</style>
