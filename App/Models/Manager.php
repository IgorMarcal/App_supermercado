<?php
namespace App\Models;
    require_once "../../config.php";
    use \PDO;
    


    class Manager
    {
        private static $table = 'gerente';


        public function login(){
            // Receba os dados de login do gerente via POST
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
                    return $stmt->fetch(\PDO::FETCH_ASSOC);
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

        public function updateProduto($produto){
            try{

                $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
            
                $sql = 'select id FROM produto WHERE nome = :nome';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':nome', $produto['nome']);
                $stmt->execute();
                
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result && isset($result['id'])) {
                    $produtoId = $result['id'];
                } else {
                    // Produto não encontrado
                    throw new Exception("Produto não encontrado!");
                }
                

                $sql = 'select produto_id FROM promocao WHERE produto_id = :produto_id';
                $stmt = $connPdo->prepare($sql);
                $stmt->bindValue(':produto_id', $produtoId);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // Atualiza os dados da promoção existente
                    $sql = 'update promocao SET desconto = :desconto, data_inicio = :data_inicio, data_fim = :data_fim WHERE produto_id = :produto_id';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':desconto', $produto['desconto']);
                    $stmt->bindValue(':data_inicio', $produto['data_inicio']);
                    $stmt->bindValue(':data_fim', $produto['data_fim']);
                    $stmt->bindValue(':produto_id', $produtoId);
                    $stmt->execute();
            
                    if ($stmt->rowCount() > 0) {
                        return 'Promoção atualizada com sucesso!';
                    } else {
                        throw new \Exception("Falha ao atualizar promoção!");
                    }
                } else {
                    // Insere os dados da nova promoção
                    $sql = 'insert INTO promocao (produto_id, desconto, data_inicio, data_fim) VALUES (:produto_id, :desconto, :data_inicio, :data_fim)';
                    $stmt = $connPdo->prepare($sql);
                    $stmt->bindValue(':produto_id', $produtoId);
                    $stmt->bindValue(':desconto', $produto['desconto']);
                    $stmt->bindValue(':data_inicio', $produto['data_inicio']);
                    $stmt->bindValue(':data_fim', $produto['data_fim']);
                    $stmt->execute();
            
                    if ($stmt->rowCount() > 0) {
                        return 'Promoção criada com sucesso!';
                    } else {
                        throw new \Exception("Falha ao criar promoção!");
                    }
                }
            }catch (Exception $e) {
                // Tratamento de exceção
                echo "Erro ao atualizar o produto: " . $e->getMessage();
            }
            
        }



        public static function insert($data)
        {
            $connPdo = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

            $sql = 'INSERT INTO '.self::$table.' (email, senha, name) VALUES (:em, :pa, :na)';
            $stmt = $connPdo->prepare($sql);
            $stmt->bindValue(':em', $data['email']);
            $stmt->bindValue(':pa', $data['senha']);
            $stmt->bindValue(':na', $data['name']);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return 'Usuário(a) inserido com sucesso!';
            } else {
                throw new \Exception("Falha ao inserir usuário(a)!");
            }
        }
    }