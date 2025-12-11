@extends('admin.layout')

@section('title', 'Админ-панель')

@section('content')

<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Администраторская</span>
</header>

<main class="conf-steps">

    {{-- ========================================================= --}}
    {{-- БЛОК 1 — УПРАВЛЕНИЕ ЗАЛАМИ --}}
    {{-- ========================================================= --}}
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Управление залами</h2>
        </header>

        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Доступные залы:</p>

            <ul class="conf-step__list">
                @foreach($halls as $hall)
                    <li>
                        {{ $hall->name }}

                        {{-- Радиокнопки открыт/закрыт --}}
                        <label>
                            <input type="radio"
                                   class="conf-step__radio hall-status-radio"
                                   name="status-{{ $hall->id }}"
                                   value="1"
                                   data-id="{{ $hall->id }}"
                                   {{ $hall->is_active ? 'checked' : '' }}>
                            <span class="conf-step__selector">открыт</span>
                        </label>

                        <label>
                            <input type="radio"
                                   class="conf-step__radio hall-status-radio"
                                   name="status-{{ $hall->id }}"
                                   value="0"
                                   data-id="{{ $hall->id }}"
                                   {{ !$hall->is_active ? 'checked' : '' }}>
                            <span class="conf-step__selector">закрыт</span>
                        </label>

                        {{-- Удаление зала --}}
                        <button class="conf-step__button conf-step__button-trash"
                            data-open="popup-delete-hall"
                            data-hall="{{ $hall->id }}"
                            data-name="{{ $hall->name }}">
                        </button>
                    </li>
                @endforeach
            </ul>

            {{-- Создать зал --}}
            <button class="conf-step__button conf-step__button-accent" data-open="popup-add-hall">
                Создать зал
            </button>
        </div>
    </section>



    {{-- ========================================================= --}}
    {{-- БЛОК 2 — КОНФИГУРАЦИЯ ЗАЛОВ --}}
    {{-- ========================================================= --}}
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Конфигурация залов</h2>
        </header>

        <div class="conf-step__wrapper">

            <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>

            <ul class="conf-step__selectors-box">
                @foreach($halls as $hall)
                    <li>
                        <input type="radio"
                               class="conf-step__radio hall-select"
                               name="chairs-hall"
                               value="{{ $hall->id }}"
                               {{ $loop->first ? 'checked' : '' }}>
                        <span class="conf-step__selector">{{ $hall->name }}</span>
                    </li>
                @endforeach
            </ul>

            <p class="conf-step__paragraph">
                Укажите количество рядов и максимальное количество кресел в ряду:
            </p>

            <div class="conf-step__legend">

                <label class="conf-step__label" for="rowsInput">
                    Рядов, шт
                </label>
                <input
                    type="number"
                    id="rowsInput"
                    name="rows"
                    class="conf-step__input"
                    min="1"
                    autocomplete="off"
                >

                <span class="multiplier">x</span>

                <label class="conf-step__label" for="seatsInput">
                    Мест, шт
                </label>
                <input
                    type="number"
                    id="seatsInput"
                    name="seats"
                    class="conf-step__input"
                    min="1"
                    autocomplete="off"
                >

            </div>

            <p class="conf-step__paragraph">Теперь вы можете указать типы кресел:</p>

            <div class="conf-step__legend">
                <span class="conf-step__chair conf-step__chair_standart"></span> — обычные
                <span class="conf-step__chair conf-step__chair_vip"></span> — VIP
                <span class="conf-step__chair conf-step__chair_disabled"></span> — нет места
                <p class="conf-step__hint">Нажмите по креслу, чтобы изменить тип</p>
            </div>

            <div class="conf-step__hall">
                <div class="conf-step__hall-wrapper" id="hallScheme"></div>
            </div>

            <fieldset class="conf-step__buttons text-center">
                <button class="conf-step__button conf-step__button-regular" type="button">Отмена</button>
                <button class="conf-step__button conf-step__button-accent" id="saveConfig" type="button">Сохранить</button>
            </fieldset>

        </div>
    </section>



    {{-- ========================================================= --}}
    {{-- БЛОК 3 — КОНФИГУРАЦИЯ ЦЕН --}}
    {{-- ========================================================= --}}
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Конфигурация цен</h2>
        </header>

        <div class="conf-step__wrapper">

            <p class="conf-step__paragraph">Выберите зал:</p>

            <ul class="conf-step__selectors-box">
                @foreach($halls as $hall)
                    <li>
                        <input type="radio"
                            class="conf-step__radio price-hall-select"
                            name="price-hall"
                            value="{{ $hall->id }}"
                            {{ $loop->first ? 'checked' : '' }}>
                        <span class="conf-step__selector">{{ $hall->name }}</span>
                    </li>
                @endforeach
            </ul>

            <p class="conf-step__paragraph">Установите цены для типов кресел:</p>

        
            <form id="priceForm" action="javascript:void(0);">
                @csrf

                <div class="conf-step__legend">
                    <label class="conf-step__label" for="regular_price">
                        Цена, рублей
                        <input
                            id="regular_price"
                            name="regular_price"
                            type="number"
                            class="conf-step__input"
                            min="0"
                            autocomplete="off">
                    </label>
                    за <span class="conf-step__chair conf-step__chair_standart"></span> обычные кресла
                </div>

                <div class="conf-step__legend">
                    <label class="conf-step__label" for="vip_price">
                        Цена, рублей
                        <input
                            id="vip_price"
                            name="vip_price"
                            type="number"
                            class="conf-step__input"
                            min="0"
                            autocomplete="off">
                    </label>
                    за <span class="conf-step__chair conf-step__chair_vip"></span> VIP кресла
                </div>

                <fieldset class="conf-step__buttons text-center">
                    <button class="conf-step__button conf-step__button-regular" type="button">
                        Отмена
                    </button>

                    {{-- ВАЖНО: кнопка НЕ submit --}}
                    <button class="conf-step__button conf-step__button-accent" type="button" id="savePriceBtn">
                        Сохранить
                    </button>
                </fieldset>
            </form>

        </div>
    </section>



    {{-- ========================================================= --}}
    {{-- БЛОК 4 — СЕТКА СЕАНСОВ --}}
    {{-- ========================================================= --}}
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Сетка сеансов</h2>
        </header>
        <div class="conf-step__wrapper">

            <p class="conf-step__paragraph">
                <button class="conf-step__button conf-step__button-accent" data-open="popup-add-movie">
                    Добавить фильм
                </button>
            </p>

            <div class="conf-step__movies">
                @foreach ($movies as $movie)
                    <div class="conf-step__movie" data-id="{{ $movie->id }}">

                        {{-- ПОСТЕР --}}
                        @if ($movie->poster_url)
                            <img class="conf-step__movie-poster"
                                src="{{ asset('storage/' . $movie->poster_url) }}"
                                alt="poster">
                        @else
                            <img class="conf-step__movie-poster"
                                src="/assets/admin/img/default-poster.png"
                                alt="poster">
                        @endif

                        {{-- НАЗВАНИЕ --}}
                        <h3 class="conf-step__movie-title">{{ $movie->title }}</h3>

                        {{-- ДЛИТЕЛЬНОСТЬ --}}
                        <p class="conf-step__movie-duration">{{ $movie->duration }} минут</p>
                    </div>
                @endforeach
            </div>

            <div class="conf-step__seances">

                @foreach ($halls as $hall)
                    <div class="conf-step__seances-hall">
                        <h3 class="conf-step__seances-title">{{ $hall->name }}</h3>

                        <div class="conf-step__seances-timeline" data-hall="{{ $hall->id }}">

                            @php
                                // 1 минута = 0.5 пикселя, как в макете
                                $pxPerMinute = 0.5;
                            @endphp

                            @foreach ($sessions->where('hall_id', $hall->id) as $session)
                                @php
                                    // start_time хранится как DATETIME (YYYY-mm-dd HH:ii:ss)
                                    $timePart = substr($session->start_time, 11, 5); // "HH:MM"

                                    $startMinutes = intval(substr($timePart, 0, 2)) * 60
                                                  + intval(substr($timePart, 3, 2));

                                    $left  = $startMinutes * $pxPerMinute;
                                    $width = $session->movie->duration * $pxPerMinute;

                                    $color = 'rgb('. (100 + $session->movie_id * 10) .',255,150)';
                                @endphp

                                <div class="conf-step__seances-movie"
                                     style="left: {{ $left }}px; width: {{ $width }}px; background-color: {{ $color }};">
                                    
                                    <p class="conf-step__seances-movie-title">
                                        {{ $session->movie->title }}
                                    </p>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>

            <fieldset class="conf-step__buttons text-center">
                <button id="deleteMovieBtn" class="conf-step__button conf-step__button-regular" type="button">
                    Удалить фильм
                </button>
                <button class="conf-step__button conf-step__button-accent">Сохранить</button>
            </fieldset>

        </div>
    </section>



    {{-- ========================================================= --}}
    {{-- БЛОК 5 — ОТКРЫТЬ ПРОДАЖИ --}}
    {{-- ========================================================= --}}
    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Открыть продажи</h2>
        </header>
        <div class="conf-step__wrapper text-center">
            <p class="conf-step__paragraph">Всё готово!</p>
            <button class="conf-step__button conf-step__button-accent">Открыть продажу билетов</button>
        </div>
    </section>

