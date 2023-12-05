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
                0 => 2, // Resposta correta para a pergunta 0 no módulo 1
                1 => 0,
                2 => 1,
                3 => 0,
                4 => 0,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 0,
                9 => 0,
            ],
            'respostas_usuario' => isset($_SESSION["respostas_modulo_1"]) ? $_SESSION["respostas_modulo_1"] : [],
        ],
        2 => [
            'respostas_corretas' => [
                0 => 0, // Resposta correta para a pergunta 0 no módulo 2
                1 => 1,
                2 => 0,
                3 => 1,
                4 => 2,
                5 => 1,
                6 => 1,
                7 => 0,
                8 => 0,
                9 => 0,
            ],
            'respostas_usuario' => isset($_SESSION["respostas_modulo_2"]) ? $_SESSION["respostas_modulo_2"] : [],
        ],
        3 => [
            'respostas_corretas' => [
                0 => 1, // Resposta correta para a pergunta 0 no módulo 3
                1 => 0,
                2 => 0,
                3 => 0,
                4 => 1,
                5 => 0,
                6 => 0,
                7 => 0,
                8 => 1,
                9 => 0,
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
        echo "<p class='erro'>Você não respondeu a nenhuma pergunta.</p>";
    }
    
    echo "<link rel='stylesheet' type='text/css' href='../CSS/resultado.css'>";
    // Exibição da pontuação na página
    echo "<div class='container'>";
    echo "<h1>Sua pontuação no módulo $modulo_atual:</h1>";
    echo "<p class='resposta-correta'>Pontuação Correta: $pontuacao_correta</p>";
    echo "<p class='erro'>Pontuação Incorreta: $pontuacao_incorreta</p>";

    // Obtendo as perguntas que o usuário errou
    // Verificar se as perguntas estão na sessão
    if (isset($_SESSION['perguntas'])) {
    $perguntas = $_SESSION['perguntas'];

    // Exibir as perguntas e respostas
    foreach ($respostas_por_modulo[$modulo_atual]['respostas_usuario'] as $index => $resposta) {
        if (isset($respostas_por_modulo[$modulo_atual]['respostas_corretas'][$index])) {
            $resposta_correta = $respostas_por_modulo[$modulo_atual]['respostas_corretas'][$index];
            
            if ($resposta != $resposta_correta) {

                // Exibindo a pergunta
                echo "<h1>Você errou a pergunta: {$perguntas[$index]['pergunta']}</h1>";
                
                // Obtendo o texto das alternativas
                $alternativas_pergunta = $perguntas[$index]['alternativas'];
                
                // Exibindo a alternativa escolhida pelo usuário
                echo "<p class='erro'>Sua resposta: " . $alternativas_pergunta[$resposta]['texto'] . "</p>";
                
                // Exibindo a resposta correta
                echo "<p class='resposta-correta'>Resposta correta: " . $alternativas_pergunta[$resposta_correta]['texto'] . "</p>";
            }
        }
    }
} else {
    echo "<p class='erro'>Perguntas não encontradas na sessão.</p>";
}
    
echo '<a href="../modulos/trilha.php" class="botao">Finalizar</a>';

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
            echo "<h2><p class='resposta-atualizacao'>Pontuação atualizada com sucesso.</p>
            </h2>";
            
            // Marcação do módulo como concluído na variável de sessão
            marcarModuloComoConcluido($modulo_atual);
        } else {
            echo "<h2 class='erro'>Erro ao atualizar a pontuação no banco de dados.</h2>";
        }
    } else {
        echo "<h2 class='informacao'>Você já concluiu este módulo, a pontuação não será acumulada novamente.</h2>";
    }
    echo "</div>";


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
?>