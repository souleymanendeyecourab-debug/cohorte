@extends('layouts.app')

@section('titre', 'Accès refusé')

@section('contenu')
    <h1>Accès refusé</h1>
    <p>Vous n'avez pas le droit d'accéder à cette page.</p>
    <p><a href="{{ route('publications.index') }}">Retour au fil</a></p>
@endsection