</main>

@include('admin.popups.add-hall')
@include('admin.popups.delete-hall')
@include('admin.popups.add-movie')
@include('admin.popups.delete-seance')
@include('admin.popups.add-seance')
@endsection



@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    /* ------------------------------- */
    /* 1) Переключение статуса зала    */
    /* ------------------------------- */
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('hall-status-radio')) return;

        const hallId = e.target.dataset.id;
        const status = e.target.value;

        const fd = new FormData();
        fd.append('status', status);

        fetch(`/admin/halls/${hallId}/toggle`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            },
            body: fd
        });
    });



    /* ------------------------------- */
/* 2) Конфигурация залов           */
/* ------------------------------- */

const halls       = @json($halls);
const seatsByHall = @json($allSeatsGroupedByHall);

const rowsInput  = document.getElementById("rowsInput");
const seatsInput = document.getElementById("seatsInput");
const hallScheme = document.getElementById("hallScheme");
const saveBtn    = document.getElementById("saveConfig");


// { hallId: { seatId: "vip" | "regular" | "disabled" } }
let changedSeats = {};

let currentHallId = halls.length > 0 ? halls[0].id : null;

function seatClass(type) {
    switch (type) {
        case "vip": return "conf-step__chair_vip";
        case "disabled": return "conf-step__chair_disabled";
        default: return "conf-step__chair_standart";
    }
}


