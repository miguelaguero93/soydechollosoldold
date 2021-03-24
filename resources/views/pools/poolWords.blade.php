@extends('voyager::master')
@section('page_header')
    <div class="container-fluid">
	    <h1 class="page-title">
	        <i class="voyager-underline"></i> Palabras en las pools <b></b>
	    </h1>
	</div>
@stop

@section('content')
<div id="app">
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">

				<form method="POST" id="form">
					<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
					<input type="hidden" value="0" id="id" name="id">
					<input type="hidden" value="" id="word" name="word">
					<input type="hidden" value="" id="category" name="category">
				</form>


				<table class="table" id="table_id">
					<thead>
					<tr>
						<th scope="col">Palabra</th>
						<th scope="col">Conteo</th>
						<th scope="col">Categoría</th>
					</tr>
					</thead>
					<tbody>


					@foreach($words as $key => $word)

						<tr>
							<td scope="row"> <div class="col-3" >{{$word->word}}</div></td>
							<td>
								{{$word->wordCount}}

							</td>
							<td>{{$word->name}}</td>
						</tr>



					@endforeach


					</tbody>
				</table>

            </div>
        </div>
    </div>
</div>

@stop

@section('javascript')
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>

	<script type="text/javascript">
		var app = new Vue({
			el: '#app',
			data: {
			},
			mixins:[],
			methods:{
				approve(id,key) {


					let word = $("#"+key).val();

					let category = $("#select"+key).val();

					$('#category').val(category);
					$('#word').val(word);
					$('#id').val(id);



					$('#form').attr('action','pools/approve').submit();




				},
				deny(id) {
					let payload = {

						'id':id

					};
					$('#id').val(id);
					$('#form').attr('action','pools/deny').submit();

				},
				enableEdit(id) {

					$('#'+id).removeAttr('readonly').attr('enabled', 'enabled');
				},

				disableEdit(id) {

					$('#'+id).removeAttr('enabled').attr('readonly', 'readonly');
				},
				enableEditSelect(id) {
					console.log("works");
					$('#select'+id).prop("disabled", false);
				},

				disableEditSelect(id) {

					$('#select'+id).prop("disabled", true);
				},
			},
			mounted(){
				$('.js-example-basic-single').select2();
				$('#table_id').DataTable();


			}
		});

		function prueba(test){
			alert(test);
		}



		function remove() {

		}



	</script>
@endsection