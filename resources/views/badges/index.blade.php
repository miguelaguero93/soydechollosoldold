@extends('layouts.main')
@section('content')
    @include('includes.navigation')
    @include('badges.badges_body')
@endsection
@section('scripts')
    @include('badges.js.index')
@endsection