function nextSeatType(type) {
    switch (type) {
        case "regular": return "vip";
        case "vip": return "disabled";
        case "disabled": return "regular";
        default: return "regular";
    }
}

function toggleSeat(el, seatId) {
    const hallSeats = seatsByHall[currentHallId] || [];
    const seatObj = hallSeats.find(s => Number(s.id) === Number(seatId));
    if (!seatObj) return;

    
    const newType = nextSeatType(seatObj.seat_type);
    seatObj.seat_type = newType;

   
    if (!changedSeats[currentHallId]) {
        changedSeats[currentHallId] = {};
    }
    changedSeats[currentHallId][seatId] = newType;

    
    el.className = "conf-step__chair " + seatClass(newType);
}

function drawHall() {
    if (!currentHallId) return;

    const hall = halls.find(h => Number(h.id) === Number(currentHallId));
    if (!hall) return;

    rowsInput.value = hall.rows;
    seatsInput.value = hall.seats_per_row;

    hallScheme.innerHTML = "";

    const hallSeats = seatsByHall[currentHallId] || [];

    for (let r = 1; r <= hall.rows; r++) {
        const rowDiv = document.createElement("div");
        rowDiv.className = "conf-step__row";

        for (let s = 1; s <= hall.seats_per_row; s++) {
            const el = document.createElement("span");
            el.classList.add("conf-step__chair");

            const seat = hallSeats.find(seat =>
                Number(seat.row_number) === r &&
                Number(seat.seat_number) === s
            );

            if (seat) {
                el.dataset.seatId = seat.id;
                el.classList.add(seatClass(seat.seat_type));
                el.addEventListener("click", () => toggleSeat(el, seat.id));
            } else {
                el.classList.add("conf-step__chair_disabled");
            }

            rowDiv.appendChild(el);
        }

        hallScheme.appendChild(rowDiv);
    }
}


if (halls.length > 0) {
    document.querySelectorAll(".hall-select").forEach(radio => {
        radio.addEventListener("change", () => {
            currentHallId = radio.value;
            drawHall();
        });
    });
}


