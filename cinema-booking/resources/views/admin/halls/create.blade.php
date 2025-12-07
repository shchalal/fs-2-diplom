<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Создать зал</h2>
    </x-slot>

    <form method="POST" action="{{ route('admin.halls.store') }}">
        @csrf

        <label>Название:</label>
        <input type="text" name="name">

        <label>Ряды:</label>
        <input type="number" name="rows">

        <label>Мест в ряду:</label>
        <input type="number" name="seats_per_row">

        <button>Создать</button>
    </form>
</x-app-layout>
