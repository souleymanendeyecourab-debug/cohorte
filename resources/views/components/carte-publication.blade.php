@props(['publication'])

<article class="carte">
    <h3><a href="{{ route('publications.show', $publication) }}">{{ $publication->titre }}</a></h3>
    <p>{{ Str::limit($publication->contenu, 200) }}</p>
    <footer>Publié par {{ $publication->auteur->name }}</footer>

    @can('epingler', $publication)
        <form method="POST" action="{{ route('publications.epingler', $publication) }}">
            @csrf
            <button type="submit">
                {{ $publication->epingle_le ? 'Désépingler' : 'Épingler' }}
            </button>
        </form>
    @endcan

    @if ($publication->epingle_le)
        <span class="badge">📌 Épinglée</span>
    @endif
</article>