@extends('layouts.main')
@section('content')
  @include('includes.navigation')
  @include('brands.search.body')
@endsection
@section('scripts')
@include('brands.js.tags')
@endsection