@extends('layouts.app')

@section('titre', $publication->titre ?? 'Publication')

@section('contenu')
    @if ($publication->statut === 'masque' && $publication->user_id === auth()->id())
        <div class="alerte alerte-attention">
            Cette publication a été masquée suite à des signalements et est en attente de révision.
            Elle n'est visible que par vous.
        </div>
    @endif

    <h1>{{ $publication->titre }}</h1>
    <p>Publié par {{ $publication->auteur->name }}</p>
    <div>{{ $publication->contenu }}</div>

    @if ($publication->user_id !== auth()->id())
        <form action="{{ route('publications.signalements.store', $publication) }}" method="POST">
            @csrf
            <label for="motif">Signaler cette publication :</label>
            <select name="motif" id="motif" required>
                <option value="">-- Choisir un motif --</option>
                <option value="spam">Spam</option>
                <option value="hors_sujet">Hors sujet</option>
                <option value="inapproprie">Contenu inapproprié</option>
                <option value="harcelement">Harcèlement</option>
                <option value="autre">Autre</option>
            </select>
            <button type="submit">Signaler</button>
        </form>
    @endif

    @if (session('succes'))
        <p>{{ session('succes') }}</p>
    @endif

    @if (session('erreur'))
        <p>{{ session('erreur') }}</p>
    @endif

    <a href="{{ route('publications.index') }}">Retour au fil</a>
@endsection