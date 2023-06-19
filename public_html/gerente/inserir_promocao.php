<?php
require_once '../../App/Models/Manager.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $nome = $_POST['nome'];
    $desconto = $_POST['desconto'];
    $dataInicio = $_POST['data_inicio'];
    $dataFim = $_POST['data_fim'];

    $manager = new App\Models\Manager();
    // Cria um novo produto em promoção
    try {
        $produto = [
            'nome' => $nome,
            'desconto' => $desconto,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim
        ];
        $result = $manager->updateProduto($produto);

        // Exibe uma mensagem de sucesso
        echo "Produto inserido com sucesso!";
    } catch (Exception $e) {
        // Exibe uma mensagem de erro
        echo "Erro ao inserir o produto: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Novo Produto em Promoção</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<div class="container">
        <h2>Novo Produto em Promoção</h2>
        <form class="product-form" method="POST" action="">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div class="form-group">
                <label for="desconto">Desconto:</label>
                <input type="text" name="desconto" id="desconto" required>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" name="data_inicio" id="data_inicio" required>
            </div>
            <div class="form-group">
                <label for="data_fim">Data de Fim:</label>
                <input type="date" name="data_fim" id="data_fim" required>
            </div>
            <button type="submit">Adicionar Produto</button>
        </form>
        <a href="welcome.php">Voltar</a>
    </div>
</body>
</html>
