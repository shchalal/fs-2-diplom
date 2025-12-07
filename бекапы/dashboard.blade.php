@extends('admin.layout')

@section('title', 'Админ-панель')

@section('content')

<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Администраторррская</span>
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

                    {{-- Радиокнопка статуса --}}
                    <ul class="conf-step__selectors-box">
                        <li>
                            <label>
                                <input type="radio"
                                       class="conf-step__radio hall-status-radio"
                                       name="status-{{ $hall->id }}"
                                       data-id="{{ $hall->id }}"
                                       value="1"
                                       {{ $hall->is_active ? 'checked' : '' }}>
                                <span class="conf-step__selector">открыт</span>
                            </label>
                        </li>

                        <li>
                            <label>
                                <input type="radio"
                                       class="conf-step__radio hall-status-radio"
                                       name="status-{{ $hall->id }}"
                                       data-id="{{ $hall->id }}"
                                       value="0"
                                       {{ !$hall->is_active ? 'checked' : '' }}>
                                <span class="conf-step__selector">закрыт</span>
                            </label>
                        </li>
                    </ul>

                    {{-- Кнопка удаления --}}
                    <button class="conf-step__button conf-step__button-trash"
                            data-open="popup-delete-hall"
                            data-hall="{{ $hall->id }}"
                            data-name="{{ $hall->name }}">
                    </button>

                </li>
                @endforeach
            </ul>

            {{-- СОЗДАТЬ ЗАЛ --}}
            <button class="conf-step__button conf-step__button-accent"
                    data-open="popup-add-hall">
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
                    <label>
                        <input type="radio"
                               class="conf-step__radio config-hall-radio"
                               name="config-hall"
                               value="{{ $hall->id }}">
                        <span class="conf-step__selector">{{ $hall->name }}</span>
                    </label>
                </li>
                @endforeach
            </ul>

            {{-- ДО ЗАГРУЗКИ AJAX выводим заглушку, как в макете --}}
            <div id="hall-config-area">
                <p class="conf-step__paragraph">Выберите зал, чтобы отредактировать его конфигурацию.</p>
            </div>

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
                    <label>
                        <input type="radio" class="conf-step__radio" name="prices-hall">
                        <span class="conf-step__selector">{{ $hall->name }}</span>
                    </label>
                </li>
                @endforeach
            </ul>

            <p class="conf-step__paragraph">Установите цены:</p>

            <div class="conf-step__legend">
                <label class="conf-step__label">Цена
                    <input type="text" class="conf-step__input">
                </label>
                за <span class="conf-step__chair conf-step__chair_standart"></span> обычные
            </div>

            <div class="conf-step__legend">
                <label class="conf-step__label">Цена
                    <input type="text" class="conf-step__input">
                </label>
                за <span class="conf-step__chair conf-step__chair_vip"></span> VIP
            </div>

            <fieldset class="conf-step__buttons text-center">
                <button class="conf-step__button conf-step__button-regular">Отмена</button>
                <button class="conf-step__button conf-step__button-accent">Сохранить</button>
            </fieldset>

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
                <button class="conf-step__button conf-step__button-accent"
                        data-open="popup-add-movie">
                    Добавить фильм
                </button>
            </p>

            <div class="conf-step__movies"></div>
            <div class="conf-step__seances"></div>

            <fieldset class="conf-step__buttons text-center">
                <button class="conf-step__button conf-step__button-regular">Отмена</button>
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


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

//
// 1. AJAX переключение статуса (открыт / закрыт)
//
document.addEventListener('change', e => {
    if (!e.target.classList.contains('hall-status-radio')) return;

    const id = e.target.dataset.id;

    fetch(`/admin/halls/${id}/toggle`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json"
        }
    });
});


//
// 2. Загрузка схемы зала AJAX при выборе в блоке 2
//
document.addEventListener('change', e => {
    if (!e.target.classList.contains('config-hall-radio')) return;

    const id = e.target.value;

    fetch(`/admin/halls/${id}/config`, {
        headers: { "Accept": "text/html" }
    })
    .then(r => r.text())
    .then(html => {
        document.getElementById('hall-config-area').innerHTML = html;
    });
});
</script>


{{-- POPUPS --}}
@include('admin.popups.add-hall')
@include('admin.popups.delete-hall')
@include('admin.popups.add-movie')

@endsection
