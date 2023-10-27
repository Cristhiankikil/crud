<?php
// Captura os dados enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];

    try {
        $host = "localhost";
        $database = "cedup";
        $username = "root";
        $password = ""; // Defina sua senha aqui

        $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $conn = new PDO($dsn, $username, $password, $options);

        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Registro Excluído.";
        } else {
            echo "Erro: " . $stmt->errorInfo()[2];
            http_response_code(400);
        }

        $conn = null;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
} else {
    http_response_code(400);
    echo "Método inválido para a solicitação.";
}
?>
