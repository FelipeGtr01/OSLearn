<?php
    // Iniciando a sessão
    session_start();

    if (isset($_SESSION["usuario"]) && is_array($_SESSION["usuario"])) {
        require("../modulos/conexao.php");

        $conexaoClass = new Conexao();
        $conexao = $conexaoClass->conectar();

        $adm = $_SESSION["usuario"][1];
        $nome = $_SESSION["usuario"][0];
    } else {
        echo "<script>window.location = 'index.php'</script>";
    }

    // Consulta SQL para recuperar informações dos usuários
    if ($adm) {
        // Se for um administrador, serão recuperadas todas as informações
        $query = $conexao->prepare("SELECT * FROM usuarios");
    } else {
        // Se for um usuário comum, recupera apenas nome, a pontuação correta e incorreta
        $query = $conexao->prepare("SELECT nome, pontuacao_correta, pontuacao_incorreta FROM usuarios");
    }

    $query->execute();
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<html>
<head>
    <link rel="stylesheet" href="../CSS/ranking.css"> <!-- Por que ranking? -->
</head>
<body>
    <div class="header">
        <?php
        if ($adm == 1) {
            echo '<a href="../admin/admin_dashboard.php" class="menu-button">Voltar</a>';
        } else {
            echo '<a href="../user_dashboard.php" class="menu-button">Voltar</a>';
        }
        ?>
        <h2>Bem-vindo, Administrador</h2>
        <a href="../logout.php" class="menu-button">Sair</a>
    </div>

    <div id="tabelaUsuarios">
        <table>
            <thead>
                <tr>
                    <th colspan="7" style="text-transform: uppercase;">Ranking de Usuários</th>
                </tr>
                <tr style="font-weight: bold">
                    <td>Nome</td>
                    <td>Email</td>
                    <td>Senha</td>
                    <td>ADM</td>
                    <td>ID</td>
                    <td>EDITAR</td>
                    <td>EXCLUIR</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario["nome"]; ?></td>
                    <td><?php echo $usuario["email"]; ?></td>
                    <td><?php echo $usuario["senha"]; ?></td>
                    <td><?php echo $usuario["adm"]; ?></td>
                    <td><?php echo $usuario["id"]; ?></td>
                    <td><button id="editar"><a href="edicao.php?id=<?php echo $usuario["id"]; ?>">Editar</a></button></td>
                    <td><button id="excluir"><a href="excluir.php?id=<?php echo $usuario["id"]; ?>">Excluir</a></button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
