<!DOCTYPE html>
<html lang="ru">

<h1>ИдёмВКино</h1>

<p>
    Проект «ИдёмВКино» — это система онлайн-бронирования киносеансов, включающая клиентскую часть
    (выбор фильма, просмотр сеансов, бронирование мест, получение билетов) и административную панель
    (управление залами, фильмами, ценами и расписанием).
</p>

<h2>Требования</h2>
<ul>
    <li>PHP 8.1+</li>
    <li>Laravel 12</li>
    <li>Composer</li>
    <li>SQLite (для тестов) или любая поддерживаемая СУБД (MySQL/PostgreSQL)</li>
    <li>Расширение GD (для генерации QR-кодов)</li>
</ul>

<h2>Установка</h2>

<ol>
    <li>Клонировать проект:
        <pre>git clone &lt;repository&gt;</pre>
    </li>

    <li>Установить зависимости:
        <pre>composer install</pre>
    </li>

    <li>Создать файл окружения:
        <pre>cp .env.example .env</pre>
    </li>

    <li>Сгенерировать ключ приложения:
        <pre>php artisan key:generate</pre>
    </li>

    <li>Выполнить миграции:
        <pre>php artisan migrate</pre>
    </li>

    <li>Создать симлинк для доступа к хранилищу:
        <pre>php artisan storage:link</pre>
    </li>
</ol>

<h2>Структура проекта</h2>

<h3>Основные директории</h3>
<ul>
    <li><code>app/Models</code> — модели приложения (Movie, MovieSession, CinemaHall, Seat, Ticket, HallPrice).</li>
    <li><code>app/Http/Controllers/Client</code> — контроллеры клиентской части.</li>
    <li><code>app/Http/Controllers/Admin</code> — контроллеры административной панели.</li>
    <li><code>resources/views/client</code> — шаблоны клиентского интерфейса.</li>
    <li><code>resources/views/admin</code> — шаблоны панели администратора.</li>
    <li><code>database/migrations</code> — миграции.</li>
    <li><code>database/factories</code> — фабрики моделей для тестов.</li>
    <li><code>tests/Feature</code> — функциональные тесты.</li>
</ul>

<h2>Клиентская часть</h2>

<p>Клиентские маршруты определены в корневой части <code>routes/web.php</code>.</p>

<h3>Главная страница</h3>
<p><strong>HomeController@index</strong> выводит список фильмов, у которых есть сеансы на текущую дату.</p>

<h3>Выбор мест</h3>
<p>
    <strong>HallController@index</strong> показывает схему зала для выбранного сеанса:
</p>
<ul>
    <li>ряд и номер места (<code>row_number</code>, <code>seat_number</code>)</li>
    <li>тип места (обычное, VIP)</li>
    <li>свободно / занято</li>
</ul>

<h3>Оплата</h3>
<p>
    <strong>PaymentController</strong> оформляет заказ, создаёт билеты и генерирует QR-коды
    (по одному на каждое место). Файлы QR-кодов сохраняются в <code>storage/app/public/qr/</code>.
</p>

<h3>Страница билета</h3>
<p>
    <strong>TicketController@show</strong> выводит информацию о заказе.  
    QR-код для каждого билета отображается тегом:
</p>

<pre>
&lt;img class="ticket__info-qr"
     src="{{ asset('storage/' . $ticket->qr_path) }}"
     alt="QR код {{ $ticket->booking_code }}"&gt;
</pre>

<h2>Административная панель</h2>

<p>Все маршруты панели находятся под префиксом <code>/admin</code> и защищены middleware <code>auth</code> и <code>isAdmin</code>.</p>

<h3>AdminDashboardController</h3>
<p>
    Вывод общей административной статистики. Доступен по маршруту <code>/admin</code>.
</p>

<h3>CinemaHallController</h3>
<p>
    Управление залами:
</p>
<ul>
    <li>создание, редактирование, активация/деактивация</li>
    <li>автоматическая генерация мест при создании</li>
    <li>AJAX-переключение типа места на плане зала</li>
</ul>

<h3>HallPriceController</h3>
<p>
    Управление ценами в зале. Модель <code>HallPrice</code> хранит цены на обычные и VIP-места.
</p>

<h3>MovieController</h3>
<p>
    CRUD-операции над фильмами: список, создание, редактирование, удаление.
</p>

<h3>MovieSessionController</h3>
<p>
    Управление сеансами:
</p>
<ul>
    <li>выбор фильма и зала</li>
    <li>указание времени начала, окончания и даты</li>
    <li>хранение цен для выбранного сеанса</li>
</ul>

<h2>QR-коды</h2>

<p>
    QR-код генерируется для каждого билета отдельно.  
    Изображения сохраняются в директорию <code>storage/app/public/qr</code>.
</p>

<p>
    Тесты проверяют наличие созданного файла через файловую систему Laravel.
</p>

<h2>Тестирование</h2>

<p>
    В проекте используется SQLite как тестовая база.  
    Запуск тестов выполняется стандартной командой:
</p>

<pre>php artisan test</pre>

<p>
    Важные тесты:
</p>

<ul>
    <li><strong>guest_can_book_tickets_and_qr_codes_are_generated</strong> — проверяет успешное бронирование и наличие QR-файлов.</li>
    <li><strong>seat_cannot_be_double_booked</strong> — гарантирует невозможность двойного бронирования одного места.</li>
</ul>

<h2>Генерация данных для разработки</h2>

<p>
    В системе есть отдельный отладочный маршрут, создающий тестовые места для зала №7:
</p>

<pre>/admin/debug/fill-seats-7</pre>





<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
