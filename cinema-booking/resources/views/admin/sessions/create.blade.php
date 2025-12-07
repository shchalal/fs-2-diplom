<h1>Создать сеанс</h1>

<form method="POST" action="{{ route('admin.sessions.store') }}">
    @csrf

    <label>Фильм:</label>
    <select name="movie_id">
        @foreach($movies as $movie)
            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
        @endforeach
    </select>

    <label>Зал:</label>
    <select name="hall_id">
        @foreach($halls as $hall)
            <option value="{{ $hall->id }}">{{ $hall->name }}</option>
        @endforeach
    </select>

    <label>Начало:</label>
    <input type="datetime-local" name="start_time">

    <label>Конец:</label>
    <input type="datetime-local" name="end_time">

    <label>Цена обычного места:</label>
    <input type="number" name="price_regular">

    <label>Цена VIP:</label>
    <input type="number" name="price_vip">

    <button>Создать</button>
</form>
