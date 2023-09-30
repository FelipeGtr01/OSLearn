<?php
    // Iniciando a sessão
    session_start();

    if (isset($_SESSION["usuario"]) && is_array($_SESSION["usuario"])) {
        require("conexao.php");

        $conexaoClass = new Conexao();
        $conexao = $conexaoClass->conectar();

        $adm = $_SESSION["usuario"][1];
        $nome = $_SESSION["usuario"][0];
    } else {
        echo "<script>window.location = 'index.php'</script>";
    }

    // Definição de uma consulta SQL para recuperar informações dos usuários
    if ($adm) {
        // Se for um administrador, serão recuperadas todas as informações
        $query = $conexao->prepare("SELECT * FROM usuarios");
    } else {
        // Se for um usuário comum, serão recuperadas apenas nome pontuação correta e incorreta, além de ordenar os usuários pelas suas pontuações.
        $query = $conexao->prepare("SELECT nome, pontuacao_correta, pontuacao_incorreta FROM usuarios ORDER BY pontuacao_correta DESC");
    }

    $query->execute();
    $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ranking.php - Exibição da lógica -->
<html>
<head>
    <title>Ranking - <?php echo $nome; ?></title>
    <link rel="stylesheet" href="../CSS/ranking.css">
</head>
<body>
    <div class="header">
        <?php /* Verificar se isso ainda está sendo necessário {estrutura do if pra a verificação}*/
        if ($adm == 1) {
            echo '<a href="../admin/admin_dashboard.php" class="menu-button">Voltar</a>';
        } else {
            echo '<a href="../user_dashboard.php" class="menu-button">Voltar</a>';
        }
        ?>
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
                    <td>Pontuação Correta</td>
                    <td>Pontuação Incorreta</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario["nome"]; ?></td>
                    <td><?php echo $usuario["pontuacao_correta"]; ?></td>
                    <td><?php echo $usuario["pontuacao_incorreta"]; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
