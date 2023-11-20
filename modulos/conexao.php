<?php
    #Arquivo responsável pela conexão com o banco de dados
    Class Conexao{
        private $server = "127.0.0.1"; //o nome passa por um servidor DNS que vai achar o IP e depois localizar, então uma boa prática é utilizar o IP direto.
        private $usuario = "root";
        private $senha = "";
        private $banco = "oslearn";
        
        #Tratamento para que somente a conexão tenha os dados
        public function conectar(){
            try {
                $conexao = new PDO("mysql:host=$this->server;dbname=$this->banco", $this->usuario, $this->senha); //API de uma biblioteca que tem uma string e no final usuário e senha
                $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $erro) {
                $conexao = null;
            }

            return $conexao;
        }
    };
    # Classe de conexão, só quem vai ter acesso aos dados de conexão vai ser quem está na classe "função conectar", quem está de fora não irá conseguir.  
?>