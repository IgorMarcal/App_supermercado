<?php
    require_once '../../App/Models/User.php';

    // Verifica se o usuário está autenticado
    if (!isset($_SESSION['user_id'])) {
        // Redireciona o usuário para a página de login
        header('Location: login.php');
        exit();
    }

    // Verifica se o formulário foi submetido
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recupera os dados do formulário
        $produtoId = $_POST['produto_id'];
        $_SESSION['user_pontos'];

        

        // Troca os pontos por produtos
        try {
            if(\App\Models\User::trocarPontosPorProduto($_SESSION['user_id'], $produtoId)){

                // Exibe uma mensagem de sucesso
        
                $mensagem = "Pontos trocados com sucesso!";
            }else{
                $mensagem = "Erro ao trocar pontos";
            }
        } catch (Exception $e) {
            // Exibe uma mensagem de erro
            $mensagem = "Erro ao trocar pontos: " . $e->getMessage();
        }
    }

    // Obtém a quantidade de pontos do cliente
    $pontos = \App\Models\User::getQuantidadePontos($_SESSION['user_id']);

    // Obtém a lista de produtos disponíveis para troca
    $produtos = \App\Models\User::getProdutosDisponiveisParaTroca();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Troca de Pontos</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Troca de Pontos</h2>
        <div class="points-container">
            <p class="points">Pontos disponíveis: <?= $pontos ?></p>
        </div>

        <h3>Produtos Disponíveis para Troca</h3>
        <ul class="product-list">
            <?php foreach ($produtos as $produto): ?>
                <li>
                    <span><?= $produto['nome'] ?></span>
                    <form class="troca-form" method="POST" action="#">
                        <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                        <button type="submit">Trocar</button>
                    </form>
                </li>
            <?php endforeach; ?>
            <a href="welcome.php">Voltar</a>
        </ul>

        <?php if (isset($mensagem)): ?>
            <p class="mensagem"><?= $mensagem ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
