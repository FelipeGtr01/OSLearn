<!-- trilha.php - Exibição dos módulos de estudos -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/trilha.css"> 
    <title>Painel do Usuário</title>
</head>
<body>
    <div id="menu">
        <ul>
            <li><a href="../user_dashboard.php">OSLearn 💻</a></li>
            <li><a href="trilha.php">TRILHA DE ESTUDOS 📚</a></li>
            <li><a href="ranking.php">RANKING 📊</a></li>
            <li><a href="feedback.php">FEEDBACK ✍</a></li>
            <li><a href="conta.php">CONTA 🔧</a></li>
            <li><a href="../logout.php" id="sair">SAIR 🔚</a></li> 
        </ul>
    </div>
    <div id="conteudo">
        <!-- Módulo 1 -->
        <div class="modulo">
            <img id="imagem" src="../assets/img/SistemasOperacionais2.jpg" alt="Trilha de Estudos 1">
            <div class="textoModulo">
                <h2>Módulo 1</h2>
                <p>Aqui você poderá ter uma introdução ao assunto de sistemas operacionais e poderá treinar conceitos básicos que a matéria exige</p>
                <br>
                <a href="../perguntas/quiz.php?modulo=1" class="botao-entrar">Iniciar Módulo</a>
            </div>
        </div>

        <!-- Módulo 2 -->
        <div class="modulo">
            <img id="imagem" src="../assets/img/SistemasOperacionais6.jpg" alt="Trilha de Estudos 2">
            <div class="textoModulo">
                <h2>Módulo 2</h2>
                <p>Aqui você poderá ter uma introdução ao assunto de sistemas operacionais e poderá treinar conceitos básicos que a matéria exige</p>
                <br>
                <a href="../perguntas/quiz.php?modulo=2" class="botao-entrar">Iniciar Módulo</a>
            </div>
        </div>

        <!-- Módulo 3 -->
        <div class="modulo">
            <img id="imagem" src="../assets/img/SistemasOperacionais7.jpg" alt="Trilha de Estudos 3">
            <div class="textoModulo">
                <h2>Módulo 3</h2>
                <p>Aqui você poderá ter uma introdução ao assunto de sistemas operacionais e poderá treinar conceitos básicos que a matéria exige</p>
                <br>
                <a href="../perguntas/quiz.php?modulo=3" class="botao-entrar">Iniciar Módulo</a>
            </div>
        </div>

        <!-- Módulos Futuros -->
    </div>
</body>
</html>
