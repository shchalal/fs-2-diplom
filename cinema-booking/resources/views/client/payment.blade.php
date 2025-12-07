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

            <p class="ticket__info">
                <span class="ticket__details ticket__title">
                    {{ $session->movie->title }}
                </span>
                <span class="ticket__details ticket__chairs">
                    Места:
                    @foreach ($seats as $seat)
                        ряд {{ $seat->row_number }}, место {{ $seat->seat_number }};
                    @endforeach
                </span>
                <span class="ticket__details ticket__hall">
                    Зал: {{ $session->hall->name }}
                </span>
                <span class="ticket__details ticket__start">
                    Начало сеанса: {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                </span>
            </p>

            @php
                $regularPrice = $session->price_regular ?? $session->hall->price->regular_price ?? 0;
                $vipPrice     = $session->price_vip     ?? $session->hall->price->vip_price     ?? 0;

                $total = 0;
                foreach ($seats as $seat) {
                    $total += $seat->seat_type === 'vip' ? $vipPrice : $regularPrice;
                }
            @endphp

            <p class="ticket__info">
                <span class="ticket__details ticket__cost">
                    Итого: {{ $total }} ₽
                </span>
            </p>

            {{-- ФОРМА ОПЛАТЫ --}}
            <form class="ticket__buy" method="POST" action="{{ route('client.payment.store') }}">
                @csrf

                <input type="hidden" name="session_id" value="{{ $session->id }}">
                <input type="hidden" name="seats" value="{{ json_encode($seatIds) }}">

                <button class="acceptin-button" type="submit">
                    Оплатить
                </button>
            </form>
        </div>
    </section>

</main>

</body>
</html>
