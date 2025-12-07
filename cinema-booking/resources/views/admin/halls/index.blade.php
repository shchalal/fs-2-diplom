@extends('admin.layout')

@section('title', 'Залы')

@section('content')

<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Управление залами</span>
</header>

<main class="conf-steps">

    <section class="conf-step conf-step__header_opened">
        <header class="conf-step__header">
            <h2 class="conf-step__title">Список залов</h2>
        </header>

        <div class="conf-step__wrapper">

            <p class="conf-step__paragraph">
                <button class="conf-step__button conf-step__button-accent"
                        data-open="popup-add-hall">
                    Создать зал
                </button>
            </p>

            <table class="conf-step__table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Ряды</th>
                        <th>Мест в ряду</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($halls as $hall)
                    <tr>
                        <td>{{ $hall->id }}</td>
                        <td>{{ $hall->name }}</td>
                        <td>{{ $hall->rows }}</td>
                        <td>{{ $hall->seats_per_row }}</td>
                        <td>{{ $hall->is_active ? 'Открыт' : 'Закрыт' }}</td>

                        <td>

                            <a href="{{ route('admin.halls.edit', $hall) }}"
                               class="conf-step__button conf-step__button-regular">
                                Редактировать
                            </a>

                            <a href="{{ route('admin.halls.config', $hall) }}"
                               class="conf-step__button conf-step__button-regular">
                                Схема
                            </a>

                            <form action="{{ route('admin.halls.toggle', $hall) }}"
                                  method="POST" style="display:inline-block;">
                                @csrf
                                <button class="conf-step__button conf-step__button-accent">
                                    {{ $hall->is_active ? 'Закрыть' : 'Открыть' }}
                                </button>
                            </form>

                            <button class="conf-step__button conf-step__button-trash"
                                    data-open="popup-delete-hall"
                                    data-hall="{{ $hall->id }}"
                                    data-name="{{ $hall->name }}">
                            </button>

                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </section>

</main>

@include('admin.popups.add-hall')
@include('admin.popups.delete-hall')

@endsection
