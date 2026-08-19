@extends('layouts.app')

@section('titre', 'Mot de passe oublié')

@section('contenu')
    <h1>Mot de passe oublié</h1>

    <p>Indiquez votre adresse e-mail, un lien de réinitialisation vous sera envoyé.</p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email">Adresse e-mail</label>
        <input id="email" type="email" name="email"
               value="{{ old('email') }}" required autofocus>

        <button type="submit">Envoyer le lien de réinitialisation</button>
    </form>

    <p>
        <a href="{{ route('login') }}">Retour à la connexion</a>
    </p>
@endsection