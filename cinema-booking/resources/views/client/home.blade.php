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

    @foreach ($dates as $d)

        @php
            $weekDay = \Carbon\Carbon::parse($d)->translatedFormat('D'); // Пн, Вт...
            $dayNum  = \Carbon\Carbon::parse($d)->format('j');           // 1, 2, 3...
            
            $classes = "page-nav__day";

            if ($d === now()->format('Y-m-d')) {
                $classes .= " page-nav__day_today";
            }

            if ($d === $date) {
                $classes .= " page-nav__day_chosen";
            }

            // выходные
            if (in_array(\Carbon\Carbon::parse($d)->dayOfWeekIso, [6, 7])) {
                $classes .= " page-nav__day_weekend";
            }
        @endphp

        <a class="{{ $classes }}" href="{{ route('client.home', ['date' => $d]) }}">
            <span class="page-nav__day-week">{{ $weekDay }}</span>
            <span class="page-nav__day-number">{{ $dayNum }}</span>
        </a>

    @endforeach

    {{-- кнопка «следующие дни» --}}
    <a class="page-nav__day page-nav__day_next" href="{{ route('client.home', ['date' => $dates->last()]) }}"></a>

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
                                    href="{{ route('client.hall', [
                                        'session' => $session->id,
                                        'date' => $date
                                    ]) }}">
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
