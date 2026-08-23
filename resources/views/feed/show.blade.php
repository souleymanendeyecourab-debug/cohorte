@extends('layouts.app')

@section('titre', $publication->titre ?? 'Publication')

@section('contenu')
    <h1>{{ $publication->titre }}</h1>
    <p>Publié par {{ $publication->auteur->name }}</p>
    <div>{{ $publication->contenu }}</div>

    <a href="{{ route('publications.index') }}">Retour au fil</a>
@endsection