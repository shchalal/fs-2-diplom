<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выбор мест — {{ $session->movie->title }}</title>

    <link rel="stylesheet" href="{{ asset('assets/client/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/css/styles.css') }}">
</head>

<body>

<header class="page-header">
    <a href="{{ route('client.home') }}">
        <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    </a>
</header>

<main>
    <section class="buying">

        <div class="buying__info">
            <div class="buying__info-description">
                <h2 class="buying__info-title">{{ $session->movie->title }}</h2>

                <p class="buying__info-hall">
                    {{ $session->hall->name }}
                </p>

                <p class="buying__info-start">
                    Начало сеанса:
                    {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                </p>
            </div>
        </div>

        <div class="buying-scheme">
            <div class="buying-scheme__wrapper">

                @php
                    $rows = $session->hall->rows;
                    $cols = $session->hall->seats_per_row;
                    $seatMap = $seats->groupBy('row_number');
                @endphp

                @for ($r = 1; $r <= $rows; $r++)
                    <div class="buying-scheme__row">

                        @for ($c = 1; $c <= $cols; $c++)
                            @php
                                $seat = $seatMap[$r]->firstWhere('seat_number', $c) ?? null;

                                $classes = 'buying-scheme__chair ';

                                if (!$seat) {
                                    $classes .= 'buying-scheme__chair_disabled';
                                } elseif ($seat->seat_type === 'vip') {
                                    $classes .= 'buying-scheme__chair_vip';
                                } else {
                                    $classes .= 'buying-scheme__chair_standart';
                                }

                                if ($seat && in_array($seat->id, $takenSeats)) {
                                    $classes .= ' buying-scheme__chair_taken';
                                }
                            @endphp

                            <span class="{{ $classes }}"
                                data-seat="{{ $seat->id ?? '' }}">
                            </span>
                        @endfor

                    </div>
                @endfor

            </div>

            {{-- ЛЕГЕНДА ДОЛЖНА БЫТЬ ТУТ — внутри buying-scheme --}}
            <div class="buying-scheme__legend">
                <div class="col">
                    <p class="buying-scheme__legend-price">
                        <span class="buying-scheme__chair buying-scheme__chair_standart"></span>
                        Свободно (<span class="buying-scheme__legend-value">
                            {{ $prices->regular_price ?? '—' }}
                        </span> руб)
                    </p>

                    <p class="buying-scheme__legend-price">
                        <span class="buying-scheme__chair buying-scheme__chair_vip"></span>
                        Свободно VIP (<span class="buying-scheme__legend-value">
                            {{ $prices->vip_price ?? '—' }}
                        </span> руб)
                    </p>
                </div>

                <div class="col">
                    <p class="buying-scheme__legend-price">
                        <span class="buying-scheme__chair buying-scheme__chair_taken"></span>
                        Занято
                    </p>

                    <p class="buying-scheme__legend-price">
                        <span class="buying-scheme__chair buying-scheme__chair_selected"></span>
                        Выбрано
                    </p>
                </div>
            </div>

        </div>

        {{-- ===================== --}}
        {{--    ФОРМА ПОКУПКИ      --}}
        {{-- ===================== --}}
        <form id="paymentForm" action="{{ route('client.payment') }}" method="GET">
           <input type="hidden" name="date" value="{{ $date }}">

            <input type="hidden" name="session_id" value="{{ $session->id }}">
            <input type="hidden" name="seats" id="seatsInput">

            <button type="submit" class="acceptin-button" id="buyButton" disabled>
                Забронировать
            </button>
        </form>

    </section>
</main>


<script>
document.addEventListener("DOMContentLoaded", () => {

    const selectedSeats = new Set();
    const takenSeats = @json($takenSeats);

    document.querySelectorAll(".buying-scheme__chair").forEach(chair => {
        chair.addEventListener("click", () => {

            const seatId = chair.dataset.seat;
            if (!seatId) return;

            if (chair.classList.contains("buying-scheme__chair_taken")) return;

            if (selectedSeats.has(seatId)) {
                selectedSeats.delete(seatId);
                chair.classList.remove("buying-scheme__chair_selected");
            } else {
                selectedSeats.add(seatId);
                chair.classList.add("buying-scheme__chair_selected");
            }

            document.getElementById("buyButton").disabled = selectedSeats.size === 0;
        });
    });

    document.getElementById("paymentForm").addEventListener("submit", (e) => {
        if (selectedSeats.size === 0) {
            e.preventDefault();
            return;
        }

        document.getElementById("seatsInput").value = JSON.stringify([...selectedSeats]);
    });

});
</script>

</body>
</html>
