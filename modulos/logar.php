<!-- logar.php - Página destinada a exibição do formulário de login -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/logar.css">
    <title>Página de login</title>
</head>
<body>
    <div class="logarphp">
        <h1>Olá! Seja Bem-vindo!</h1>

        <div id="formulario">
            <form method="POST" action="login.php">
                <div class="form">
                    <div id="linha"><input type="email" name="email"  placeholder="Digite seu Email"/></div>
                    <div id="linha"><input type="password" name="senha" placeholder="Senha" /></div>
                    <button type="submit" id="btnCadastro">Entrar</button>
                </div>
            </form>
            <div id="textoCadastro"> 
                <br>
                <span class="title">Não possui uma conta?</span>
                <span class="subtitle">Crie uma conta!</span>
                <form action="cadastrar.php" method="post">
                    <button type="submit" class="botao-cadastrar">Cadastrar</button>
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
