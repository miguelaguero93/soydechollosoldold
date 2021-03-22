@extends('layouts.main')
@section('content')
@include('includes.breadcrumbs')
<div class="container" style="min-height: 70vh">
  <div class="row">
  	<div class="col-12 font-bold color-blue2 mt-40 mb-20 text-center font-28">
  		¿Qué es lo que quieres compartir?
  	</div>
  	<div class="col-12 col-md-4 text-center cursor-pointer" onclick="goTo('/nuevo/chollo')">
	  		<div class="bg-white p-25 rounded mb-20 mb-md-100 hoverable">
	  			<span class="font-bold color-blue2 font-22">Chollo</span><br> <p style="color: initial">Una oferta única de un único producto</p> 
	  		</div>
  	</div>
  	<div class="col-12 col-md-4 text-center cursor-pointer"  onclick="goTo('/nuevo/cupon')">
	  		<div class="bg-white p-25 rounded mb-20 mb-md-100 hoverable">
	  			<span class="font-bold color-blue2 font-22">Cupón </span> <br> <p style="color: initial">Un cupón para una tienda</p>	
	  		</div>
  	</div>
  	<div class="col-12 col-md-4 text-center  cursor-pointer"  onclick="goTo('/nuevo/evento')">
	  		<div class="bg-white p-25 rounded mb-20 mb-md-100 hoverable">
	  			<span class="font-bold color-blue2 font-22">Evento o Sorteo</span> <br> <p style="color: initial">Un evento o sorteo</p>
	  		</div>
  	</div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
  var logged = {{$logged}}
  function goTo(url){
    if (logged == 0){
      triggerLoginModal()
    }else{
      showBlanket()
      window.location.href = url
    }
  }
</script>
@endsection
