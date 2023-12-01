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
            $novaPergunta = $_POST['nova_pergunta'];
            $ativo = $_POST['ativo'];
            $novaData = $_POST['data_publicacao'];
            $modulo = $_POST['modulo'];

            // Caminho temporário do arquivo
            $caminho_temporario = $_FILES["imagem"]["tmp_name"];

            try {
                $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }

            // Leitura do arquivo em binário
            $conteudo_imagem = file_get_contents($caminho_temporario);

            // Atualização dos dados no banco de dados
            $query = $pdo->prepare("INSERT INTO perguntas(pergunta, data_publicacao, ativo, imagem, modulo) VALUES (:novaPergunta, :dataPublicacao, :ativo, :novaImagem, :modulo)");
            $query->bindParam(':novaPergunta', $novaPergunta, PDO::PARAM_STR);
            $query->bindParam(':dataPublicacao', $novaData, PDO::PARAM_STR);
            $query->bindParam(':ativo', $ativo, PDO::PARAM_INT);
            $query->bindParam(':novaImagem', $conteudo_imagem, PDO::PARAM_LOB);
            $query->bindParam(':modulo', $modulo, PDO::PARAM_STR);

            if ($query->execute()) {
                // A atualização foi bem-sucedida, redirecione ou exiba uma mensagem de sucesso
                header('Location: admin_dashboard.php');
                exit();
            } else {
                // Se por acaso a atualização falhar, será exiba uma mensagem de erro
                echo "Erro ao inserir a nova pergunta no banco de dados.";
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
                    <label>NOVA PERGUNTA:</label>
                    <input type="text" name="nova_pergunta" placeholder="pergunta" ><br><br>
                    <br><br>
                    <label for="data">SELECIONE UMA DATA:</label>
                    <input type="datetime-local" id="data" name="data_publicacao"><br><br>
                    <br><br>
                    <label>ATIVO:</label>
                    <input type="text" name="ativo" placeholder="ativo" ><br><br>
                    <br><br>
                    <label for="imagem">SELECIONE UMA IMAGEM:</label>
                    <input type="file" id="imagem" name="imagem"><br><br>
                    <br><br>
                    <label>MODULO:</label>
                    <input type="text" name="modulo" placeholder="modulo" ><br><br>
                    <br><br>

                    <input type="hidden" name="id_cliente">
                    <input type="submit" value="Inserir" name="inserir">
                </form>
            </div>
        </div>
    </div>
</body>
</html>