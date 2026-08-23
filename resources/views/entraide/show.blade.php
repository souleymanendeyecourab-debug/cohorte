@extends('layouts.app')

@section('titre', $question->titre)

@section('contenu')
    <h1>{{ $question->titre }}</h1>
    <p>Posée par {{ $question->auteur->name }}</p>
    <div>{{ $question->contenu }}</div>

    <hr>

    <h2>Réponses</h2>

    @forelse ($question->reponses as $reponse)
        <article class="carte @if($question->reponse_retenue_id === $reponse->id) carte--retenue @endif">
            <p>{{ $reponse->contenu }}</p>
            <footer>
                Par {{ $reponse->auteur->name }}
                @if ($question->reponse_retenue_id === $reponse->id)
                    — <strong>Réponse retenue</strong>
                @endif
            </footer>

            @can('designerReponse', $question)
                @if ($question->reponse_retenue_id !== $reponse->id)
                    <form method="POST" action="{{ route('reponse-retenue.store', $question) }}">
                        @csrf
                        <input type="hidden" name="reponse_id" value="{{ $reponse->id }}">
                        <button type="submit">Marquer comme retenue</button>
                    </form>
                @endif
            @endcan
        </article>
    @empty
        <p class="vide">Aucune réponse pour l'instant.</p>
    @endforelse

    <h3>Répondre</h3>
    <form method="POST" action="{{ route('reponses.store', $question) }}">
        @csrf
        <textarea name="contenu" rows="4" required></textarea>
        <button type="submit">Répondre</button>
    </form>

    <a href="{{ route('questions.index') }}">Retour à l'entraide</a>
@endsection