<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titre', 'Cohorte')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="barre">
        <a href="{{ Route::has('feed.index') ? route('feed.index') : url('/') }}" class="logo">Cohorte</a>

        @auth
            <nav>
                <a href="{{ Route::has('entraide.index') ? route('entraide.index') : url('/') }}">Entraide</a>
                <a href="{{ Route::has('profil.show') ? route('profil.show') : url('/') }}">{{ auth()->user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Déconnexion</button>
                </form>
            </nav>
        @endauth
    </header>

    <main class="conteneur">
        <x-alerte />
        @yield('contenu')
    </main>
</body>
</html>