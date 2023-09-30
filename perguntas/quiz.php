<?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        // Se o usuário não está autenticado, será redirecionado para a página de login
        header('Location: ../modulos/logar.php');
        exit();
    }else{
        $apiUrl = 'http://localhost/OSLearn/perguntas/api.php';
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        $perguntas = $data; // Supondo que a API retorna as perguntas diretamente {revisar}

        $pergunta_atual = isset($_SESSION['pergunta_atual']) ? intval($_SESSION['pergunta_atual']) : 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['resposta'])) {
                $resposta_escolhida = $_POST['resposta'];
                $_SESSION["resposta_$pergunta_atual"] = $resposta_escolhida;

                if (isset($_POST['voltar']) && $pergunta_atual > 0) {
                    $pergunta_atual--;
                } else if (isset($_POST['avancar']) && $pergunta_atual < count($perguntas) - 1) {
                    $pergunta_atual++;
                } else if (isset($_POST['verificar'])) {
                    header("location: resultado.php");
                    exit();
                }
                
                $_SESSION['pergunta_atual'] = $pergunta_atual; // Atualização da variável de sessão
            }
        }

        if ($pergunta_atual < count($perguntas)) {
            $perguntaAtual = $perguntas[$pergunta_atual]['pergunta'];
            $alternativas = $perguntas[$pergunta_atual]['alternativas'];
            echo "<h1>{$perguntaAtual}</h1>";
            echo "<form method='post'>";
            
            foreach ($alternativas as $j => $alternativa) {
                echo "<div class='alternativa'>";
                echo "<input type='radio' name='resposta' value='$j'/> {$alternativa['texto']} <br>";
                echo "</div>";
            }

            echo "<br><div class='botoes'>";
            
            if ($pergunta_atual > 0) {
                echo "<button type='submit' name='voltar'>Voltar</button>";
            }
            
            if ($pergunta_atual < count($perguntas) - 1) {
                echo "<button type='submit' name='avancar'>Avançar</button>";
            } else {
                echo "<button type='submit' name='verificar'>Verificar Respostas</button>";
            }
            
            echo "</div>";
            echo "</form>";
        }
    }
?>
