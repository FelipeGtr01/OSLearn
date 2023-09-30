<?php
    session_start();

    if (!isset($_SESSION['usuario']['id'])) {
        // Se o usuário não estiver autenticado, será redirecionado para a página de login
        header('Location: logar.php');
        exit();
    }
    else {
        require("conexao.php");

        $conexaoClass = new Conexao();
        $conexao = $conexaoClass->conectar();

        $nome = "";
        $email = "";
        $senha = "";

        $idUsuario = $_SESSION["usuario"]["id"];

        try {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=OSLearn", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }

        $query = $pdo->prepare("SELECT nome, email, senha FROM usuarios WHERE id=:idUsuario");
        $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            $user = $query->fetch(PDO::FETCH_ASSOC);
            $nome = $user['nome'];
            $email = $user['email'];
            $senha = $user['senha'];
        } else {
            header('Location: ../user_dashboard.php');
            exit();
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['atualizar'])) {
                    $idUsuario = $_SESSION["usuario"]["id"];

                    // Recuperação dos dados do formulário
                    $novoNome = $_POST['nome_completo'];
                    $novoEmail = $_POST['email'];
                    $novaSenha = $_POST['senha'];

                    try {
                        $pdo = new PDO("mysql:host=127.0.0.1;dbname=OSLearn", "root", "");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (PDOException $e) {
                        die("Erro na conexão com o banco de dados: " . $e->getMessage());
                    }

                    // Atualização dos dados no banco de dados
                    $query = $pdo->prepare("UPDATE usuarios SET nome=:novoNome, email=:novoEmail, senha=:novaSenha WHERE id=:idUsuario");
                    $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                    $query->bindParam(':novoNome', $novoNome, PDO::PARAM_STR);
                    $query->bindParam(':novoEmail', $novoEmail, PDO::PARAM_STR);
                    $query->bindParam(':novaSenha', $novaSenha, PDO::PARAM_STR);

                    if ($query->execute()) {
                        // A atualização foi bem-sucedida, redirecionamento para a tela principal do sistema.
                        header('Location: ../user_dashboard.php');
                        exit();
                    } else {
                        // Se por acaso a atualização falhar, será exiba uma mensagem de erro
                        echo "Erro ao atualizar os dados no banco de dados.";
                    }
                }
            }
        }
?>

<!-- conta.php - Exibição da lógica -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/conta.css"> 
    <title>Conta - OSLearn</title>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="../user_dashboard.php">OSLearn</a></li>
            <li><a href="trilha.php">TRILHA DE ESTUDOS</a></li>
            <li><a href="ranking.php">RANKING</a></li>
            <li><a href="feedback.php">FEEDBACK</a></li>
            <li><a href="conta.php">CONTA</a></li>
            <li><a href="../logout.php" id="sair" >SAIR</a></li> 
        </ul>
    </div>
    <div id="content-container">
        <div id="conteudo">
            <div id="header">
                <h1>Configurações</h1>
                <div class="user-icon">F</div>
                <a href="#" id="mudar">Mudar Foto do Perfil</a> <!-- Talvez seja desenvolvido -->
            </div>
            <br><br>
            <div class="informacoes">
                <form action="" method="post">
                    <label>Nome:</label><br>
                    <input type="text" name="nome_completo" placeholder="nome" value="<?php echo $nome; ?>"><br><br>

                    <label>E-mail:</label><br>
                    <input type="email" name="email" placeholder="email" value="<?php echo $email; ?>"><br><br>

                    <label>Senha:</label><br>
                    <input type="password" name="senha" placeholder="senha" value="<?php echo $senha; ?>"><br><br>

                    <input type="hidden" name="id_cliente">
                    <input type="submit" value="Salvar Alterações" name="atualizar">
                </form>
            </div>
        </div>
    </div>
</body>
</html>