<?php
    session_start();

    if (!isset($_SESSION['usuario']['id'])) {
        // Se o usuário não estiver autenticado, será redirecionado para a página de login
        header('Location: logar.php');
        exit();
    }
    else{
        require("conexao.php");

        $conexaoClass = new Conexao();
        $conexao = $conexaoClass->conectar();
        $conexao->exec("SET NAMES utf8");

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            if (isset($_POST['apagarComentario'])) {
                // Certificação de que o usuário está logado seja o proprietário da mensagem enviada
                $idMensagem = $_POST['idMensagem'];
                
                $idUsuario = $_SESSION["usuario"]["id"];
                try {
                    $pdo = new PDO("mysql:host=127.0.0.1;dbname=OSLearn", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Erro na conexão com o banco de dados: " . $e->getMessage());
                }
        
                // Verificação se a mensagem pertence ao usuário logado antes de apagar
                $query = $conexao->prepare("DELETE FROM mensagens WHERE id=:idMensagem AND id_usuario=:idUsuario");
                $query->bindParam(':idMensagem', $idMensagem, PDO::PARAM_INT);
                $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $query->execute();
            }

            if(isset($_POST['mensagem'])){
                $mensagem = $_POST['mensagem'];
                $idUsuario = $_SESSION["usuario"]["id"];

                try {
                    $pdo = new PDO("mysql:host=127.0.0.1;dbname=OSLearn", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Erro na conexão com o banco de dados: " . $e->getMessage());
                }

                $query = $conexao->prepare("INSERT INTO mensagens (id_usuario, mensagem, data_envio) VALUES (:idUsuario, :mensagem, NOW())");
                $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $query->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);
                $query->execute();
            }
        }

        // Consulta ao banco de dados para obter todas as mensagens de todos os usuários com seus nomes
        $query = $conexao->prepare("SELECT mensagens.id, mensagens.mensagem, mensagens.data_envio, mensagens.id_usuario, usuarios.nome AS usuario_nome FROM mensagens INNER JOIN usuarios ON mensagens.id_usuario = usuarios.id");
        $query->execute();
        $mensagens = $query->fetchAll(PDO::FETCH_ASSOC);
    }

?>
<!-- user_dashboard.php - Exibição da lógica -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/feedback.css"> 
    <title>Painel do Usuário</title>
</head>
<body>
<div id="menu">
        <ul>
            <li><a href="../user_dashboard.php">OSLearn</a></li>
            <li><a href="trilha.php">TRILHA DE ESTUDOS</a></li>
            <li><a href="ranking.php">RANKING</a></li>
            <li><a href="feedback.php">FEEDBACK</a></li>
            <li><a href="conta.php">CONTA</a></li>
            <li><a href="../logout.php" id="sair">SAIR</a></li> 
        </ul>
    </div>
    <div id="conteudo">
        <div id="header">
            <h1>Comentários</h1>
            <p>Aqui você pode visualizar alguns comentários sobre a plataforma e também pode enviar um comentário para a gente!</p>
        </div>
        <table>
            <?php foreach ($mensagens as $mensagem) : ?>
                <tr>
                    <td>
                        <div class="comment">
                            <div class="user-icon"><?= $mensagem['usuario_nome'] ?></div>
                                <div class="user-info">
                                    <h3><?= $mensagem['usuario_nome'] ?></h3>
                                    <p><?= $mensagem['mensagem'] ?></p>
                                    <p>Enviado em: <?= $mensagem['data_envio'] ?></p>
                                    <?php
                                    // Verificação para saber se o usuário logado é o proprietário da mensagem
                                    if ($_SESSION['usuario']['id'] == $mensagem['id_usuario']) {
                                        echo '<form action="" method="POST">';
                                        echo '<input type="hidden" name="idMensagem" value="' . $mensagem['id'] . '">';
                                        echo '<input type="submit" value="Apagar Comentário" name="apagarComentario">';
                                        echo '</form>';
                                        }
                                    ?>
                                </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div id="header">
            <h1>Envie seu comentário</h1>
            <p>Aqui você pode enviar seus comentários sobre a plataforma! Nos ajude contando sua experiência ao utilizar a plataforma e aquilo que podemos melhorar.</p>
        </div>
        <div id="enviarComentarioContainer">
            <div id="comentarioFormContainer">
                <form action="" method="POST">
                    <div id="comentarioInput">
                    <textarea type="text" name="mensagem"><?= isset($_POST['mensagem']) ? htmlspecialchars($_POST['mensagem'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
                    </div>
            </div>
                    <div id="enviarButton">
                    <input type="submit" value="Enviar Comentário" name="enviarComentario">
                    </div>
                </form>
        </div>
    </div>
</body>
</html>