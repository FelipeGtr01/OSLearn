<?php
    session_start();

    if (!isset($_SESSION['usuario']['id'])) {
        // Se o usuário não estiver autenticado, será redirecionado para a página de login
        header('Location: logar.php');
        exit();
    }
    else{
        require("../modulos/conexao.php");

        function conectarAoBanco() {
            try {
                $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        
        $pdo = conectarAoBanco();

        if($_SERVER["REQUEST_METHOD"] == "GET"){
            $idAlternativa = $_GET["id"];

                try {
                    $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Erro na conexão com o banco de dados: " . $e->getMessage());
                }

                $query = $pdo->prepare("DELETE FROM alternativas WHERE id=:idAlternativa");
                $query->bindParam(':idAlternativa', $idAlternativa, PDO::PARAM_INT);
                $query->execute();
                header('Location: gerenciamento_alternativas.php');
        }
    }
?>