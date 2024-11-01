<!DOCTYPE html>
<html>
<head>
    <title>Вход</title>
</head>
<body>
    <h1>Вход</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Войти</button>
    </form>

    <h2>Или войдите через ВКонтакте</h2>
    <a href="{{ route('auth.vk') }}">Войти через ВКонтакте</a>
</body>
</html>
