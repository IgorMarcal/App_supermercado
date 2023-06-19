<?php
    require_once '../../App/Models/User.php';

    // Verifica se o usuário está autenticado
    if (isset($_SESSION['user_id'])) {
        // Recupera a quantidade de pontos do cliente
        $pontos = \App\Models\User::getQuantidadePontos($_SESSION['user_id']);
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Pontos do Cliente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Pontos do Cliente</h2>
        <div class="points-container">
            <p class="points"> <?= $pontos ?? '0' ?> </p>
        </div>
        <a href="troca_pontos.php">Trocar</a>
    </div>
</body>
</html>
