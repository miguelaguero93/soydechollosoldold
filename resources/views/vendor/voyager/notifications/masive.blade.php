@extends('voyager::master')

@section('page_title', 'Notificacion Masiva')

@section('page_header')
    <div class="container-fluid">
	    <h1 class="page-title">
	        <i class="voyager-bell"></i> Notificacion Masiva 
	    </h1>
	</div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <form method="POST">
	                    <div class="panel-body">
	                    	 <div class="textarea mb-10" id="editor-container" style="height: 200px"></div>
	                    	 <div class="form-group" style="padding-top: 30px">
								<label class="control-label" for="name">Link de la notificación - opcional</label>
	                            <input type="text" class="form-control" name="link" placeholder="Link de la notificación">
	                        </div>
	                    </div>
	                    <div class="panel-footer">
                        	@csrf
                        	<input type="hidden" name="notification" id="notification">
                        	<button type="submit" class="btn btn-primary save"><i class="voyager-paper-plane"></i> Enviar</button>
	                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/quill/quill.js"></script>
	<script src="/js/quill/quill.min.js"></script>
	<link href="/js/quill/quill.snow.css" rel="stylesheet">
	<script type="text/javascript">
		var quill = new Quill('#editor-container', {
		  modules: {
		    toolbar: [
		      ['bold', 'italic', 'underline','strike'],
		      ['link']
		    ]
		  },
		  placeholder: 'Notificacion.',
		  theme: 'snow'
		});
		quill.on('text-change', function(range, oldRange, source) {
		  document.getElementById('notification').value = quill.root.innerHTML  
		});
	</script>
	<style type="text/css">
	strong {
    	font-weight: 600 !important;
	}
	</style>
@stop