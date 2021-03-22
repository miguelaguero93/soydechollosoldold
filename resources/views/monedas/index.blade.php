@extends('layouts.main')
@section('content')
  	@include('includes.navigation')
  	@include('monedas.includes.body')
@endsection
@section('scripts')
  	@include('monedas.js.index')
@endsection