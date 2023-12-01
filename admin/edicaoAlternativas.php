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
    $idAlternativa = $_GET['id'];
    // Consulta ao banco de dados para obter os detalhes da pergunta com o ID fornecido
    $pdo = conectarAoBanco();
    $query = $pdo->prepare("SELECT id, id_pergunta, texto_alternativa, correta FROM alternativas WHERE id = :idAlternativa");
    $query->bindParam(':idAlternativa', $idAlternativa, PDO::PARAM_INT);
    $query->execute();
    $alternativa = $query->fetch(PDO::FETCH_ASSOC);

    if ($alternativa) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar'])) {
            // Lógica para atualizar a pergunta com os dados recebidos do formulário
            $idPergunta = $_POST["idPergunta"];
            $novaAlternativa = $_POST["novaAlternativa"];
            $correta = $_POST["correta"];
            
            $query = $pdo->prepare("UPDATE alternativas SET id_pergunta=:idPergunta, texto_alternativa=:novaAlternativa, correta=:correta WHERE id=:idAlternativa");
            
            $query->bindParam(':idAlternativa', $idAlternativa, PDO::PARAM_INT);
            $query->bindParam(':idPergunta', $idPergunta, PDO::PARAM_INT);
            $query->bindParam(':novaAlternativa', $novaAlternativa, PDO::PARAM_STR);
            $query->bindParam(':correta', $correta, PDO::PARAM_INT);

            if ($query->execute()) {
                header('Location: listaAlternativas.php');
                exit();
            } else {
                echo "Erro ao atualizar a alternativa.";
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>Editar Alternativa - OSLearn</title>
        </head>
        <body>
            <h1>Editar Alternativa</h1>
            <form action="" method="post" enctype="multipart/form-data">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="idAlternativa" value="<?php echo $alternativa['id']; ?>">
                
                <label>ID da Pergunta:</label><br>
                <input type="number" name="idPergunta" value="<?php echo $alternativa['id_pergunta']; ?>"><br><br>

                <label>Alternativa:</label><br>
                <input type="text" name="novaAlternativa" value="<?php echo $alternativa['texto_alternativa']; ?>"><br><br>

                <label>Correta ou Incorreta:</label><br>
                <input type="number" name="correta" value="<?php echo $alternativa['correta']; ?>"><br><br>

                <input type="submit" value="Atualizar Pergunta" name="atualizar">
            </form>
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Alternativa não encontrada.";
    }
} else {
    echo "ID da alternativa não fornecido.";
}
?>
