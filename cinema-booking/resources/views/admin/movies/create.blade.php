<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Добавить фильм</h2>
    </x-slot>

    <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Название:</label>
        <input type="text" name="title" class="border">

        <label>Описание:</label>
        <textarea name="description" class="border"></textarea>

        <label>Длительность (мин):</label>
        <input type="number" name="duration" class="border">

        <label>Постер:</label>
        <input type="file" name="poster">

        <button class="bg-green-600 text-white px-4 py-2 mt-4">Создать</button>
    </form>
</x-app-layout>
