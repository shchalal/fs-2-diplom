<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Редактировать фильм</h2>
    </x-slot>

    <form method="POST" action="{{ route('admin.movies.update', $movie) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Название:</label>
        <input type="text" name="title" value="{{ $movie->title }}" class="border">

        <label>Описание:</label>
        <textarea name="description" class="border">{{ $movie->description }}</textarea>

        <label>Длительность (мин):</label>
        <input type="number" name="duration" value="{{ $movie->duration }}" class="border">

        <label>Постер:</label>
        <input type="file" name="poster">
        @if($movie->poster)
            <img src="/storage/{{ $movie->poster }}" width="80">
        @endif

        <button class="bg-blue-600 text-white px-4 py-2 mt-4">Сохранить</button>
    </form>
</x-app-layout>
