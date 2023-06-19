<?php
    namespace App\Models;
    use \PDO;
    session_start();
    require_once "../../config.php";

    class User
    {
        private static $table = 'cliente';


        public static function login($email, $password){
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            try {
                $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

                $sql = 'select * FROM '.self::$table.' WHERE email = :email AND senha = :senha';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':senha', $senha);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    header('Location: welcome.php');
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                    echo $_SESSION['user_id'] = $result['id'];
                    return;
                } else {
                    throw new \Exception("Credenciais de login inválidas!");
                }

                exit();
            } catch (\Exception $e) {
                // Lide com erros de login, por exemplo, exibindo uma mensagem de erro ou redirecionando para a página de login com uma mensagem de erro
                echo "Erro ao fazer login: " . $e->getMessage();
                // Ou
                // header('Location: login.php?error=1');
                // exit();
            }
        }

        public static function select(int $id) {
            $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

            $sql = 'select * FROM '.self::$table.' WHERE id = :id';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum usuário encontrado!");
            }
        }

        public static function selectAll() {
            $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

            $sql = 'select * FROM '.self::$table;
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            } else {
                throw new \Exception("Nenhum usuário encontrado!");
            }
        }

        public static function insert($data)
        {
            $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

            $sql = 'INSERT INTO '.self::$table.' (email, password, name) VALUES (:em, :pa, :na)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':em', $data['email']);
            $stmt->bindValue(':pa', $data['password']);
            $stmt->bindValue(':na', $data['name']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Usuário(a) inserido com sucesso!';
            } else {
                throw new \Exception("Falha ao inserir usuário(a)!");
            }
        }

        public static function getQuantidadePontos($id_cliente) {
            // Recupera a conexão com o banco de dados
            $connPdo = new PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

            // Prepara a consulta SQL para obter a quantidade de pontos
            $sql = 'select * FROM pontos WHERE cliente_id = :id_cliente';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':id_cliente', $id_cliente);
            $stmt->execute();
    
            // Verifica se a consulta retornou algum resultado
            if ($stmt->rowCount() > 0) {
                // Obtém o resultado da consulta
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['pontos'];
            } else {
                // Cliente não encontrado ou sem pontos registrados
                return 0;
            }
        }

        public static function getProdutosDisponiveisParaTroca() {
            // Recupera a conexão com o banco de dados
            $connPdo = new PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
        
            // Prepara a consulta SQL para obter os produtos disponíveis para troca
            $sql = 'select * FROM produto';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();
        
            // Obtém os resultados da consulta
            $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            return $produtos;
        }

        public static function trocarPontosPorProduto($produtoId) {
            // Recupera a conexão com o banco de dados
            $connPdo = new PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
            
            $sql = 'select pontos FROM produto';
            $stmt = $connPdo->prepare($sql);
            $stmt->execute();
            $pts = $stmt->fetchAll(PDO::FETCH_ASSOC);


            // Verifica se a consulta retornou algum resultado
            if ($stmt->rowCount() > 0) {
                // Obtém os dados do produto
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        
                // Subtrai os pontos do cliente
                $novosPontos = $_SESSION['user_pontos'] - $produto['pontos'];
        
                // Atualiza a quantidade de pontos do cliente
                $sql = 'UPDATE pontos SET pontos = :novos_pontos WHERE id_cliente = :id_cliente';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':novos_pontos', $novosPontos);
                $stmt->bindValue(':id_cliente', $_SESSION['user_id']);
                $stmt->execute();
        
                // Cria o voucher para o cliente
                $voucher = 'VOUCHER-' . uniqid();
        
                // Insere o registro do voucher no banco de dados
                $sql = 'INSERT INTO vouchers (id_cliente, produto_id, voucher) VALUES (:id_cliente, :produto_id, :voucher)';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':id_cliente', $_SESSION['user_id']);
                $stmt->bindValue(':produto_id', $produto['id']);
                $stmt->bindValue(':voucher', $voucher);
                $stmt->execute();
        
                return $voucher; // Retorna o voucher gerado
            } else {
                throw new \Exception("Produto não encontrado ou pontos insuficientes para a troca.");
            }
        }        

    }