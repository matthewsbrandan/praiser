<script>
  function handleMirrorImg(img, src){
    img.attr('src', src);
  }
  function handleMirrorFileImg(event, mirrorIn){
    let files = event.target.files;
    let src = '';
    if(files){
      for(let index = 0; index < files.length; index++){
        var file = new FileReader();
        file.onload = function(e) {
          src = e.target.result;
          console.log(src);
          handleMirrorImg(mirrorIn, src)
        };       
        file.readAsDataURL(files[index]);
      }
    }
  }
</script>