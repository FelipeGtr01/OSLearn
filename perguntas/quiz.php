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
            }
        }

        if(isset($data) && is_array($data)){

            echo "<form method='post'>";

            foreach ($data as $index => $pergunta) {
                echo "<div id='pergunta-container'>";
                echo "<h1>{$pergunta['pergunta']}</h1>";
                
                // Exibição da imagem relacionada à pergunta, se houver.
                $imagens = $pergunta['imagem'];
                if (!empty($imagens)) {
                    echo "<img src='data:image/jpeg;base64," . $imagens . "' alt='Imagem da pergunta' style='max-width: 200px;' />";
                }

                // Exibição da resposta salva pelo usuário, se houver.
                $resposta_salva = isset($_SESSION["respostas_modulo_$modulo_atual"][$index]) ? $_SESSION["respostas_modulo_$modulo_atual"][$index] : '';

                // Exibição das alternativas com a opção marcada.
                echo "<div id='alternativas'>";
                foreach ($pergunta['alternativas'] as $j => $alternativa) {
                    echo "<div class='alternativa'>";
                    $checked = $j == $resposta_salva ? 'checked' : '';
                    echo "<input type='radio' name='resposta_$index' value='$j' $checked/> {$alternativa['texto']} <br>";
                    echo "</div>";
                }
                echo "</div>"; // Fechar div 'alternativas'
                
                echo "</div>"; // Fechar div 'pergunta-container'
            }

            // Adicione um botão para verificar as respostas.
            echo "<button type='submit' name='verificar'>Verificar Respostas</button>";
            echo "</form>";
        } else{
            echo "Erro!";
        }
    }
?>

    <script>
        let totalPerguntas = <?php echo count($data); ?>;
        let progressoAtual = 0;

        document.addEventListener('DOMContentLoaded', function () {
            let opcoesResposta = document.querySelectorAll('input[type="radio"]');

            opcoesResposta.forEach(function (opcao) {
                opcao.addEventListener('change', function () {
                    progressoAtual++;
                    updateProgressBar();
                });
            });
        });

        function updateProgressBar() {
            let percentualProgresso = (progressoAtual / totalPerguntas) * 100;
            document.getElementById('progress-indicator').style.width = percentualProgresso + '%';
            updateProgressBarColor(percentualProgresso);
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
    </script>
</body>
</html>
