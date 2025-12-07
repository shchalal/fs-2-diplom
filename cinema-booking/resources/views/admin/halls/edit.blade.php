@extends('admin.layout')

@section('title', 'Редактирование зала: ' . $hall->name)

@section('content')
<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Редактирование зала</span>
</header>

<main class="conf-steps">

    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Параметры зала</h2>
        </header>

        <div class="conf-step__wrapper">

            <form action="{{ route('admin.halls.update', $hall) }}" method="POST">
                @csrf
                @method('PUT')

                <label class="conf-step__label conf-step__label-fullsize">
                    Название зала
                    <input class="conf-step__input" type="text" name="name" value="{{ $hall->name }}" required>
                </label>

                <div class="conf-step__legend">
                    <label class="conf-step__label">
                        Рядов
                        <input class="conf-step__input" type="number" name="rows" value="{{ $hall->rows }}" min="1" required>
                    </label>

                    <span class="multiplier">x</span>

                    <label class="conf-step__label">
                        Мест в ряду
                        <input class="conf-step__input" type="number" name="seats_per_row" value="{{ $hall->seats_per_row }}" min="1" required>
                    </label>
                </div>

                <fieldset class="conf-step__buttons text-center">
                    <a href="{{ route('admin.halls.index') }}" class="conf-step__button conf-step__button-regular">Отмена</a>
                    <button type="submit" class="conf-step__button conf-step__button-accent">Сохранить</button>
                </fieldset>

            </form>

        </div>
    </section>

</main>
@endsection
