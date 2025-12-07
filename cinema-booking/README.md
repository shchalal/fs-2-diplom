
     <h1>Cinema Booking — система онлайн-бронирования билетов</h1>
        <p>
            Этот проект представляет собой веб-приложение для бронирования билетов в кинотеатр.
            У приложения есть две части:
        </p>
        <ul>
            <li><strong>Административная</strong> — для управления залами, фильмами и расписанием.</li>
            <li><strong>Пользовательская</strong> — для гостей, которые выбирают фильм, дату, сеанс и места,
                после чего получают электронный билет с QR-кодом.</li>
        </ul>
        <p>Проект создан на Laravel с использованием готовой клиентской верстки.</p>

    <section class="section">
        <h2>Требования и окружение</h2>
        <p>Проект работает на:</p>
        <ul>
            <li>PHP</span> PHP 8.1+</li>
            <li>Laravel</span> Laravel 12</li>
            <li>MySQL</span> MySQL 8</li>
        </ul>
        <p>Дополнительно используется Laravel-пакет для генерации QR-кодов.</p>
    </section>

    <section class="section">
        <h2>Установка и запуск</h2>

        <h3>1. Клонирование проекта</h3>
        <pre><code>git clone https://github.com/yourname/cinema-booking.git
cd cinema-booking</code></pre>

        <h3>2. Установка зависимостей</h3>
        <pre><code>composer install</code></pre>

        <h3>3. Настройка окружения</h3>
        <pre><code>cp .env.example .env</code></pre>

        <p>Укажите параметры подключения к базе данных:</p>
        <pre><code>DB_DATABASE=cinema
DB_USERNAME=root
DB_PASSWORD=</code></pre>

        <h3>4. Генерация ключа</h3>
        <pre><code>php artisan key:generate</code></pre>

        <h3>5. Запуск миграций</h3>
        <pre><code>php artisan migrate</code></pre>

        <p>Если используются сидеры с тестовыми данными:</p>
        <pre><code>php artisan db:seed</code></pre>

        <h3>6. Создание ссылки на хранилище</h3>
        <pre><code>php artisan storage:link</code></pre>

        <h3>7. Запуск сервера</h3>
        <pre><code>php artisan serve</code></pre>

        <p>Приложение станет доступно по адресу:</p>
        <p><code>http://127.0.0.1:8000/</code></p>
    </section>

    <section class="section">
        <h2>Административная панель</h2>

        <p>Админ-панель расположена по адресу:</p>
        <p><code>http://127.0.0.1:8000/admin</code></p>

        <p>Если создан сидер администратора, логин может быть таким:</p>
        <ul>
            <li><strong>email:</strong> <code>admin@example.com</code></li>
            <li><strong>password:</strong> <code>admin</code></li>
        </ul>

        <p>Если сидера нет, пользователя можно создать вручную:</p>
        <pre><code>php artisan tinker</code></pre>

        <pre><code>User::create([
    'name'      =&gt; 'Admin',
    'email'     =&gt; 'admin@example.com',
    'password'  =&gt; Hash::make('admin'),
    'is_admin'  =&gt; true,
]);</code></pre>
    </section>

    <section class="section">
        <h2>Описание функционала</h2>

        <h3>Администратор</h3>
        <p>Администратор может:</p>
        <ul>
            <li>создавать и редактировать кинозалы;</li>
            <li>задавать количество рядов и мест, а также тип каждого места (обычное, VIP, отключённое);</li>
            <li>включать и выключать продажу билетов в конкретном зале;</li>
            <li>добавлять и редактировать фильмы с указанием длительности, описания, страны, постера;</li>
            <li>настраивать базовые цены для обычных и VIP-мест;</li>
            <li>создавать расписание сеансов. При добавлении одного сеанса система автоматически создаёт его на семь последующих дней. Если цена не указана вручную, подставляется базовая цена зала;</li>
            <li>редактировать и удалять существующие сеансы.</li>
        </ul>

        <h3>Пользовательская часть</h3>
        <p>Пользователь может:</p>
        <ul>
            <li>просматривать расписание по дням (дни переключаются вверху страницы);</li>
            <li>просматривать список фильмов с описанием;</li>
            <li>для каждого фильма видеть доступные сеансы по залам;</li>
            <li>выбрать конкретный сеанс и открыть схему зала;</li>
            <li>выбрать свободные места;</li>
            <li>забронировать билет.</li>
        </ul>

        <p>После бронирования пользователь получает электронный билет. В нём указаны:</p>
        <ul>
            <li>название фильма,</li>
            <li>дата и время сеанса,</li>
            <li>зал,</li>
            <li>ряд и место,</li>
            <li>уникальный код бронирования,</li>
            <li>QR-код.</li>
        </ul>

        <p>QR-код сохраняется как файл в <code>storage/app/public/qr/</code>.</p>
    </section>

    <section class="section">
        <h2>Важные технические моменты</h2>
        <ul>
            <li>Все формы проходят серверную валидацию.</li>
            <li>Пароли хранятся в хешированном виде.</li>
            <li>При попытке забронировать занятое место система не позволит завершить операцию.</li>
            <li>В клиентской части выводятся только активные залы.</li>
            <li>Расписание автоматически фильтруется по дате.</li>
            <li>QR-коды генерируются автоматически и сохраняются в файловом хранилище.</li>
        </ul>
    </section>

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
