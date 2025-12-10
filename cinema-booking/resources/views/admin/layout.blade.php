<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Админ-панель')</title>

    <link rel="stylesheet" href="/assets/admin/css/normalize.css">
    <link rel="stylesheet" href="/assets/admin/css/styles.css">

    @yield('styles')
</head>

<body>

    @yield('content')

    {{-- Общие скрипты --}}
    <script src="/assets/admin/js/accordeon.js"></script>

    <script>
       
    console.log("ADMIN LAYOUT JS LOADED!");

    document.addEventListener('DOMContentLoaded', () => {
        console.log(
            'Popups on page:',
            Array.from(document.querySelectorAll('.popup')).map(p => p.id)
        );
    });

    document.addEventListener('click', function (event) {
        
        console.log('GLOBAL CLICK:', event.target);
        
        const opener = event.target.closest('[data-open]');
        console.log('OPENER FOUND:', opener); 
        
    
        if (opener) {
            const popupId = opener.dataset.open;
            const popup   = document.getElementById(popupId);
            console.log('popupId =', popupId, 'popup =', popup); 

            if (!popup) return;

            if (popupId === 'popup-delete-hall' && opener.dataset.hall) {
                const idInput  = document.getElementById('delete-hall-id');
                const nameSpan = document.getElementById('delete-hall-name');

                if (idInput)  idInput.value        = opener.dataset.hall;
                if (nameSpan) nameSpan.textContent = opener.dataset.name || '';
            }

            popup.classList.add('active');
            console.log('POPUP OPENED:', popupId);
            return;
        }

        
        const closer = event.target.closest('.popup-close');
        if (closer) {
            const popup = closer.closest('.popup');
            if (popup) {
                popup.classList.remove('active');
                console.log('POPUP CLOSED:', popup.id);
            }
        }
    });


    </script>

    @stack('scripts')

</body>
</html>
