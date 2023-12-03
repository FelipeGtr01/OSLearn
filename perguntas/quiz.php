<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../CSS/quiz.css">
</head>
<body>
<!-- Barra de progresso -->
<div id="progress-bar">
    <div id="progress-indicator"></div>
    <button id="stop-button" onclick="confirmStop()">X</button>
</div>

<?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header('Location: ../modulos/logar.php');
        exit();
    } else {
        if (isset($_GET['modulo'])) {
            $modulo_atual = $_GET['modulo'];
        } else {
            header('Location: ../modulos/trilha.php');
        }

        $apiUrl = "http://localhost/oslearn/perguntas/api.php?modulo=$modulo_atual";
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        $perguntas = $data; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtém o módulo atual
            $modulo_atual = $_GET['modulo']; // Supondo que $modulo_atual seja definido corretamente

            // Verifica se a estrutura de respostas por módulo já existe na sessão
            if (!isset($_SESSION["respostas_modulo_$modulo_atual"])) {
               $_SESSION["respostas_modulo_$modulo_atual"] = [];
            }

            // Loop para salvar as respostas por módulo
            foreach ($perguntas as $index => $pergunta) {
                if (isset($_POST["resposta_$index"])) {
                    $resposta_escolhida = $_POST["resposta_$index"];
                    // Salvar a resposta do usuário no array de respostas do módulo atual
                    $_SESSION["respostas_modulo_$modulo_atual"][$index] = $resposta_escolhida;
                }
            }

            if (isset($_POST['verificar'])) {
                // Redirecione para a página de resultado com o parâmetro do módulo
                $modulo_atual = isset($_GET['modulo']) ? $_GET['modulo'] : 1;
                header("location: resultado.php?modulo=$modulo_atual");
                // Armazenar as perguntas na sessão
                $_SESSION['perguntas'] = $perguntas;
                header("location: resultado.php?modulo=$modulo_atual");
            }
        }

        if (isset($data) && is_array($data)) {
            echo "<form method='post' id='quiz-form'>";
        
            foreach ($data as $index => $pergunta) {
                $resposta_salva = isset($_SESSION["respostas_modulo_$modulo_atual"][$index]) ? $_SESSION["respostas_modulo_$modulo_atual"][$index] : '';
        
                echo "<div class='pergunta-container' id='pergunta-$index' style='display: " . ($index === 0 ? 'block' : 'none') . ";'>";
                echo "<h1>{$pergunta['pergunta']}</h1>";
        
                // Exibição da imagem relacionada à pergunta, se houver.
                $imagens = $pergunta['imagem'];
                if (!empty($imagens)) {
                    echo "<img src='data:image/jpeg;base64," . $imagens . "' alt='Imagem da pergunta' style='max-width: 200px;' />";
                }
        
                // Exibição das alternativas com a opção marcada.
                echo "<div class='alternativas'>";
                foreach ($pergunta['alternativas'] as $j => $alternativa) {
                    echo "<div class='alternativa'>";
                    $checked = $j == $resposta_salva ? 'checked' : '';
                    echo "<input type='radio' name='resposta_$index' value='$j' $checked/> {$alternativa['texto']} <br>";
                    echo "</div>";
                }
                echo "</div>"; // Fechar div 'alternativas'
        
                echo "</div>"; // Fechar div 'pergunta-container'
            }
        
            echo "<div class='botoes'>";
            echo "<button type='button' onclick='anteriorPergunta()'>Anterior</button>";
            echo "<button type='button' onclick='proximaPergunta()'>Próxima</button>";
            echo "<button type='submit' name='verificar'>Verificar Respostas</button>";
            echo "</div>"; // Fechar div 'botoes'
        
            echo "</form>";
        } else {
            echo "Erro!";
        }
    }
?>

    <script>
        let totalPerguntas = <?php echo count($data); ?>;
        let progressoAtual = 0;
        let respostaAlterada = false; // Adicionado para rastrear se a resposta foi alterada

        document.addEventListener('DOMContentLoaded', function () {
            let opcoesResposta = document.querySelectorAll('input[type="radio"]');

            opcoesResposta.forEach(function (opcao) {
                opcao.addEventListener('change', function () {
                    respostaAlterada = true; // Atualiza o status da resposta
                });
            });
        });

        function updateProgressBar() {
            if (respostaAlterada) {
                let percentualProgresso = (progressoAtual / totalPerguntas) * 100;
                document.getElementById('progress-indicator').style.width = percentualProgresso + '%';
                updateProgressBarColor(percentualProgresso);
                respostaAlterada = false; // Reinicia o status da resposta
            }
        }

        function updateProgressBarColor(percentualProgresso) {
            let cor;
            if (percentualProgresso < 30) {
                cor = '#fff'; // branco
            } else if (percentualProgresso < 70) {
                cor = '#ff0'; // amarelo
            } else if (percentualProgresso < 90) {
                cor = '#ffa500'; // laranja
            } else {
                cor = '#4caf50'; // verde
            }
            document.getElementById('progress-indicator').style.backgroundColor = cor;
        }

        function confirmStop() {
            if (confirm("Tem certeza de que deseja parar? Se você parar, o progresso será perdido.")) {
                window.location.href = '../modulos/trilha.php';
            }
        }

        function anteriorPergunta() {
            let perguntas = document.querySelectorAll('.pergunta-container');
            let indexAtual = Array.from(perguntas).findIndex(pergunta => pergunta.style.display === 'block');

            if (indexAtual > 0) {
                perguntas[indexAtual].style.display = 'none';
                perguntas[indexAtual - 1].style.display = 'block';
                progressoAtual = indexAtual - 1;
                updateProgressBar();
            }
        }

        function proximaPergunta() {
            let perguntas = document.querySelectorAll('.pergunta-container');
            let indexAtual = Array.from(perguntas).findIndex(pergunta => pergunta.style.display === 'block');

            if (indexAtual < perguntas.length - 1) {
                perguntas[indexAtual].style.display = 'none';
                perguntas[indexAtual + 1].style.display = 'block';
                progressoAtual = indexAtual + 1;
                updateProgressBar();
            }
        }
    </script>
</body>
</html>
