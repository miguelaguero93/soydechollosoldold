@extends('layouts.main')
@section('content')
@include('includes.breadcrumbs')
<div class="container" style="min-height: 70vh">
  <div class="row">
  	<div class="col-12 col-md-8 py-md-40">
      @if(session()->has('success'))
        <div class="alert alert-success">
          {{session('success')}}
        </div>
      @endif
      <h3>Contacta con nosotros</h3>
      <ul style="line-height: 2">
        <li>
          
          ¿Quieres colaborar con nosotros, proponernos una campaña, un descuento, …?
        </li>
        <li>
          
          ¿Quieres aportarnos ideas o sugerencias?
        </li>
        <li>
          ¿Quieres ser redactor/a en Soydechollos?
          
        </li>
        <li>
          ¿Tienes alguna duda sobre algún producto o servicio recomendado en el blog?
          
        </li>
        <li>
          
          ¿Necesitas consejos sobre tipos de envíos o métodos de pagos? 
        </li>
      </ul>
      <p>En cualquier caso, sea cual sea tu duda, <b>estamos aquí para responderte</b> tan rápido como nos sea posible.</p>
      <form class="w-100" id="form" method="POST">
        @csrf
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="form-group">
              <input type="text" maxlength="20" class="form-control" required name="name" placeholder="Nombre">
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="form-group">
              <input type="text" class="form-control" required name="email" placeholder="Correo electrónico">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <input type="text"  maxlength="50" class="form-control" required name="subject" placeholder="Asunto">
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <textarea class="form-control"  maxlength="1000" rows="10" required name="message" placeholder="Mensaje"></textarea>
            </div>
          </div>
          <div class="col-12">
            <input type="checkbox" required name="terms" id="terms"> <label for="terms"><span>Acepto el tratamiento de mis datos personales conforme a la <a href="/politicas">política de privacidad.</a></span> </label>
          </div>
          <div class="col-12 my-30">
            <div class="g-recaptcha" data-sitekey="6LdBI-QZAAAAAD5DtuY4lNjA3W9a-rp1uca1rirj"></div>
            <br>
            {{-- <button class="g-recaptcha btn"data-sitekey="6LeLx7MZAAAAAMA2haEwHCZR5PkOFaEFYR03SryK"data-callback='onSubmit'data-action='submit'>Enviar</button> --}}
            <button id="submit" type="submit" class="btn"><i class="fas fa-paper-plane mr-10"></i> Enviar</button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-12 col-md-4 py-md-40">
      <h3>En las Redes Sociales:</h3>
      <p>Recuerda que también puedes ponerte en contacto con nosotros a través de mensaje privado en <b>Twitter</b> o <b>Facebook</b>.</p>
      <ul class="list-unstyled">
        <li class="my-13">
          <div class="d-flex align-items-center">
            <span class="circle-small">
              <i class="fab fa-facebook-f"></i>
            </span>
            <a class="link-none pl-10" href="{{setting('site.facebook')}}">Facebook</a>
             
          </div>
        </li>
        <li class="my-13">
          <div class="d-flex align-items-center">
            <span class="circle-small">
              <i class="fab fa-instagram"></i>
            </span>
            <a class="link-none pl-10" href="{{setting('site.instagram')}}">Instagram</a>
          </div>
        </li>
        <li class="my-13">
          <div class="d-flex align-items-center">
            <span class="circle-small">
              <i class="fab fa-twitter"></i>
            </span>
            <a class="link-none pl-10" href="{{setting('site.twitter')}}">Twitter</a>
          </div>
        </li>
        <li class="my-13">
          <div class="d-flex align-items-center">
            <span class="circle-small">
              <i class="fas fa-envelope"></i>
            </span>
            <a class="link-none pl-10" href="mailto:{{setting('site.email')}}">Email</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="https://www.google.com/recaptcha/api.js"></script>

<script type="text/javascript">
  $("form").submit(function(event) {
     var recaptcha = $("#g-recaptcha-response").val();
     if (recaptcha === "") {
        event.preventDefault();
        alert("Please selcciona que no eres un robot");
     }
  });
</script>
@endsection
