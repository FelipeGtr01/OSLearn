<?php
    session_start();

    if (!isset($_SESSION['usuario'])) {
        // Se o usuário não está autenticado, será redirecionado para a página de login
        header('Location: ../modulos/logar.php');
        exit();
    }else{
        include '../modulos/conexao.php';

        $apiUrl = 'http://localhost/OSLearn/perguntas/api.php';
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        $perguntas = $data; // Supondo que a API retorna as perguntas diretamente {revisar}

        $pontosCorretos = 0;
        $pontosIncorretos = 0;
        $respostas_corretas = 0;
        $respostas_erradas = 0;
        foreach ($perguntas as $i => $pergunta) {
            $resposta_escolhida = isset($_SESSION["resposta_$i"]) ? $_SESSION["resposta_$i"] : null;
            
            if ($resposta_escolhida !== null && isset($pergunta['alternativas'][$resposta_escolhida]) && $pergunta['alternativas'][$resposta_escolhida]['correta'] == 1) {
                $respostas_corretas++;
                $pontosCorretos = $respostas_corretas * 1000;
            }
            else {
                $respostas_erradas++;
                $pontosIncorretos = $respostas_erradas * 1000;
            }
            $idUsuario = $_SESSION["usuario"]["id"];
        }

        echo "<h1>Resultado do Quiz</h1>";
        echo "<p>Total de pontos: $pontosCorretos</p>";
        echo "<p>Total de pontos Incorretos: $pontosIncorretos</p>";
        echo "<p>Total de acertos: $respostas_corretas/" . count($perguntas) . "</p>";
        echo "<p>Total de erros: $respostas_erradas/" . count($perguntas) . "</p>";

        // Configuração da conexão PDO
        try {
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=OSLearn", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }

        $pontosCorretos = $pontosCorretos;
        $pontosIncorretos = $pontosIncorretos;

        $sql = "UPDATE usuarios SET pontuacao_correta = :pontosCorretos, pontuacao_incorreta = :pontosIncorretos WHERE id = :idUsuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $stmt->bindParam(':pontosCorretos', $pontosCorretos, PDO::PARAM_INT);
        $stmt->bindParam(':pontosIncorretos', $pontosIncorretos, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<p>Pontuação inserida no banco de dados com sucesso.</p>";
        } else {
            echo "<p>Erro ao inserir a pontuação no banco de dados.</p>";
        }
    }
?>
