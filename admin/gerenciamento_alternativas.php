<?php
    // Iniciando a sessão
    session_start();

    if (isset($_SESSION["usuario"]) && is_array($_SESSION["usuario"])) {
        require("../modulos/conexao.php");

        $adm = $_SESSION["usuario"][1];
        $nome = $_SESSION["usuario"][0];
    } else {
        echo "<script>window.location = 'index.php'</script>";
    }
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
                    <td>INSERIR</td>
                    <td>EDITAR</td>
                    <td>EXCLUIR</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><button id="editar"><a href="inserirAlternativas.php">Inserir</a></button></td>
                    <td><button id="editar"><a href="listaAlternativas.php">Editar</a></button></td>
                    <td><button id="excluir"><a href="listaAlternativasExcluir.php">Excluir</a></button></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
