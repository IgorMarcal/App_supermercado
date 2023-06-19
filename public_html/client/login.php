<?php
    // Inclua as classes necessárias e quaisquer arquivos de configuração
    require "../../App/Models/User.php";

    // Verifique se os dados de login foram enviados via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Receba os dados de login do formulário
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Crie uma instância da classe User
        $client = new App\Models\User();

        try {
            // Chame o método de login para verificar as credenciais
            $client->login($email, $senha);
            // Login bem-sucedido, redirecione para a página de boas-vindas ou para o painel do gerent
            exit;
        } catch (\Exception $e) {
            // Lide com erros de login, exiba uma mensagem de erro ou redirecione de volta para a página de login
            echo "Erro ao fazer login: " . $e->getMessage();
        }
    }
?>
