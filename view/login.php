
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="/handle_db/loginconnect.php" method="post">
        <label for="email">Correo:</label>
        <input type="email" name="email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
