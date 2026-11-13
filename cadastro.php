<?php
require ".includes/funcoes.php";


$erros=[];
$sucesso='';
$nome='';
$email='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nome = trim($_POST['nome'] ?? '');
    $nome = trim($_POST['email'] ?? '');
    $nome = trim($_POST['senha'] ?? '');
    $confirmarsenha=trim($_POST['confirmarsenha'] ?? '');
    //validacoes basica dos dados
    if($nome===''){
        $erros[]='informe um nome!';
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erros[]= 'informe um email valido'
    }
    //strlen verifica o tamanho da variavel/caracteres digitados que ela captrou
    if(strlen($senha)<8){
        $erros[]= 'a senha deve ter pelo menos 8 caracteres'
    }
    if($senha !== $confirmarsenha){
        $erros[]= 'as senhas nao sao iguais!';
    }
    if(empty($erros)){
        try{
            require_once __DIR__ . '/config/conexao.php';
            if(!isset($pdo) or !($pdo instanceof PDO)){
                throw new RuntimeException(
                    'a conexao nao foi encontrada. verifique se config/ conexao.php cria a variavel $pdo'
                );
            }
            $consulta=$pdo ->prepare(
                'SELECT id from usuarios WHERE email= :email LIMIT 1'
            );
            $consulta->execute([':email'=>$email]);

            if($consulta -> fetch()){
                $erros[]='ja existe uma conta cadastrada com esse e-mail!';
            }else{
                //nunca armazenamos uma senha original no banco, então o password_hash faz uma criptografia da senha para hash -PASSWORD_HASH é a forma padrao de transformacao para HASH
                $senhahash=password_hash($senha, PASSWORD_DEFAULT);

                $sql='INSERT INTO usuarios (nome, email, senha, tipo, criado_em)VALUES(:nome, email, senha, tipo, criado_em) VALUES (:nome, :email, :senha, :tipo, NOW())';
                $inserir=$pdo->prepare($sql);
                $inserir->execute([
                    ':nome'=>$nome,
                    ':email'=>$email,
                    ':senha'=>$senhahash,
                    ':tipo'=>'cliente'
                ]);
                $sucesso='cadastro realizado com sucesso! agora voce ja pode entrar na sua conta.';
                $nome='';
                $email='';
            }
        } catch(PDOException $e){
            $erros="nao foi possivel concluir o cadastro.";
        }catch (RuntimeException $e){
            $erros = $e -> getMessage();
        }
    }
}
require __DIR__ . '/includes/cabecalho.php';
?>

<main class="login">
    <section>
        <small>FACA PARTE DA VITTA</small>
        <h1>SEU PROXIMO
            <br>PASSO COMECA <br>
            <em>AQUI.</em>
        </h1>
        <p>crie sua conta e acompanhe sua experiencia na Vitta Sports.</p>
    </section>
    <form action="cadastro.php" method="POST">
        <small>NOVA CONTA</small>
        <h2>CADASTRE-SE</h2>

        <?php if (!empty($erros)): ?>
        <div class="auth_message auth_error">
            <?php foreach($erros as $erro): ?>
                <p><?=e($erro)?></p>
                <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($sucesso !== ''): ?>
            <div class="auth-message auth-success">
                <?= e($sucesso) ?>
                <p>
                    <a href="login.php">ir para o login</a>
                </p>
            </div>
            <?php endif; ?>
            <label for="name">nome completo:</label>
            <input type="text" id="nome" name="nome" maxlenght="100" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" maxlenght="150" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" minlenght="8">

            <label for="confirmar_senha">Confirmar_senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" minlenght="8" required>

            <button type="submit" class="btn">criar minha conta!</button>
    </form>
</main>