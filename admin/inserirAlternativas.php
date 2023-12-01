<?php
session_start();

if (!isset($_SESSION['usuario']['id'])) {
    // Se o usuário não estiver autenticado, será redirecionado para a página de login
    header('Location: logar.php');
    exit();
} else {
    require("../modulos/conexao.php");

    $conexaoClass = new Conexao();
    $conexao = $conexaoClass->conectar();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['inserir'])) {
            $idUsuario = $_SESSION["usuario"]["id"];

            // Recuperação dos dados do formulário
            $idPergunta = $_POST['id_pergunta'];
            $textoAlternativa = $_POST['texto_alternativa'];
            $correta = $_POST['correta'];

            try {
                $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }

            // Atualização dos dados no banco de dados
            $query = $pdo->prepare("INSERT INTO alternativas(id_pergunta, texto_alternativa, correta) VALUES (:idPergunta, :textoAlternativa, :correta)");
            $query->bindParam(':idPergunta', $idPergunta, PDO::PARAM_INT);
            $query->bindParam(':textoAlternativa', $textoAlternativa, PDO::PARAM_STR);
            $query->bindParam(':correta', $correta, PDO::PARAM_INT);

            if ($query->execute()) {
                // A atualização foi bem-sucedida, redirecione ou exiba uma mensagem de sucesso
                header('Location: admin_dashboard.php');
                exit();
            } else {
                // Se por acaso a atualização falhar, será exiba uma mensagem de erro
                echo "Erro ao inserir a nova alternativa no banco de dados.";
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
    <div id="content-container">
        <div id="conteudo">
            <br><br>
            <div class="informacoes">
                <form action="" method="post" enctype="multipart/form-data">
                    <label>ID DA PERGUNTA:</label>
                    <input type="text" name="id_pergunta" placeholder="id da pergunta" ><br><br>
                    <br><br>
                    <label>NOVA ALTERNATIVA:</label>
                    <input type="text" name="texto_alternativa" placeholder="nova alternativa" ><br><br>
                    <br><br>
                    <label>CORRETA:</label>
                    <input type="text" name="correta" placeholder="correta" ><br><br>
                    <br><br>

                    <input type="hidden" name="id_cliente">
                    <input type="submit" value="Inserir" name="inserir">
                </form>
            </div>
        </div>
    </div>
</body>
</html>