if (saveBtn) {
    saveBtn.addEventListener("click", () => {
        const hall = halls.find(h => h.id == currentHallId);
        if (!hall) return;

        const rows = Number(rowsInput.value);
        const seats = Number(seatsInput.value);

        if (rows < 1 || seats < 1) {
            alert("Ряды и места должны быть положительными числами");
            return;
        }

        const fd = new FormData();
        fd.append("name", hall.name);
        fd.append("rows", rows);
        fd.append("seats_per_row", seats);
        fd.append("_method", "PUT");

       
        const hallChanges = changedSeats[currentHallId] || {};

        if (Object.keys(hallChanges).length > 0) {
            fd.append("seats_changes", JSON.stringify(hallChanges));
        }

        fetch(`/admin/halls/${currentHallId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json"
            },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.seats) {
                seatsByHall[currentHallId] = data.seats;

                const hallObj = halls.find(h => h.id == currentHallId);
                hallObj.rows = rows;
                hallObj.seats_per_row = seats;

               
                changedSeats[currentHallId] = {};
            }

            drawHall();
        })
        .catch(err => {
            console.error(err);
            alert("Ошибка сохранения зала.");
        });
    });
}

drawHall();


    /* ------------------------------- */
    /* 3) КОНФИГУРАЦИЯ ЦЕН             */
    /* ------------------------------- */

    const priceForm    = document.getElementById("priceForm");
    const savePriceBtn = document.getElementById("savePriceBtn");
    const regularInput = document.getElementById("regular_price");
    const vipInput     = document.getElementById("vip_price");

    function loadPrices(hallId) {
        const hall = halls.find(h => Number(h.id) === Number(hallId));
        if (!hall) return;

        regularInput.value = hall.price?.regular_price ?? 0;
        vipInput.value     = hall.price?.vip_price ?? 0;
    }

    document.querySelectorAll('.price-hall-select').forEach(radio => {
        radio.addEventListener("change", () => loadPrices(radio.value));
    });

    const firstPriceHall = document.querySelector('.price-hall-select:checked');
    if (firstPriceHall && regularInput && vipInput) {
        loadPrices(firstPriceHall.value);
    }

    if (savePriceBtn && priceForm) {
        savePriceBtn.addEventListener("click", () => {
            priceForm.dispatchEvent(new Event("submit"));
        });

        priceForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const selected = document.querySelector('.price-hall-select:checked');
    if (!selected) {
        alert("Выберите зал");
        return;
    }

    const hallId = selected.value;
    const fd = new FormData(priceForm);

    fetch(`/admin/halls/${hallId}/prices`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;

       
        const hall = halls.find(h => Number(h.id) === Number(hallId));
        if (hall) {
            hall.price = {
                regular_price: data.price.regular_price,
                vip_price: data.price.vip_price
            };
        }

        alert("Цены сохранены!");
    })
    .catch(err => {
        console.error(err);
        alert("Ошибка сохранения цен");
    });
});

    }



    /* ------------------------------- */
    /* 4) Сетка сеансов: выбор/удаление фильма */
    /* ------------------------------- */

    let selectedMovieId = null;

    document.querySelectorAll('.conf-step__movie').forEach(el => {
        el.addEventListener('click', () => {
            document.querySelectorAll('.conf-step__movie').forEach(m => m.classList.remove('selected'));
            el.classList.add('selected');
            selectedMovieId = el.dataset.id;
        });
    });

    const deleteMovieBtn = document.getElementById('deleteMovieBtn');
    if (deleteMovieBtn) {
        deleteMovieBtn.addEventListener('click', () => {
            if (!selectedMovieId) {
                alert("Выберите фильм");
                return;
            }

            if (!confirm("Удалить выбранный фильм?")) return;

            fetch(`/admin/movies/${selectedMovieId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: new URLSearchParams({"_method": "DELETE"})
            })
            .then(() => location.reload());
        });
    }



    /* ------------------------------- */
    /* 5) Открытие попапа добавления сеанса */
    /* ------------------------------- */

    document.querySelectorAll(".conf-step__seances-timeline").forEach(timeline => {
        timeline.addEventListener("click", function (e) {
            const hallId = timeline.dataset.hall;

            const hall = halls.find(h => Number(h.id) === Number(hallId));
            if (hall && Number(hall.is_active) === 0) {
                alert("Зал закрыт. Нельзя добавлять сеансы.");
                return;
            }

            if (e.target.classList.contains("conf-step__seances-movie")) {
                return;
            }

            const hallSelect = document.getElementById("seance_hall");
            if (hallSelect) {
                hallSelect.value = hallId;
            }

            const popup = document.getElementById("popup-add-seance");
            if (popup) {
                popup.classList.add("active");
            }
        });
    });

});
</script>
@endpush
