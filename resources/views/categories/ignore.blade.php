@extends('voyager::master')
@section('page_header')
    <div class="container-fluid">
	    <h1 class="page-title">
	        <i class="voyager-underline"></i> Pool palabras ignoradas <b></b> 
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
	                    	 <div class="form-group">
								<label class="control-label" for="name">pool de palabras separadas por comas</label>
	                            <textarea class="form-control" name="words" rows="10">@foreach($words as $word){{$word->word}}, @endforeach</textarea>
	                        </div>
	                    </div>
	                    <div class="panel-footer">
                        	@csrf
                        	<button type="submit" class="btn btn-primary save"><i class="voyager-paper-plane"></i> Guardar</button>
	                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop