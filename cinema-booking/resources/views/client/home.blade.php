<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ИдёмВКино</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/client/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/styles.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
</head>

<body>

<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
</header>

{{-- ====================== --}}
{{-- Навигация по дням (пока статичная) --}}
{{-- ====================== --}}
<nav class="page-nav">
    <a class="page-nav__day page-nav__day_today" href="#">
        <span class="page-nav__day-week">Пн</span><span class="page-nav__day-number">31</span>
    </a>
    <a class="page-nav__day" href="#">
        <span class="page-nav__day-week">Вт</span><span class="page-nav__day-number">1</span>
    </a>
    <a class="page-nav__day page-nav__day_chosen" href="#">
        <span class="page-nav__day-week">Ср</span><span class="page-nav__day-number">2</span>
    </a>
    <a class="page-nav__day" href="#">
        <span class="page-nav__day-week">Чт</span><span class="page-nav__day-number">3</span>
    </a>
    <a class="page-nav__day" href="#">
        <span class="page-nav__day-week">Пт</span><span class="page-nav__day-number">4</span>
    </a>
    <a class="page-nav__day page-nav__day_weekend" href="#">
        <span class="page-nav__day-week">Сб</span><span class="page-nav__day-number">5</span>
    </a>
    <a class="page-nav__day page-nav__day_next" href="#"></a>
</nav>

<main>

    {{-- ====================== --}}
    {{-- ДИНАМИЧЕСКИЙ ВЫВОД ФИЛЬМОВ --}}
    {{-- ====================== --}}
    @foreach ($movies as $movie)

        <section class="movie">

            {{-- ==== Блок: постер + описание ==== --}}
            <div class="movie__info">

                <div class="movie__poster">
                    <img
                        class="movie__poster-image"
                        alt="Постер"
                        src="{{ $movie->poster_url ? asset('storage/' . $movie->poster_url) : asset('assets/client/i/default.png') }}">
                </div>

                <div class="movie__description">
                    <h2 class="movie__title">{{ $movie->title }}</h2>

                    <p class="movie__synopsis">
                        {{ $movie->description ?? 'Описание отсутствует' }}
                    </p>

                    <p class="movie__data">
                        <span class="movie__data-duration">{{ $movie->duration }} минут</span>
                        <span class="movie__data-origin">{{ $movie->country ?? 'США' }}</span>
                    </p>
                </div>
            </div>

            {{-- ==== Сеансы по залам ==== --}}
            @foreach ($halls as $hall)

                @php
                    $sessionsForHall = $movie->sessions->where('hall_id', $hall->id);
                @endphp

                @if ($sessionsForHall->count() > 0)
                    <div class="movie-seances__hall">
                        <h3 class="movie-seances__hall-title">{{ $hall->name }}</h3>

                        <ul class="movie-seances__list">

                            @foreach ($sessionsForHall as $session)

                                @php
                                    $time = \Carbon\Carbon::parse($session->start_time)->format('H:i');
                                @endphp

                                <li class="movie-seances__time-block">
                                    <a class="movie-seances__time"
                                       href="{{ route('client.hall', ['session' => $session->id]) }}">
                                        {{ $time }}
                                    </a>
                                </li>

                            @endforeach

                        </ul>
                    </div>
                @endif

            @endforeach

        </section>

    @endforeach

</main>

</body>
</html>
