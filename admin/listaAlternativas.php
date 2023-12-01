<?php
session_start();

if (!isset($_SESSION['usuario']['id'])) {
    // Se o usuário não estiver autenticado, será redirecionado para a página de login
    header('Location: logar.php');
    exit();
} 

require("../modulos/conexao.php");

function conectarAoBanco() {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

$pdo = conectarAoBanco();
$query = $pdo->prepare("SELECT id, id_pergunta, texto_alternativa, correta FROM alternativas WHERE correta = 1 OR correta = 0");
$query->execute();
$alternativas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alternativas - OSLearn</title>
</head>
<body>
    <h1>Alternativas</h1>
    <ul>
        <?php foreach ($alternativas as $alternativa): ?>
            <li>
                <strong>ID: <?php echo $alternativa['id']; ?></strong><br>
                ID da Pergunta: <?php echo $alternativa['id_pergunta']; ?><br>
                Alternativa: <?php echo $alternativa['texto_alternativa']; ?><br>
                Correta ou Incorreta: <?php echo $alternativa['correta']; ?><br>
                <a href="edicaoAlternativas.php?id=<?php echo $alternativa['id']; ?>">Atualizar</a>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
</body>
</html>
