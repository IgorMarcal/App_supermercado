<?php


require_once('../../App/Models/Manager.php');

// Recupera a lista de produtos em promoção do banco de dados
try {
    $connPdo = new PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    $sql = "SELECT * FROM `promocao` left join produto on(promocao.id=produto.id);";
    $stmt = $connPdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao recuperar os produtos em promoção: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Produtos em Promoção</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Produtos em Promoção</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome do Produto</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $promo): ?>
                    <tr>
                        <td><?php echo $promo['nome']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
