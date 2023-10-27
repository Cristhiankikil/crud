<?php
// Conecte-se ao banco de dados (substitua com as suas configurações)
$host = "localhost";
$database = "cedup";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_POST['id'];
    $novoNome = $_POST['nome'];
    $novoUsuario = $_POST['usuario'];
    $novaSenha = $_POST['senha'];
    $cep = $_POST['cep'];

    // Atualize os dados do usuário no banco de dados
    $sql = "UPDATE usuarios SET nome = :nome, login = :login, senha = :senha, cep = :cep, localidade = :localidade, uf = :uf WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nome', $novoNome);
    $stmt->bindParam(':login', $novoUsuario);
    $stmt->bindParam(':senha', $novaSenha);

    // Aqui, você precisa verificar se o CEP não está vazio antes de realizar a consulta
    if (!empty($cep)) {
        $endereco = get_endereco($cep);
        $cep1 = $endereco->cep;
        $localidade = $endereco->localidade;
        $uf = $endereco->uf;
        $stmt->bindParam(':cep', $cep1);
        $stmt->bindParam(':localidade', $localidade);
        $stmt->bindParam(':uf', $uf);
    } else {
        $stmt->bindParam(':cep', null, PDO::PARAM_NULL);
        $stmt->bindParam(':localidade', null, PDO::PARAM_NULL);
        $stmt->bindParam(':uf', null, PDO::PARAM_NULL);
    }

    if ($stmt->execute()) {
        // Se a atualização for bem-sucedida, envie uma resposta de sucesso
        echo 'success';
    } else {
        // Se ocorrer um erro na atualização, envie uma resposta de erro
        echo 'error';
    }
} catch (PDOException $e) {
    // Em caso de erro na conexão com o banco de dados
    echo 'error';
}

function get_endereco($cep) {
    $cep = preg_replace("/[^0-9]/", "", $cep);
    $url = "http://viacep.com.br/ws/$cep/xml/";
    $xml = simplexml_load_file($url);
    return $xml;
}
?>
