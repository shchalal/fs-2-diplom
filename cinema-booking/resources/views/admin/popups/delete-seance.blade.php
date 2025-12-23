<div id="popup-delete-seance" class="popup"> 
    <div class="popup__container">
        <div class="popup__content">

            <div class="popup__header">
                <h2 class="popup__title">
                    Снятие с сеанса
                    <a class="popup__dismiss popup-close" href="#">
                        <img src="/assets/admin/i/close.png" alt="Закрыть">
                    </a>
                </h2>
            </div>

            <div class="popup__wrapper">

                <form id="delete-seance-form" method="POST">
                    @csrf
                    @method('DELETE')

                    <p class="conf-step__paragraph">
                        Вы действительно хотите снять с сеанса фильм
                        <span id="delete-seance-movie-name">""</span>?
                    </p>

                    <input type="hidden" id="delete-seance-id">

                    <div class="conf-step__buttons text-center">
                    
                        <button type="submit"
                                class="conf-step__button conf-step__button-accent">
                            Удалить
                        </button>


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
