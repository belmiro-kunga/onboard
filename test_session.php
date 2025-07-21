<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Inicializar o kernel da aplicação
try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
} catch (Exception $e) {
    die("Erro ao inicializar o kernel: " . $e->getMessage());
}

// Capturar a requisição
try {
    $request = Illuminate\Http\Request::capture();
    $response = $kernel->handle($request);
} catch (Exception $e) {
    die("Erro ao processar a requisição: " . $e->getMessage());
}

// Função para escapar saída HTML
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

// Obter configurações de sessão de forma segura
function getSessionConfig($key, $default = null) {
    $value = config("session." . $key, $default);
    
    // Se for um objeto, converte para array
    if (is_object($value)) {
        return json_encode($value, JSON_PRETTY_PRINT);
    }
    
    // Se for um array, converte para string formatada
    if (is_array($value)) {
        return json_encode($value, JSON_PRETTY_PRINT);
    }
    
    // Se for nulo, retorna 'null' como string
    if (is_null($value)) {
        return 'null';
    }
    
    // Se for booleano, converte para 'Sim' ou 'Não'
    if (is_bool($value)) {
        return $value ? 'Sim' : 'Não';
    }
    
    // Para outros tipos, converte para string
    return (string) $value;
}

// Obter configurações de sessão
$sessionConfig = [
    'driver' => getSessionConfig('driver'),
    'cookie' => getSessionConfig('cookie'),
    'domain' => getSessionConfig('domain'),
    'secure' => getSessionConfig('secure'),
    'http_only' => getSessionConfig('http_only'),
    'same_site' => getSessionConfig('same_site'),
    'lifetime' => getSessionConfig('lifetime'),
    'files' => getSessionConfig('files'),
];

// Verificar se a sessão foi iniciada
$sessionStarted = session_status() === PHP_SESSION_ACTIVE;

// Verificar cookies
$cookies = $request->cookies->all();

// Verificar se o cookie de sessão está presente
$sessionCookieName = $sessionConfig['cookie'];
$hasSessionCookie = isset($cookies[$sessionCookieName]);

// Exibir informações
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Sessão</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .success { color: green; }
        .error { color: red; }
        .info { color: #333; }
    </style>
</head>
<body>
    <h1>Informações de Sessão</h1>
    
    <h2>Configurações de Sessão</h2>
    <pre><?= e(print_r($sessionConfig, true)) ?></pre>
    
    <p><strong>Sessão Iniciada:</strong> <?= $sessionStarted ? '<span class="success">Sim</span>' : '<span class="error">Não</span>' ?></p>
    
    <h2>Cookies</h2>
    <pre><?= e(print_r($cookies, true)) ?></pre>
    
    <p><strong>Cookie de Sessão (<?= e($sessionCookieName) ?>) Presente:</strong> 
        <?= $hasSessionCookie ? '<span class="success">Sim</span>' : '<span class="error">Não</span>' ?>
    </p>
    
    <h2>Configurações de Cookie</h2>
    <?php
    $cookieParams = session_get_cookie_params();
    ?>
    <pre><?= e(print_r($cookieParams, true)) ?></pre>
    
    <h2>Cabeçalhos de Resposta</h2>
    <?php
    $headers = headers_list();
    ?>
    <pre><?= e(print_r($headers, true)) ?></pre>
    
    <h2>Teste de Sessão</h2>
    <?php
    // Tentar iniciar uma sessão manualmente
    try {
        if (session_start()) {
            $_SESSION['test_key'] = 'test_value';
            echo '<p class="success">Sessão iniciada com sucesso! ID da sessão: ' . session_id() . '</p>';
            echo '<p>Valor de teste armazenado na sessão: ' . ($_SESSION['test_key'] ?? 'não definido') . '</p>';
            
            // Verificar se o cookie de sessão foi definido
            if (isset($_COOKIE[$sessionCookieName])) {
                echo '<p class="success">Cookie de sessão encontrado: ' . e($_COOKIE[$sessionCookieName]) . '</p>';
            } else {
                echo '<p class="error">Cookie de sessão não encontrado.</p>';
            }
        } else {
            echo '<p class="error">Falha ao iniciar a sessão.</p>';
        }
    } catch (Exception $e) {
        echo '<p class="error">Erro ao manipular a sessão: ' . e($e->getMessage()) . '</p>';
    }
    ?>
    
    <h2>Informações do Diretório de Sessão</h2>
    <?php
    $sessionPath = $sessionConfig['files'];
    $isWritable = is_writable($sessionPath);
    $permissions = substr(sprintf('%o', fileperms($sessionPath)), -4);
    
    echo '<p><strong>Diretório:</strong> ' . e($sessionPath) . '</p>';
    echo '<p><strong>Gravável:</strong> ' . ($isWritable ? '<span class="success">Sim</span>' : '<span class="error">Não</span>') . '</p>';
    echo '<p><strong>Permissões:</strong> ' . e($permissions) . '</p>';
    
    // Listar arquivos no diretório de sessão
    echo '<h3>Arquivos no Diretório de Sessão</h3>';
    try {
        $files = scandir($sessionPath);
        if ($files === false) {
            throw new Exception("Não foi possível listar o diretório de sessão.");
        }
        echo '<pre>' . e(print_r($files, true)) . '</pre>';
    } catch (Exception $e) {
        echo '<p class="error">Erro ao listar arquivos de sessão: ' . e($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>
<?php
// Enviar a resposta
$response->send();
$kernel->terminate($request, $response);
