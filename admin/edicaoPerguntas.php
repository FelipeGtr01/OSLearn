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
// Verificação se foi enviado um ID de pergunta pela URL
if (isset($_GET['id'])) {
    $idPergunta = $_GET['id'];
    // Consulta ao banco de dados para obter os detalhes da pergunta com o ID fornecido
    $pdo = conectarAoBanco();
    $query = $pdo->prepare("SELECT id, pergunta, data_publicacao, modulo, imagem FROM perguntas WHERE id = :idPergunta");
    $query->bindParam(':idPergunta', $idPergunta, PDO::PARAM_INT);
    $query->execute();
    $pergunta = $query->fetch(PDO::FETCH_ASSOC);

    if ($pergunta) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar'])) {
            // Lógica para atualizar a pergunta com os dados recebidos do formulário
            $novaPergunta = $_POST["novaPergunta"];
            $novaData = $_POST["novaData"];
            $modulo = $_POST["modulo"];
            
            // Se uma nova imagem foi enviada, processar e atualizar
            if (isset($_FILES["novaImagem"]) && !empty($_FILES["novaImagem"]["tmp_name"]) && file_exists($_FILES["novaImagem"]["tmp_name"])) {
                $caminho_temporario = $_FILES["novaImagem"]["tmp_name"];
                $conteudo_imagem = file_get_contents($caminho_temporario);
                
                $query = $pdo->prepare("UPDATE perguntas SET pergunta=:novaPergunta, data_publicacao=:novaData, modulo=:modulo, imagem=:imagem WHERE id=:idPergunta");
                $query->bindParam(':imagem', $conteudo_imagem, PDO::PARAM_LOB);
            } else {
                // Se nenhuma nova imagem foi enviada
                $query = $pdo->prepare("UPDATE perguntas SET pergunta=:novaPergunta, data_publicacao=:novaData, modulo=:modulo WHERE id=:idPergunta");
            }
            
            $query->bindParam(':idPergunta', $idPergunta, PDO::PARAM_INT);
            $query->bindParam(':novaPergunta', $novaPergunta, PDO::PARAM_STR);
            $query->bindParam(':novaData', $novaData, PDO::PARAM_STR);
            $query->bindParam(':modulo', $modulo, PDO::PARAM_INT);

            if ($query->execute()) {
                header('Location: listaPerguntas.php');
                exit();
            } else {
                echo "Erro ao atualizar a pergunta.";
            }
        }
        ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/inserir.css">
    <title>Editar Pergunta - OSLearn</title>
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
            <div id="content-container">
                <div id="conteudo">
                    <h1>Editar Pergunta</h1>
                    <div class="informacoes">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="idPergunta" value="<?php echo $pergunta['id']; ?>">
                            <label>Pergunta:</label><br>
                            <input type="text" name="novaPergunta" value="<?php echo $pergunta['pergunta']; ?>"><br><br>
                            <label>Data de Publicação:</label><br>
                            <input type="datetime-local" name="novaData" value="<?php echo $pergunta['data_publicacao']; ?>"><br><br>
                            <label>Módulo:</label><br>
                            <input type="number" name="modulo" value="<?php echo $pergunta['modulo']; ?>"><br><br>
                            <?php if (!empty($pergunta['imagem'])): ?>
                                <label>Imagem atual:</label><br>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($pergunta['imagem']); ?>" alt="Imagem da pergunta" style="max-width: 200px;"><br>
                            <?php else: ?>
                                <em>Sem imagem</em><br>
                            <?php endif; ?>
                            <label>Nova Imagem:</label><br>
                            <input type="file" name="novaImagem"><br><br>
                            <input type="submit" value="Atualizar Pergunta" name="atualizar">
                        </form>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Pergunta não encontrada.";
    }
} else {
    echo "ID da pergunta não fornecido.";
}
?>
