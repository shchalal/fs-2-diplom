<div id="popup-add-seance" class="popup">
    <div class="popup__container">
        <div class="popup__content">

            <div class="popup__header">
                <h2 class="popup__title">
                    Добавление сеанса
                    <a class="popup__dismiss popup-close" href="#">
                        <img src="/assets/admin/i/close.png" alt="Закрыть">
                    </a>
                </h2>
            </div>

            <div class="popup__wrapper">
                <form action="{{ route('admin.sessions.store') }}" method="POST">
                    @csrf

                    <label class="conf-step__label conf-step__label-fullsize">
                        Название зала
                        <select class="conf-step__input" name="hall_id" id="seance_hall" required>
                            @foreach($halls as $hall)
                                <option value="{{ $hall->id }}">{{ $hall->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize">
                        Название фильма
                        <select class="conf-step__input" name="movie_id" required>
                            @foreach($movies as $movie)
                                <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize">
                        Время начала
                        <input class="conf-step__input" type="time" name="start_time" required>
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize">
                        Цена обычных
                        <input class="conf-step__input" type="number" name="price_regular" required min="0">
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize">
                        Цена VIP
                        <input class="conf-step__input" type="number" name="price_vip" required min="0">
                    </label>

                    <div class="conf-step__buttons text-center">
                        <input type="submit" value="Добавить"
                               class="conf-step__button conf-step__button-accent">

                        <button type="button"
                                class="conf-step__button conf-step__button-regular popup-close">
                            Отмена
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
