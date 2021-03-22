@extends('layouts.main')
@section('content')
  @include('includes.navigation')
  @include('stores.search.body')
@endsection
@section('scripts')
@include('stores.js.tags')
@endsection