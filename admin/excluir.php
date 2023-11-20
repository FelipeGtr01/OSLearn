<?php
    session_start();

    if (!isset($_SESSION['usuario']['id'])) {
        // Se não houver uma sessão válida ou o usuário não for um administrador, será redirecionado para o login.
        header('Location: logar.php');
        exit();
    }
    else{
        require("../modulos/conexao.php");

        $conexaoClass = new Conexao();
        $conexao = $conexaoClass->conectar();
        $conexao->exec("SET NAMES utf8");

        if($_SERVER["REQUEST_METHOD"] == "GET"){
            $idUsuario = $_GET["id"];

                try {
                    $pdo = new PDO("mysql:host=127.0.0.1;dbname=oslearn", "root", "");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("Erro na conexão com o banco de dados: " . $e->getMessage());
                }

                $query = $conexao->prepare("DELETE FROM usuarios WHERE id=:idUsuario");
                $query->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
                $query->execute();
                header('Location: usuarios_cadastrados.php');
        }
    }
?>