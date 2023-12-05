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
    <link rel="stylesheet" href="../CSS/lista.css">
    <title>Lista de Perguntas Ativas - OSLearn</title>
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
                    <th colspan="6"><h1>Perguntas Ativas</h1></th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Pergunta</th>
                    <th>Data de Publicação</th>
                    <th>Módulo</th>
                    <th>Imagem</th>
                    <th>Atualizar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($perguntas as $pergunta): ?>
                    <tr>
                        <td><strong><?php echo $pergunta['id']; ?></strong></td>
                        <td><?php echo $pergunta['pergunta']; ?></td>
                        <td><?php echo $pergunta['data_publicacao']; ?></td>
                        <td><?php echo $pergunta['modulo']; ?></td>
                        <td>
                            <?php if (!empty($pergunta['imagem'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($pergunta['imagem']); ?>" alt="Imagem da pergunta" style="max-width: 100px;">
                            <?php else: ?>
                                <em>Sem imagem</em>
                            <?php endif; ?>
                        </td>
                        <td><div id="botaoeditar"><a href="edicaoPerguntas.php?id=<?php echo $pergunta['id']; ?>">Atualizar</a></div></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
