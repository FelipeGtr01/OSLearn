<?php
    // Iniciando a sessão
    session_start();

    if (isset($_SESSION["usuario"]) && is_array($_SESSION["usuario"])) {
        require("../modulos/conexao.php");

        $conexaoClass = new Conexao();
        $conexao = $conexaoClass->conectar();

        $adm = $_SESSION["usuario"][1];
        $nomeUsuarioLogado = $_SESSION["usuario"][0];
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
    <link rel="stylesheet" href="../CSS/usuarios_cadastrados.css"> <!-- Por que ranking? -->
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
        <div class="title-container">
            <br>
            <h2>🔎 LISTA DE USUÁRIOS 🔍</h2>
        </div>
    </div>

    <div id="tabelaUsuarios">
        <table>
            <thead>
                
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
                    
                    <?php
                        /* Conta principal de administração do sistema*/
                        $isUsuarioLogado = ($usuario["nome"] === $nomeUsuarioLogado);
                        $isAdminLogado = ($usuario["email"] === "ADMIN@gmail.com");
                        $isAdminParaExcluir = ($usuario["adm"] == 1 && !$isAdminLogado && $isUsuarioLogado);
                        $isAdminOutro = ($usuario["adm"] == 1 && !$isUsuarioLogado && !$isAdminLogado);
                        $isPerfilAdminGlobal = ($usuario["email"] === "ADMIN@gmail.com");
                        
                    ?>

                <tr <?php echo $isUsuarioLogado ? 'class="usuario-logado"' : ''; ?> >
                    <td><?php echo $usuario["nome"]; ?></td>
                    <td><?php echo $usuario["email"]; ?></td>
                    <td><?php echo $usuario["senha"]; ?></td>
                    <td><?php echo $usuario["adm"]; ?></td>
                    <td><?php echo $usuario["id"]; ?></td>
                    <td>
                        <?php
                            // Verifica se o usuário logado é um administrador e se está tentando editar a conta "ADMIN@gmail.com"
                            if ($isAdminLogado || $isPerfilAdminGlobal) {
                                echo "Não permitido";
                            } else {
                                // Se não for o administrador tentando editar a conta "ADMIN@gmail.com", exibe o botão "Editar"
                                echo '<button id="editar" ';
                                if ($isAdminOutro) {
                                    echo 'disabled';
                                }
                                echo '><a href="edicao.php?id=' . $usuario["id"] . '">Editar</a></button>';
                            }
                        ?>    
                    </td>
                    <td>
                        <?php
                            // Verifica se o usuário logado é um administrador e se está tentando excluir sua própria conta
                            if ($isAdminParaExcluir || ($isAdminLogado && $isUsuarioLogado) || $isPerfilAdminGlobal) {
                                echo "Não permitido";
                            } else {
                                // Se não for o administrador tentando excluir sua própria conta, exibe o botão "Excluir" com confirmação
                                echo '<button id="excluir" onclick="confirmarExclusao(' . $usuario["id"] . ', \'' . $usuario["nome"] . '\',' . $isAdminParaExcluir . ')">Excluir</button>';
                            }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<script>
    function confirmarExclusao(id, nome, isAdmin) {
        var mensagem = "Tem certeza que deseja excluir o usuário '" + nome + "'?";
        
        if (isAdmin) {
            mensagem += "\nEste é um perfil de administrador. Ao excluir, o acesso administrativo será removido.";
        }

        var confirmacao = confirm(mensagem);

        if (confirmacao) {
            // Se o usuário confirmar, redirecionar para a página de exclusão com o ID do usuário
            window.location.href = 'excluir.php?id=' + id;
        }
        // Se o usuário cancelar, nada acontecerá
    }
</script>