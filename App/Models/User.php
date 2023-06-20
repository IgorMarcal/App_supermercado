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

                $sql = 'select c.id, c.nome, c.email, c.senha, p.pontos FROM '.self::$table.' as c
                    inner join pontos as p
                    WHERE email = :email AND senha = :senha';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':senha', $senha);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    header('Location: welcome.php');
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $_SESSION['user_id'] = $result['id'];
                    $_SESSION['user_pontos'] = $result['pontos'];
                    return $result;
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

            $sql = 'select c.id, c.nome, c.email, c.senha, p.pontos FROM '.self::$table.' as c
                inner join pontos as p 
                WHERE c.id = :id';
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

            $sql = 'insert INTO '.self::$table.' (email, password, name) VALUES (:em, :pa, :na)';
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

        public static function trocarPontosPorProduto($id_usuario,$produtoId) {
            // Recupera a conexão com o banco de dados
            $connPdo = new PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
            
            $sql = 'select pontos FROM produto where id = ?';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(1, $produtoId);
            $stmt->execute();
            $pts_prod = $stmt->fetch(PDO::FETCH_ASSOC);

            

            // Verifica se a consulta retornou algum resultado
            if ($stmt->rowCount() > 0) {
               
                $sql = 'select pontos FROM pontos where cliente_id = ?';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(1, $id_usuario);
                $stmt->execute();
                $pts = $stmt->fetch(PDO::FETCH_ASSOC);
                if($pts['pontos']<=0){
                    return false;
                }

                
                if($pts['pontos'] >= $pts_prod['pontos']){
                    // Subtrai os pontos do cliente
                    $novosPontos = $pts['pontos'] - $pts_prod['pontos'];
                    
                    // Atualiza a quantidade de pontos do cliente
                    $sql = 'update pontos SET pontos = :novos_pontos WHERE cliente_id = :id_cliente';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':novos_pontos', $novosPontos);
                    $stmt->bindValue(':id_cliente', $id_usuario);
                    $stmt->execute();
                    return true;
                }
                
            } else {
                throw new \Exception("Produto não encontrado ou pontos insuficientes para a troca.");
            }
        }        

    }