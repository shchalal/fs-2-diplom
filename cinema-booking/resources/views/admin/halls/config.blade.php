@extends('admin.layout')

@section('title', 'Конфигурация зала: ' . $hall->name)

@section('content')
<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Настройка зала: {{ $hall->name }}</span>
</header>

<main class="conf-steps">

    <section class="conf-step">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Схема зала</h2>
        </header>

        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Кликните по месту, чтобы изменить его тип.</p>

            <div class="conf-step__legend">
                <span class="conf-step__chair conf-step__chair_standart"></span> — обычное
                <span class="conf-step__chair conf-step__chair_vip"></span> — VIP
                <span class="conf-step__chair conf-step__chair_disabled"></span> — нет места
            </div>

            <div class="conf-step__hall">
                <div class="conf-step__hall-wrapper">

                    @php
                        $rows = $seats->groupBy('row_number');
                    @endphp

                    @foreach($rows as $row)
                        <div class="conf-step__row">
                            @foreach($row as $seat)
                                <span
                                    class="conf-step__chair 
                                        @if($seat->seat_type === 'regular') conf-step__chair_standart
                                        @elseif($seat->seat_type === 'vip') conf-step__chair_vip
                                        @else conf-step__chair_disabled
                                        @endif
                                    "
                                    data-seat="{{ $seat->id }}"
                                ></span>
                            @endforeach
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </section>
</main>

@endsection

@section('scripts')
<script src="/assets/admin/js/accordeon.js"></script>

<script>
document.querySelectorAll('.conf-step__chair').forEach(chair => {

    chair.addEventListener('click', function () {

        const seatId = this.dataset.seat;

        fetch("{{ url('/admin/halls/' . $hall->id . '/seats') }}/" + seatId + "/toggle-ajax", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                this.classList.remove(
                    'conf-step__chair_standart',
                    'conf-step__chair_vip',
                    'conf-step__chair_disabled'
                );

                if (data.new_type === 'regular') {
                    this.classList.add('conf-step__chair_standart');
                } else if (data.new_type === 'vip') {
                    this.classList.add('conf-step__chair_vip');
                } else {
                    this.classList.add('conf-step__chair_disabled');
                }

            }
        });

    });

});
</script>
@endsection
