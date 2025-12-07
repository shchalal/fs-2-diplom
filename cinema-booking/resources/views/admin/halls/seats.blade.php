<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Места зала: {{ $hall->name }}
        </h2>
    </x-slot>

    <div style="display: grid; grid-template-columns: repeat({{ $hall->seats_per_row }}, 60px); gap: 5px;">
        @foreach ($seats as $seat)
            <form method="POST" action="{{ route('admin.halls.seats.toggle', [$hall, $seat]) }}">
                @csrf
                <button style="
                    width: 60px; height: 60px;
                    border-radius: 6px;
                    border: 1px solid #999;
                    background: {{ $seat->seat_type === 'vip' ? '#fdd835' : '#90caf9' }};
                ">
                    R{{ $seat->row }}-{{ $seat->seat_number }}
                </button>
            </form>
        @endforeach
    </div>
</x-app-layout>
