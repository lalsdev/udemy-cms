@extends('layouts.app')

@section('content')
    <div>Item Page</div>
    <div>Type of clothe : {{$type}}</div>
    <div>Clothe color : {{$color}}</div>
    <div>Clothe size: {{$size}}</div>
@endsection

@section('footer')
    footer item page
@endsection
