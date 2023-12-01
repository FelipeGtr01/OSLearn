<?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        // Se o usuário não está autenticado, será redirecionado para a página de login
        header('Location: ../modulos/logar.php');
        exit();
    }

    $modulo_atual = isset($_GET['modulo']) ? $_GET['modulo'] : 1;

    // Recuperação das respostas do usuário do módulo atual da sessão
    $respostas = isset($_SESSION["respostas_modulo_" . $modulo_atual]) ? $_SESSION["respostas_modulo_" . $modulo_atual] : [];


    $respostas_por_modulo = [
        1 => [
            'respostas_corretas' => [
                //a resposta correta corresponde a posição que a alternativa correta aparece... 
                0 => 1, // Resposta correta para a pergunta 0 no módulo 1
                1 => 1,
                2 => 0
            ],
            'respostas_usuario' => isset($_SESSION["respostas_modulo_1"]) ? $_SESSION["respostas_modulo_1"] : [],
        ],
        2 => [
            'respostas_corretas' => [
                0 => 1, // Resposta correta para a pergunta 0 no módulo 2
            ],
            'respostas_usuario' => isset($_SESSION["respostas_modulo_2"]) ? $_SESSION["respostas_modulo_2"] : [],
        ],
        3 => [
            'respostas_corretas' => [
                0 => 1, // Resposta correta para a pergunta 0 no módulo 3
                1 => 0, // Resposta correta para a pergunta 1 no módulo 3
            ],
            'respostas_usuario' => isset($_SESSION["respostas_modulo_3"]) ? $_SESSION["respostas_modulo_3"] : [],
        ],
    ];

    // Realização do cálculo da pontuação com base nas respostas corretas
    $pontuacao_correta = 0;
    $pontuacao_incorreta = 0;

    // Verificação se o usuário respondeu a alguma pergunta
    if (count($respostas_por_modulo[$modulo_atual]['respostas_usuario']) > 0) {
        foreach ($respostas_por_modulo[$modulo_atual]['respostas_usuario'] as $index => $resposta) {
            if (isset($respostas_por_modulo[$modulo_atual]['respostas_corretas'][$index])) {
                $resposta_correta = $respostas_por_modulo[$modulo_atual]['respostas_corretas'][$index];
                
                if ($resposta == $resposta_correta) {
                    $pontuacao_correta += 1000; // Incrementa a pontuação correta
                } else {
                    $pontuacao_incorreta += 1000; // Incrementa a pontuação incorreta
                }
            }
        }
    } else {
        echo "Você não respondeu a nenhuma pergunta.";
    }
    

    // Exibição da pontuação na página
    echo "<h1>Sua pontuação no módulo $modulo_atual:</h1>";
    echo "<p>Pontuação Correta: $pontuacao_correta</p>";
    echo "<p>Pontuação Incorreta: $pontuacao_incorreta</p>";

    $idUsuario = $_SESSION['usuario']['id'];

    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }

    // Recuperação das pontuações corretas e incorretas atuais do usuário do banco de dados
    $sql = "SELECT pontuacao_correta, pontuacao_incorreta FROM usuarios WHERE id = :idUsuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $pontuacao_correta_atual = $row['pontuacao_correta'];
    $pontuacao_incorreta_atual = $row['pontuacao_incorreta'];

    // Soma das pontuações acumuladas com as pontuações atuais
    $pontuacao_correta_total = $pontuacao_correta + $pontuacao_correta_atual;
    $pontuacao_incorreta_total = $pontuacao_incorreta + $pontuacao_incorreta_atual;

    // Atualização das pontuações no banco de dados
    $sql = "UPDATE usuarios SET pontuacao_correta = :pontuacao_correta, pontuacao_incorreta = :pontuacao_incorreta WHERE id = :idUsuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':pontuacao_correta', $pontuacao_correta_total, PDO::PARAM_INT);
    $stmt->bindParam(':pontuacao_incorreta', $pontuacao_incorreta_total, PDO::PARAM_INT);

    // Verificação se o usuário já concluiu o módulo atual
    if (!usuarioConcluiuModulo($modulo_atual)) {
        // Se o usuário não concluiu o módulo, atualize a pontuação
        if ($stmt->execute()) {
            echo "<p>Pontuação atualizada no banco de dados com sucesso.</p>";
            
            // Marcação do módulo como concluído na variável de sessão
            marcarModuloComoConcluido($modulo_atual);
        } else {
            echo "<p>Erro ao atualizar a pontuação no banco de dados.</p>";
        }
    } else {
        echo "<p>Você já concluiu este módulo, a pontuação não será acumulada novamente.</p>";
    }

    function usuarioConcluiuModulo($modulo_atual) {
        // Verificação se o módulo atual está registrado na variável de sessão
        if (isset($_SESSION['modulos_concluidos']) && in_array($modulo_atual, $_SESSION['modulos_concluidos'])) {
            return true; // O módulo já foi concluído
        } else {
            return false; // O módulo não foi concluído
        }
    }

    function marcarModuloComoConcluido($modulo_atual) {
        // Registro do módulo atual na variável de sessão "modulos_concluidos"
        if (!isset($_SESSION['modulos_concluidos'])) {
            $_SESSION['modulos_concluidos'] = [];
        }
        $_SESSION['modulos_concluidos'][] = $modulo_atual;
    }

    // Limpeza das respostas da sessão, se desejar
    unset($_SESSION['respostas']);
    // Erro!!! acumular a pontuação só uma vez de cada modulo, após isso não acumular a pontuação no banco!!! 
?>
