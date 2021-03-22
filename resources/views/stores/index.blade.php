@extends('layouts.main')
@section('content')
  @include('includes.navigation')
  @include('stores.body')
@endsection
@section('scripts')
@include('stores.js.index')
@endsection