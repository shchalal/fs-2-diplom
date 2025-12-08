<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ИдёмВКино — Электронный билет</title>

  <link rel="stylesheet" href="{{ asset('assets/client/css/normalize.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/client/css/styles.css') }}">
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
</head>

<body>
  <header class="page-header">
    <a href="{{ route('client.home') }}">
      <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    </a>
  </header>
  
  <main>
    <section class="ticket">
      
      <header class="tichet__check">
        <h2 class="ticket__check-title">Электронный билет</h2>
      </header>
      
      <div class="ticket__info-wrapper">

        {{-- Название фильма --}}
        <p class="ticket__info">
            На фильм:
            <span class="ticket__details ticket__title">
                {{ $session->movie->title }}
            </span>
        </p>

        {{-- Список мест --}}
       <p class="ticket__info">
            Места:
            <span class="ticket__details ticket__chairs">
                @foreach ($tickets as $ticket)
                    Ряд {{ $ticket->seat->row_number }}, место {{ $ticket->seat->seat_number }}@if(!$loop->last), @endif
                @endforeach
            </span>
        </p>


        {{-- Зал --}}
        <p class="ticket__info">
            В зале:
            <span class="ticket__details ticket__hall">
                {{ $session->hall->name }}
            </span>
        </p>

        {{-- Начало --}}
        <p class="ticket__info">
            Начало сеанса:
            <span class="ticket__details ticket__start">
                {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
            </span>
        </p>

        {{-- QR-коды для каждого билета --}}
       @foreach ($tickets as $ticket)
            <img class="ticket__info-qr"
                src="{{ asset('storage/' . $ticket->qr_path) }}"
                alt="QR код {{ $ticket->booking_code }}">
        @endforeach



        <p class="ticket__hint">
            Покажите QR-код нашему контроллеру для подтверждения бронирования.
        </p>

        <p class="ticket__hint">
            Приятного просмотра!
        </p>
      </div>
    </section>     
  </main>
  
</body>
</html>
