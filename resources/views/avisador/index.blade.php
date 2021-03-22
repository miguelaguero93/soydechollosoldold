<link rel="preload" href="/css/views/avisador.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
@extends('layouts.main')
@section('content')
	@include('includes.banner')
  	@include('includes.navigation')
  	@include('avisador.body')
@endsection
@section('scripts')
@include('common.js.helpers')
@include('common.js.favorite_mixin')
@include('avisador.js.index')
@endsection