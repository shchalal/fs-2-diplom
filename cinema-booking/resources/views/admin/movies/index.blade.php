<h1>Фильмы</h1>

<a href="{{ route('admin.movies.create') }}">Добавить фильм</a>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Действия</th>
    </tr>

    @foreach($movies as $movie)
        <tr>
            <td>{{ $movie->id }}</td>
            <td>{{ $movie->title }}</td>
            <td>
                <a href="{{ route('admin.movies.edit', $movie) }}">Редактировать</a>
                |
                <form action="{{ route('admin.movies.destroy', $movie) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button>Удалить</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
