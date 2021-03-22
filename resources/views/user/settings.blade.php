@extends('layouts.main')
@section('content')
    @include('includes.navigation')
    @include('user.settings.body')
@endsection
@section('scripts')
@include('user.js.settings')
@endsection