@extends('layouts.main')
@section('content')
  @include('includes.navigation')
  @include('categories.body')
@endsection
@section('scripts')
@include('categories.js.index')
@endsection