<div id="popup-add-movie" class="popup">
    <div class="popup__container">
        <div class="popup__content">

            <div class="popup__header">
                <h2 class="popup__title">
                    Добавление фильма
                    <a class="popup__dismiss popup-close" href="#">
                        <img src="/assets/admin/i/close.png" alt="Закрыть">
                    </a>
                </h2>
            </div>

            <div class="popup__wrapper">
                <form id="add-movie-form" enctype="multipart/form-data">
                    @csrf

                    <label class="conf-step__label conf-step__label-fullsize">
                        Название фильма
                        <input class="conf-step__input" type="text" name="title" required>
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize">
                        Описание
                        <textarea class="conf-step__input" name="description" rows="4"></textarea>
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize">
                        Длительность (мин)
                        <input class="conf-step__input" type="number" name="duration" required>
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize">
                        Постер (jpg/png)
                        <input class="conf-step__input" type="file" name="poster">
                    </label>

                    <div class="conf-step__buttons text-center">
                        <button type="button"
                                id="submit-add-movie"
                                class="conf-step__button conf-step__button-accent">
                            Добавить фильм
                        </button>
                        <button type="button" class="conf-step__button conf-step__button-regular popup-close">
                            Отмена
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
