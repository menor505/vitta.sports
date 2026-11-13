<?php
//exige que conexão com banco/arquivo de conexão exista
require './config/conexao.php';
require './includes/funcoes.php';
//pega o id da tela, verifica se é um numero inteiro e armazena
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

//SELECT p.*, c.nome AS categoria busca todos os campos da tabela produtos (p.*) e o nome da tabela categoria (c.nome), onde apelidamos de categoria

//FROM produtos p - tabela onde pesquisamos a maior quantidade de campos (principal)

//INNER JOIN categorias c - INNER JOIN junta as tabelas (porque o campo categoria não existe na tabela 'produtos') - c é apelido de categoria

//ON p.categoria_id =c.id - ON relaciona os campos de categoria_id (tabela produtos) com o id da tabela categoria

//WHERE p.id=:id' - WHERE é o que estamos pesquisando produtos.id que é igual ao id da variavel de produto (variavel hipotetica)


$stmt =$pdo -> prepare('SELECT p.*, c.nome AS categoria FROM produtos p INNER JOIN categorias c ON p.categoria_id =c.id WHERE p.id=:id');

//alternamos a variavel hipotética (:id) para o valor de variável $id real
//?: funciona como um if: verificando se o valor não é zero
$stmt ->execute(['id'=>$id?:0]);

//captura tudo que vem do SELECT e armazena
$produto = $stmt->fetch();
include './includes/cabecalho.php';
?>
<main class="section">
    <?php if(!$produto): ?>
        <h1>Produto não encontrado</h1>
        <?php else: ?>
            <div class="detail">
                <div class="visual large">
                    VITTA / <?php (int)$produto['id'] ?>
                </div>
                <div>
                    <small>
                        <?= e($produto['categoria']??'VITTA')?>
                    </small>
                    <h1>
                        <? e($produto['nome'])?>
                    </h1>

                    <h2>
                        <?=number_format((float) $produto ['preco'], 2, ',', '.') ?>
                    </h2>
                    <p>
                        Tamanho: 
                        <?= e($produto['tamanho']) ?>
                    </p>
                    <p>
                        estoque:
                        <?=(int)$produto ['estoque'] ?>
                    </p>
                </div>
            </div>
            <?php endif: ?>
</main>

<?php include './includes/rodape.php'; ?>
