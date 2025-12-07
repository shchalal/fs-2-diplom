<h1>Сеансы</h1>

<a href="{{ route('admin.sessions.create') }}">Создать сеанс</a>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Фильм</th>
        <th>Зал</th>
        <th>Начало</th>
        <th>Конец</th>
        <th>Обычные</th>
        <th>VIP</th>
        <th>Действия</th>
    </tr>

    @foreach ($sessions as $session)
        <tr>
            <td>{{ $session->id }}</td>
            <td>{{ $session->movie->title }}</td>
            <td>{{ $session->hall->name }}</td>
            <td>{{ $session->start_time }}</td>
            <td>{{ $session->end_time }}</td>
            <td>{{ $session->price_regular }} ₽</td>
            <td>{{ $session->price_vip }} ₽</td>
            <td>
                <a href="{{ route('admin.sessions.edit', $session) }}">Редактировать</a>

                <form action="{{ route('admin.sessions.destroy', $session) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button>Удалить</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
