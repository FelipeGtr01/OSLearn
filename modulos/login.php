<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        // Lógica de validação do login com base nas informações do banco de dados.
        // Se as credenciais forem válidas, será redirecionado para a página principal do sistema.
        // Caso contrário, será exibido uma mensagem de erro.

        require("conexao.php");

        Class LoginAndCadastro{
            private $con = null;
            
            public function __construct($conexao) {
                $this->con = $conexao;
            }

            public function send(){
                #Verificação se existe POST para essa página, haverá direcionamento.
                if (empty($_POST) || $this->con == null) {
                    //Quando não tiver POST ou conexão irá mandar para o login novamente.
                    echo "<script>window.location = '../index.php'</script>";
                    return;
                }

                switch (true) {
                    case (isset($_POST["email"]) && isset($_POST["senha"])):
                        echo $this->login($_POST["email"], $_POST["senha"]);
                        break;
                }
            }

            public function login($email, $senha){
                $conexao = $this->con;

                //Select no banco de dados para verificar se o usário está no banco.
                $query = $conexao->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ? "); //A interrogação é utilizada para atribuir um valor quando for executado.
                $query->execute(array($email, $senha));
                
                // Verifica se encontrou um usuário correspondente
                $user = $query->fetch(PDO::FETCH_ASSOC);
            
                if ($user && $user['email'] === $email && $user['senha'] === $senha) {
                    // Credenciais corretas
                    session_start();
                    $_SESSION["usuario"] = array($user["nome"], $user["adm"], "id" => $user["id"]);
                
                if ($user["adm"] == 1) {
                    // Usuário é um administrador, redirecionar para a página de administração.
                    return "<script>window.location = '../admin/admin_dashboard.php'</script>";
                } else {
                    // Usuário comum, redirecionar para a página do usuário.
                    return "<script>window.location = '../user_dashboard.php'</script>";
                }
                } else {
                    // Credenciais incorretas
                    header("Location: logar.php?erro=credenciais_incorretas");
                    exit(); // Certifique-se de sair do script para evitar execução adicional
                }                  
            }
        };

        $conexao = new Conexao(); #Não tem construct, então não precisa de parâmetro dento dos parênteses.
        $classe = new LoginAndCadastro($conexao->conectar());
        $classe->send();
    }
?>
