@extends('layouts.app')

@section('titre', 'Réinitialiser le mot de passe')

@section('contenu')
    <h1>Réinitialiser votre mot de passe</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <label for="email">Adresse e-mail</label>
        <input id="email" type="email" name="email"
               value="{{ old('email', $request->email) }}" required autofocus>

        <label for="password">Nouveau mot de passe</label>
        <input id="password" type="password" name="password" required>

        <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>

        <button type="submit">Réinitialiser le mot de passe</button>
    </form>
@endsection