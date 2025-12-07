<div class="popup" id="popup-add-hall">
    <div class="popup__container">
        <div class="popup__content">
            <div class="popup__header">
                <h2 class="popup__title">
                    Добавление зала
                    <a class="popup__dismiss popup-close" href="#">
                        <img src="/assets/admin/i/close.png" alt="Закрыть">
                    </a>
                </h2>
            </div>

            <div class="popup__wrapper">
                <form action="{{ route('admin.halls.store') }}" method="POST">
                    @csrf
                    <label class="conf-step__label conf-step__label-fullsize" for="name">
                        Название зала
                        <input class="conf-step__input"
                            type="text"
                            id="name"
                            name="name"
                            placeholder="Например, «Зал 1»"
                            required>
                    </label>

                    <div class="conf-step__buttons text-center">
                        <input type="submit"
                               value="Добавить зал"
                               class="conf-step__button conf-step__button-accent">

                        <button class="conf-step__button conf-step__button-regular popup-close" type="button">
                            Отменить
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
