<!DOCTYPE html>
<html>
<head>
    <title>Login do Cliente</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <form class="login-form" method="POST" action="client/login.php">
            <h2>Login do Cliente</h2>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
