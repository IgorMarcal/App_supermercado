<?php
    //header('Content-Type: application/json');

    require_once '../vendor/autoload.php';

    // api/users/1
    //var_dump($_GET['url']);
    if (isset($_GET['url'])) {
        $url = explode('/', $_GET['url']);

        if (isset($url[0]) && $url[0] === 'api') {
            array_shift($url);

            $service = 'App\Services\\'.ucfirst($url[0]).'Service';
            array_shift($url);

            $method = strtolower($_SERVER['REQUEST_METHOD']);

            // Verificar se é a URL de login do cliente
            if ($url[0] === 'cliente' && $method === 'get' && $url[1] === 'login') {
                // Redirecionar para a tela de login do cliente
                header('Location: cliente_login.php');
                exit;
            }

            // Verificar se é a URL de login do gerente
            if (isset($url[0]) && $url[0] === 'api'&& $url[0] === 'gerente' && $method === 'get' && $url[1] === 'login') {
                // Redirecionar para a tela de login do gerente
                header('Location: manager_login.php');
                exit;
            }

            
            try {
                $response = call_user_func_array(array(new $service, $method), $url);

                http_response_code(200);
                echo json_encode(array('status' => 'success', 'data' => $response));
                exit;
            } catch (\Exception $e) {
                http_response_code(404);
                echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }else{
        header('location: manager_login.php');
    }
?>

