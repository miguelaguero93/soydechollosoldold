@extends('layouts.main')
@section('content')
  @include('includes.store_banner')
  @include('includes.navigation')

  <div class="d-md-none p-15 text-right">
    <span>
      Ocultar Agotados
    </span>
    <div class="ml-15 d-inline">
      <label class="switch">
        <input type="checkbox" id="hide_expired" @if(isset($filter[0]['hide_expired'])) checked @endif>
        <span class="switch-mark switch-mark-round"></span>
      </label>
    </div>
  </div>
  @include('cupon.body')
  <div v-show="loading" class="text-center">
    <img src="/images/svgs/loader.svg">
  </div>
  @include('index.paginator')
@endsection
@section('scripts')
@include('common.js.helpers')
@include('cupon.js.index')
<script type="text/javascript">
  var hide_expired = document.getElementById("hide_expired")

  if (hide_expired != null) {
    hide_expired.addEventListener('change',function(value){
        var newvalue = value.target.checked
        document.getElementById('hide_expired_filter_box').checked = newvalue
        document.getElementById('filter_form').submit()
    })
  }
</script>
@endsection