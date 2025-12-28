<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Оплата — ИдёмВКино</title>

    <link rel="stylesheet" href="{{ asset('assets/client/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
</head>

<body>

<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
</header>

<main>
    <section class="ticket">

        <header class="tichet__check">
            <h2 class="ticket__check-title">Вы выбрали билеты:</h2>
        </header>

        <div class="ticket__info-wrapper">

            {{-- На фильм --}}
            <p class="ticket__info">
                На фильм:
                <span class="ticket__details ticket__title">
                    {{ $session->movie->title }}
                </span>
            </p>

            {{-- Места --}}
            <p class="ticket__info">
                Места:
                <span class="ticket__details ticket__chairs">
                    @foreach ($seats as $seat)
                        ряд {{ $seat->row_number }}, место {{ $seat->seat_number }}@if(!$loop->last), @endif
                    @endforeach
                </span>
            </p>

            {{-- В зале --}}
            <p class="ticket__info">
                В зале:
                <span class="ticket__details ticket__hall">
                    {{ $session->hall->name }}
                </span>
            </p>

            {{-- Начало сеанса --}}
            <p class="ticket__info">
                Начало сеанса:
                <span class="ticket__details ticket__start">
                    {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                </span>
            </p>

            {{-- Стоимость --}}
           @php
                $regularPrice = optional($session->hall->price)->regular_price ?? 0;
                $vipPrice     = optional($session->hall->price)->vip_price ?? 0;

                $total = 0;
                foreach ($seats as $seat) {
                    $total += $seat->seat_type === 'vip' ? $vipPrice : $regularPrice;
                }
            @endphp

            <p class="ticket__info">
                Стоимость:
                <span class="ticket__details ticket__cost">
                    {{ $total }}
                </span>
                рублей
            </p>

            {{-- Форма "Получить код бронирования" --}}
            <form class="ticket__buy" method="POST" action="{{ route('client.payment.store') }}">
                @csrf

                <input type="hidden" name="session_id" value="{{ $session->id }}">
                <input type="hidden" name="seats" value="{{ json_encode($seatIds) }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <button class="acceptin-button" type="submit">
                    Получить код бронирования
                </button>
            </form>

            <p class="ticket__hint">
                После оплаты билет будет доступен в этом окне, а также придёт вам на почту.
                Покажите QR-код нашему контроллёру у входа в зал.
            </p>
            <p class="ticket__hint">Приятного просмотра!</p>

        </div>
    </section>
</main>

</body>
</html>
