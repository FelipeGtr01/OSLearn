<?php #Lógica para o usuário deslogar do sistema.
    session_start();
    session_destroy();
    header("Location: index.php");
?>
