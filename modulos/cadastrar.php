<!-- cadastrar.php - Página destinada a exibição do formulário para que novos usuários se cadastrem -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/logar.css"> 
    <title>Cadastro de Usuário</title>
</head>
<body>
    <div class="logarphp">
        <h1>OSLearn, Seja Bem-Vindo!</h1>
        <div id="formulario">
            <form method="POST" action="cadastrar_process.php">
                <div id="linha">
                    <input type="text" name="nome" placeholder="Nome" required>
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                    <input type="password" name="senha_confirmacao" placeholder="Confirme a Senha" required>
                </div>
                <button type="submit" id="btnCadastro">Cadastrar</button>
            </form>
            <div id="textoCadastro"> 
                <br>
                <span class="title">Já possui uma conta?</span>
                <span class="subtitle">Faça login!</span>
                <form action="logar.php" method="post">
                    <button type="submit" class="botao-cadastrar">Logar</button>
                </form>
                <br><br>
                <div id="botaoSairContainer"> 
                    <a id="botaoSair" href="../index.php">SAIR</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
