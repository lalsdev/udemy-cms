@extends('layouts.app')
@include('common.intro')
@section('content')
    coucou {{$name}}, contenu pour cette page
    on a ici
    @foreach($people as $peep)
        <p>This is people {{$peep}}</p>
    @endforeach
@endsection

@section('footer')
    footer pour cette page
@endsection
