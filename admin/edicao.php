<?php
    session_start();

    if (!isset($_SESSION['usuario']['id'])) {
        // Se não houver uma sessão válida ou o usuário não for um administrador, será redirecionado para o login.
        header('Location: logar.php');
        exit();
    }

    require("../modulos/conexao.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $idUsuario = $_GET["id"]; // Obtenção do ID do usuário a ser editado a partir da URL

        try {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }

        // Consulta para obter os dados do usuário com base no ID
        $query = $pdo->prepare("SELECT nome, email, senha FROM usuarios WHERE id=:idUsuario");
        $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            $user = $query->fetch(PDO::FETCH_ASSOC);
            $nome = $user['nome'];
            $email = $user['email'];
            $senha = $user['senha'];
            $tipoConta = isset($user["adm"]) ? $user["adm"] : 0; // Definindo o tipo de conta
        } else {
            header('Location: ../admin_dashboard.php');
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['atualizar'])) {
            $idUsuario = $_POST["id_usuario"]; // Obtenção do ID do usuário a ser atualizado

            // Recuperação dos dados do formulário
            $novoNome = $_POST["nome_completo"];
            $novoEmail = $_POST["email"];
            $novaSenha = $_POST["senha"];

            try {
                $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }

            // Recuperação do valor do campo de seleção (admin ou usuário comum)
            $tipoConta = isset($_POST["tipo_conta"]) ? $_POST["tipo_conta"] : 0; // Se não estiver definido, assume como usuário comum

            // Atualização dos dados no banco de dados
            $query = $pdo->prepare("UPDATE usuarios SET nome=:novoNome, email=:novoEmail, senha=:novaSenha, adm=:tipoConta WHERE id=:idUsuario");
            $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $query->bindParam(':novoNome', $novoNome, PDO::PARAM_STR);
            $query->bindParam(':novoEmail', $novoEmail, PDO::PARAM_STR);
            $query->bindParam(':novaSenha', $novaSenha, PDO::PARAM_STR);
            $query->bindParam(':tipoConta', $tipoConta, PDO::PARAM_INT);

            if ($query->execute()) {
                // A atualização foi bem-sucedida, será feito o redirecionamento ou será exibido uma mensagem de sucesso
                header('Location: usuarios_cadastrados.php');
                exit();
            } else {
                // A atualização falhou, será exibido uma mensagem de erro
                echo "Erro ao atualizar os dados no banco de dados.";
            }
        }
    }
?>

<!-- edicao.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/edicao.css"> 
    <title>Edição - OSLearn</title>
</head>
<body>
    <div id="content-container">
        <div id="conteudo">
            <!-- Conteúdo específico para administradores -->
                <div id="header">
                    <a href="../admin/usuarios_cadastrados.php" class="menu-button">Sair sem alterar</a>
                    <h1>Configurações</h1>
                    <div class="user-icon">F</div>
                    <a href="#" id="mudar">Mudar Foto do Perfil</a> <!-- Talvez seja desenvolvido -->
                </div>
                <br><br>
                <div class="informacoes">
                    <form action="" method="post">
                        <input type="hidden" name="id_usuario" value="<?php echo $idUsuario; ?>">

                        <label>NOME:</label>
                        <input type="text" name="nome_completo" placeholder="nome" value="<?php echo $nome; ?>"><br><br>

                        <label>EMAIL:</label>
                        <input type="email" name="email" placeholder="email" value="<?php echo $email; ?>"><br><br>

                        <label>SENHA:</label>
                        <input type="text" name="senha"  id="senha-input" placeholder="senha" value="<?php echo $senha; ?>">
                        
                        <br><br>
                        <label>Tipo de Conta:</label>
                        <select name="tipo_conta">
                            <option value="0" <?php echo ($tipoConta == 0) ? 'selected' : ''; ?>>Usuário Comum</option>
                            <option value="1" <?php echo ($tipoConta == 1) ? 'selected' : ''; ?>>Administrador</option>
                        </select><br><br>

                        <br><br>

                        
                        <input type="submit" value="Salvar Alterações" name="atualizar">
                    </form>
                </div>
        </div>
    </div>
</body>
</html>
