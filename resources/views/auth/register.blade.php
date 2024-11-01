<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Имя:</label>
        <input type="text" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" >
        <br>
        <label for="password">Пароль:</label>
        <input type="password" name="password" >
        <br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>
