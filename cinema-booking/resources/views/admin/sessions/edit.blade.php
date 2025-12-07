<h1>Редактировать сеанс</h1>

<form method="POST" action="{{ route('admin.sessions.update', $session) }}">
    @csrf
    @method('PUT')

    {{-- Фильм --}}
    <label>Фильм:</label>
    <select name="movie_id">
        @foreach($movies as $movie)
            <option value="{{ $movie->id }}"
                @if($movie->id == $session->movie_id) selected @endif>
                {{ $movie->title }}
            </option>
        @endforeach
    </select>
    <br><br>

    {{-- Зал --}}
    <label>Зал:</label>
    <select name="hall_id">
        @foreach($halls as $hall)
            <option value="{{ $hall->id }}"
                @if($hall->id == $session->hall_id) selected @endif>
                {{ $hall->name }}
            </option>
        @endforeach
    </select>
    <br><br>

    {{-- Время начала --}}
    <label>Начало:</label>
    <input type="datetime-local" name="start_time"
           value="{{ date('Y-m-d\TH:i', strtotime($session->start_time)) }}">
    <br><br>

    {{-- Время конца --}}
    <label>Конец:</label>
    <input type="datetime-local" name="end_time"
           value="{{ date('Y-m-d\TH:i', strtotime($session->end_time)) }}">
    <br><br>

    {{-- Цены --}}
    <label>Цена обычного места:</label>
    <input type="number" name="price_regular" value="{{ $session->price_regular }}">
    <br><br>

    <label>Цена VIP:</label>
    <input type="number" name="price_vip" value="{{ $session->price_vip }}">
    <br><br>

    <button>Сохранить изменения</button>
</form>
