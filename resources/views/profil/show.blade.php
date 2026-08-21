@extends('layouts.app')

@section('titre', 'Mon profil')

@section('contenu')
    <h1>Mon profil</h1>

    <p><strong>Nom :</strong> {{ $utilisateur->name }}</p>
    <p><strong>E-mail :</strong> {{ $utilisateur->email }}</p>
    <p><strong>Rôle :</strong> {{ $utilisateur->role }}</p>

    @if ($utilisateur->promotion)
        <p><strong>Promotion :</strong> {{ $utilisateur->promotion->nom }}</p>
    @endif

    <p><strong>Points de contribution :</strong> {{ $utilisateur->points }}</p>
@endsection