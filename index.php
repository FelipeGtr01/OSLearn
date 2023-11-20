<?php
    #Validação para saber se o usuário está logado.
    //Iniciando a sessão
    session_start();

    if (isset($_SESSION["usuario"]) && is_array($_SESSION["usuario"])) {
        echo "<script>window.location = 'index.php'</script>";
    }     
?>

<!-- index.php - Exibição da tela inicial do sistema -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Página inicial</title>
</head>
<body>
    <h3><a href="http://localhost/OSLearn/">Home</a></h3> <!-- Verificar se isso ainda vai ficar funcionando ou não -->
    <div class="conteudo">
        <div class="titulo-imagem">
            <div class="titulo">
                <h1>OSLearn,</h1>
                <h1>Seja Bem-vindo!</h1>
            </div>
            <div class="imagem-inicio">
                <img src="assets/img/inicio.jpg" alt="">
            </div>
        </div>
        <div class="botoes">
            <form action="modulos/cadastrar.php" method="post">
                <button type="submit" class="botao-cadastrar">Crie uma conta</button>
            </form>
            <form action="modulos/logar.php" method="post">
                <button type="submit" class="botao-logar">Já tenho uma conta</button>
            </form>
        </div>
    </div>
</body>
</html>
