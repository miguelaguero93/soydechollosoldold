@extends('layouts.main')
@section('content')
  @include('includes.navigation')
  @include('stores.tags.body')
@endsection
@section('scripts')
@include('stores.js.tags')
@endsection