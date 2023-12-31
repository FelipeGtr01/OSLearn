<?php
    // Verificação para saber se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexão com o banco de dados
    require("conexao.php");

    $conexaoClass = new Conexao();
    $conexao = $conexaoClass->conectar();

    // Recuperação dos dados do formulário
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $senha_confirmacao = $_POST["senha_confirmacao"];

    // Verificação para saber se as senhas correspondem
    if ($senha === $senha_confirmacao) {
        // Se as senhas correspondem, inserir no banco de dados
        // Execução de uma consulta SQL para inserir o novo usuário
        $query = $conexao->prepare("INSERT INTO usuarios (nome, email, senha, adm) VALUES (?, ?, ?, 0)");
        $query->execute([$nome, $email, $senha]);

        // Verificação para saber se a inserção foi bem-sucedida
        if ($query->rowCount() > 0) {
            // Redirecionamento para a página de login 
            header("Location: logar.php");
            exit;
        } else {
            // Tratamento de erro caso o usuário não tenha sido cadastrado
            echo "Erro ao cadastrar o usuário.";
        }
    } else {
        // Mensagem de erro caso as senhas não correspondam
        header("Location: cadastrar.php?erro=credenciais_incorretas");
        exit(); // Certifique-se de sair do script para evitar execução adicional
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
</head>
<body>
    <!-- Seu formulário HTML com os campos preenchidos -->
    <form method="post" action="modulos/cadastrar.php">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo isset($nome) ? $nome : ''; ?>">
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
        <br>

        <label for="senha">Senha:</label>
        <input type="password" name="senha">
        <br>

        <label for="senha_confirmacao">Confirme a senha:</label>
        <input type="password" name="senha_confirmacao">
        <br>

        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
