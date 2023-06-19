<?php

require_once '../../App/Models/Manager.php';
require_once '../../App/Models/User.php';

session_start();


?>

<!DOCTYPE html>
<html>
<head>
    <title>Clientes</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Lista de Clientes</h2>

        <?php
        // Recupere a lista de clientes
        $clients = \App\Models\User::selectAll();

        if (count($clients) > 0) {
            echo '<ul>';
            foreach ($clients as $client) {
                echo '<li>' . $client['nome'] . '</li>';
                // Exiba outras informações do cliente, se necessário
            }
            echo '</ul>';
        } else {
            echo '<p>Nenhum cliente encontrado.</p>';
        }
        ?>
    </div>
</body>
</html>
