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
$query = $pdo->prepare("SELECT id, pergunta, data_publicacao, modulo, imagem FROM perguntas WHERE ativo = 1 OR ativo = 0");
$query->execute();
$perguntas = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas Ativas - OSLearn</title>
</head>
<body>
    <h1>Perguntas Ativas</h1>
    <ul>
        <?php foreach ($perguntas as $pergunta): ?>
            <li>
                <strong>ID: <?php echo $pergunta['id']; ?></strong><br>
                Pergunta: <?php echo $pergunta['pergunta']; ?><br>
                Data de Publicação: <?php echo $pergunta['data_publicacao']; ?><br>
                Módulo: <?php echo $pergunta['modulo']; ?><br>
                <?php if (!empty($pergunta['imagem'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($pergunta['imagem']); ?>" alt="Imagem da pergunta" style="max-width: 200px;"><br>
                <?php else: ?>
                    <em>Sem imagem</em><br>
                <?php endif; ?>
                <a href="edicaoPerguntas.php?id=<?php echo $pergunta['id']; ?>">Atualizar</a>
            </li>
            <br>
        <?php endforeach; ?>
    </ul>
</body>
</html>
