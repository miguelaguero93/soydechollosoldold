<script src="https://cdn.tiny.cloud/1/hrqna2dqpnt9mg7qo0qp0zq287dxl3vkc520zk3idcrvz2e8/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>
tinymce.init({
  selector: '#myTextarea',
  height: 300,
  menubar: false,
  language: 'es',
  plugins: [
    'image autolink lists link image anchor',
    'searchreplace visualblocks',
    'media table paste help paste'
  ],
  mobile: { 
    theme: 'mobile' 
  },
  toolbar: 'link image media | undo redo |  formatselect | bold italic forecolor backcolor blockquote| bullist numlist removeformat | alignleft aligncenter alignright alignjustify | outdent indent | help',
  content_css: [
    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
    '//www.tiny.cloud/css/codepen.min.css'
  ],
  paste_data_images: true,
  image_title: true,
  /* enable automatic uploads of images represented by blob or data URIs*/
  automatic_uploads: true,
  format: {
      removeformat: [
        {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
        {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
        {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
      ]
  },
  content_style: `

  blockquote {
    border: 0;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: .1em;
    margin: 1.5em auto;
    padding: 0 2rem;
    position: relative;
    text-align: left;
  }

  blockquote::before {
    color: black;
    content: '“';
    font-family: 'georgia';
    font-size: 30px;
    left: 0%;
    pointer-events: none;
    position: absolute;
    top: -.75em;
  }

  blockquote::after {
    bottom: -1.2em;
    color: black;
    content: '”';
    font-family: 'georgia';
    font-size: 30px;
    pointer-events: none;
    position: absolute;
    right: 0%;
  }
    `,
  /*
    URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
    images_upload_url: 'postAcceptor.php',
    here we add custom filepicker only to Image dialog
  */
  file_picker_types: 'image',
  /* and here's our custom image picker*/
  file_picker_callback: function (cb, value, meta) {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');

    /*
      Note: In modern browsers input[type="file"] is functional without
      even adding it to the DOM, but that might not be the case in some older
      or quirky browsers like IE, so you might want to add it to the DOM
      just in case, and visually hide it. And do not forget do remove it
      once you do not need it anymore.
    */

    input.onchange = function () {
      var file = this.files[0];

      var reader = new FileReader();
      reader.onload = function () {
        /*
          Note: Now we need to register the blob in TinyMCEs image blob
          registry. In the next release this part hopefully won't be
          necessary, as we are looking to handle it internally.
        */
        var id = 'blobid' + (new Date()).getTime();
        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
        var base64 = reader.result.split(',')[1];
        var blobInfo = blobCache.create(id, file, base64);
        blobCache.add(blobInfo);

        /* call the callback and populate the Title field with the file name */
        cb(blobInfo.blobUri(), { title: file.name });
      };
      reader.readAsDataURL(file);
    };

    input.click();
  }
});
</script>