<div class="popup" id="popup-delete-hall">
    <div class="popup__container">
        <div class="popup__content">

            <div class="popup__header">
                <h2 class="popup__title">
                    Удаление зала
                    <a class="popup__dismiss popup-close" href="#">
                        <img src="/assets/admin/i/close.png" alt="Закрыть">
                    </a>
                </h2>
            </div>

            <div class="popup__wrapper">

                <form action="{{ route('admin.halls.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="hall_id" id="delete-hall-id">

                    <p class="conf-step__paragraph">
                        Вы действительно хотите удалить зал 
                        "<span id="delete-hall-name"></span>"?
                    </p>

                    <div class="conf-step__buttons text-center">
                        <input type="submit"
                               value="Удалить"
                               class="conf-step__button conf-step__button-accent">

                        <button type="button"
                                class="conf-step__button conf-step__button-regular popup-close">
                            Отменить
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
