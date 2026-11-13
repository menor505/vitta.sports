<?php
// inicia uma sessão no navegador
session_start();

require __DIR__ . '/../config/conexao.php';

// Verificamos se o metodo que esta vindo do login é um POST, se não for direciona para o login
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: ../login.php');
}
// variavel de email -> recebe o email do front, tratado e verificado por um filtro que garante que ele é um email
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

// filter_input -> aplica um filtro para validar o input/dado de entrada e exige 3 informações primordias - 1° exige um dado vindo de uma requisição, 2° variável ou campo a ser verificado e 3° tipo de filtro a ser validado

$senha = $_POST['senha'] ?? '';
// $pdo -> gera um driver de conexão com o banco, vindo do conexao.php
// prepare -> prepara a query
$stmt = $pdo->prepare('SELECT id, nome, senha, tipo FROM usuarios WHERE email = :email LIMIT 1');
// execute -> executa a query do banco, com as variáveis ajustadas
$stmt->execute([':email' => $email?:'']);

// fetch ele captura do banco o resultado executado anteriormente
$usuario = $stmt->fetch(); //1 linha, ele captura 1 linha

// verifica se há um retorno dentro da variável usuário

// verifica se a senha não esta errada com o password_verify(sempre com duas informações que serão comparadas)
if (!$usuario || !password_verify($senha, $usuario['senha'])){
    header('Location: ../login.php?erro=1');
    exit();
}

// regenerar ou gerar o id da sessão
session_regenerate_id(true);

// Pega informações para preencher os campos da sessão, apontando que o usuário esta logado na sessão com as informações dele e de forma adequada
$_SESSION['usuario_id'] = (int)$usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['tipo'] = $usuario['tipo'];

// redirecionado com o header(levando essas informações da sessão) para a tela de admin

header('Location: ../admin/produtos.php');
exit;