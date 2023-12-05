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
    <link rel="stylesheet" href="../CSS/lista.css">
    <title>Lista de Alternativas - OSLearn</title>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="admin_dashboard.php">OSLearn (ADM)💻</a></li>
            <li><a href="usuarios_cadastrados.php">LISTA DE USUÁRIOS 📄</a></li>
            <li><a href="gerenciar.php">GERENCIAR MÓDULOS 🔩</a></li>
            <li><a href="../logout.php" id="sair">SAIR 🔚</a></li> 
        </ul>
    </div>
    <div class="conteudo">
        <div class="header">
            <?php
                echo '<a href="../admin/gerenciamento_alternativas.php" class="menu-button">Voltar</a>';
            ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th colspan="5"><h1>Editar Alternativas</h1></th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>ID da Pergunta</th>
                    <th>Alternativa</th>
                    <th>Correta ou Incorreta</th>
                    <th>Atualizar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alternativas as $alternativa): ?>
                    <tr>
                        <td><strong><?php echo $alternativa['id']; ?></strong></td>
                        <td><?php echo $alternativa['id_pergunta']; ?></td>
                        <td><?php echo $alternativa['texto_alternativa']; ?></td>
                        <td><?php echo $alternativa['correta']; ?></td>
                        <td><div id="botaoeditar"><a href="edicaoAlternativas.php?id=<?php echo $alternativa['id']; ?>">Atualizar</a></div></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
