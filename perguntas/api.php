<?php
    require_once '../modulos/conexao.php';

    $conexaoObj = new Conexao(); // Criação de uma instância da classe Conexao
    $conexao = $conexaoObj->conectar(); // Obtenção da conexão usando o método conectar()

    if (!$conexao) {
        die("Erro na conexão com o banco de dados.");
    }

    $query = "
        SELECT
            p.id AS pergunta_id,
            p.pergunta AS pergunta_texto,
            p.imagem AS pergunta_imagem,
            p.modulo AS pergunta_modulo,
            a.id AS alternativa_id,
            a.texto_alternativa AS alternativa_texto,
            a.correta AS alternativa_correta
        FROM
            perguntas p
        INNER JOIN
            alternativas a ON p.id = a.id_pergunta
        WHERE
            p.modulo = :modulo
    ";

    $modulo_atual = isset($_GET['modulo']) ? $_GET['modulo'] : 1; // Valor padrão 1 se não especificado
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':modulo', $modulo_atual, PDO::PARAM_INT);
    $stmt->execute(); // Execute a consulta com o parâmetro do módulo

    if ($stmt->rowCount() == 0) {
        die("Nenhum resultado encontrado para o módulo especificado.");
    }

    $perguntas = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $perguntaId = $row['pergunta_id'];

        if (!isset($perguntas[$perguntaId])) {
            $perguntas[$perguntaId] = [
                'id' => $perguntaId,
                'pergunta' => $row['pergunta_texto'],
                'imagem' => base64_encode($row['pergunta_imagem']),
                'modulo' => $row['pergunta_modulo'],
                'alternativas' => []
            ];
        }

        $alternativa = [
            'id' => $row['alternativa_id'],
            'texto' => $row['alternativa_texto'],
            'correta' => $row['alternativa_correta']
        ];

        $perguntas[$perguntaId]['alternativas'][] = $alternativa;
    }

    // Conversão do array em JSON
    $jsonResponse = json_encode(array_values($perguntas));

    // Enviar uma Resposta JSON
    header('Content-Type: application/json');
    echo $jsonResponse;
?>
