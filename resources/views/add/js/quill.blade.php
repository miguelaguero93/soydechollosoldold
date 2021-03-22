<script src="/js/quill/quill.js"></script>
{{-- <script src="/js/quill/quill.min.js"></script> --}}
<script src="/quill-emoji/dist/quill-emoji.js"></script>
<link href="/js/quill/quill.snow.css" rel="stylesheet">
<link href="/quill-emoji/dist/quill-emoji.css" rel="stylesheet">
<script type="text/javascript">
	var toolbarOptions = {
    container: [
      ['bold', 'italic', 'underline', 'strike'],
      ['blockquote', 'code-block'],
      [{ 'header': 1 }, { 'header': 2 }],
      [{ 'list': 'ordered' }, { 'list': 'bullet' }],
      [{ 'script': 'sub' }, { 'script': 'super' }],
      [{ 'indent': '-1' }, { 'indent': '+1' }],
      [{ 'direction': 'rtl' }],
      [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
      [{ 'color': [] }, { 'background': [] }],
      [{ 'font': [] }],
      [{ 'align': [] }],
      ['clean'],
      ['emoji'],
      ['link', 'image']
    ],
    handlers: {
      'emoji': function () {}
    }
  }
	var quill = new Quill('#editor-container', {
	  modules: {
	    "toolbar": toolbarOptions,
	    "emoji-toolbar": true,
        "emoji-shortname": true,
	  },
	  placeholder: 'Te recomendamos describir el chollo con tus propias palabras, no copiar y pegar, una buena explicación de porque es un buen chollo y que sea fácil de leer. De esta forma, tendrá más opciones de ser el chollo más votado del día y podrás ganar chollocoins para conseguir fantásticos premios.',
	  theme: 'snow'
	});
	quill.on('selection-change', function(range, oldRange, source) {
	  if (!range) {
	    app.description = quill.root.innerHTML
	  } 
	});

  var Link = Quill.import('formats/link');

  class MyLink extends Link {
    static create(value) {
      let node = super.create(value);
      value = this.sanitize(value);
      if(!value.startsWith('http')) {
        value = `http://` + value;
      }
      console.log('the fkin value is --->'+value)
      node.setAttribute('href', value);
      return node;
    }
  }

  Quill.register(MyLink);


</script>