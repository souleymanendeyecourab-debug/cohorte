@extends('layouts.app')

@section('titre', 'Entraide')

@section('contenu')
    <div class="entete-fil">
        <h1>Entraide</h1>
        <a href="{{ route('questions.create') }}" class="bouton">Poser une question</a>
    </div>

    @forelse ($questions as $question)
        <article class="carte">
            <h3><a href="{{ route('questions.show', $question) }}">{{ $question->titre }}</a></h3>
            <p>{{ Str::limit($question->contenu, 200) }}</p>
            <footer>Posée par {{ $question->auteur->name }}</footer>
        </article>
    @empty
        <p class="vide">Aucune question pour l'instant. Soyez la première à en poser une.</p>
    @endforelse

    {{ $questions->links() }}
@endsection