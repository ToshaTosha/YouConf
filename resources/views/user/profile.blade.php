<!DOCTYPE html>
<html>
<head>
    <title>Профиль пользователя</title>
</head>
<body>
    <h1>Добро пожаловать, {{ Auth::user()->name }}</h1>
    <p>Ваш Email: {{ Auth::user()->email }}</p>
    <p>Ваш ID ВКонтакте: {{ Auth::user()->vk_id ?? 'Не привязано' }}</p>

    @if (is_null(Auth::user()->vk_id))
        <a href="{{ route('auth.vk') }}">Привязать страницу ВКонтакте</a>
    @else
        <p>Страница ВКонтакте уже привязана.</p>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Выйти</button>
    </form>
</body>
</html>
