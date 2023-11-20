<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../CSS/quiz.css">
</head>
<body>
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
            // Lógica para processar respostas.

            $_SESSION["respostas"] = [];
            
            foreach ($perguntas as $index => $pergunta) {
                if (isset($_POST["resposta_$index"])) {
                    $resposta_escolhida = $_POST["resposta_$index"];
                    // Salvando a resposta do usuário no array de respostas.
                    $_SESSION["respostas"][$index] = $resposta_escolhida;
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
            echo "<h1>{$pergunta['pergunta']}</h1>";
            
            // Exibiçõa da imagem relacionada à pergunta, se houver.
            $imagens = $pergunta['imagem'];
            if (!empty($imagens)) {
                echo "<img src='data:image/jpeg;base64," . $imagens . "' alt='Imagem da pergunta' style='max-width: 200px;' />";
            }

            // Exibição da resposta salva pelo usuário, se houver.
            $resposta_salva = isset($_SESSION['respostas'][$index]) ? $_SESSION['respostas'][$index] : '';

            // Exibição das alternativas com a opção marcada.
            foreach ($pergunta['alternativas'] as $j => $alternativa) {
                echo "<div class='alternativa'>";
                $checked = $j == $resposta_salva ? 'checked' : '';
                echo "<input type='radio' name='resposta_$index' value='$j' $checked/> {$alternativa['texto']} <br>";
                echo "</div>";
            }
        }
        // Adicione um botão para verificar as respostas.
        echo "<button type='submit' name='verificar'>Verificar Respostas</button>";
        echo "</form>";
        } else{
            echo "Erro!";
        }
    }
?>
</body>
</html>