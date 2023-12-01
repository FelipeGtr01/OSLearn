<?php

function exibirProgresso() {
    $total_modulos = 3; // Defina o número total de módulos

    // Recupere os módulos concluídos pelo usuário da variável de sessão
    $modulos_concluidos = isset($_SESSION['modulos_concluidos']) ? $_SESSION['modulos_concluidos'] : [];

    // Calcule a quantidade de módulos concluídos pelo usuário
    $modulos_concluidos_count = count($modulos_concluidos);

    // Calcule a porcentagem de conclusão dos módulos
    $porcentagem_conclusao = ($modulos_concluidos_count / $total_modulos) * 100;

    echo '<div class="centralizado">';
    echo '<p>Você completou <span class="progresso">' . $modulos_concluidos_count . '</span> de <span class="total">' . $total_modulos . '</span> módulos. Progresso: <span class="porcentagem">' . number_format($porcentagem_conclusao, 2) . '%</span></p>';
    echo '</div>';
}

// Chame a função para exibir o progresso
exibirProgresso();
?>

<style>
    .progresso {
        font-weight: bold;
        color: green;
    }

    .total {
        font-weight: bold;
        color: blue;
    }

    .porcentagem {
        font-weight: bold;
        color: red;
    }

    .centralizado {
        text-align: center;
        margin-top: 20px;
        color: white;
    }
</style>
