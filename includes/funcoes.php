<?php

//funcao chamada 'e' ela imprimi o texto, removendo os caracteres especiais que o html nao reconhece
function e(?string $valor): string{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
    //<"pietro"> -> pietro
}
//verifica se o usuario é admin ou nao, se nao for vai para outro login
function exigirAdmin(): void{
    //verifica se o estado da sessao é diferente de ativo, se for diferente ele inicia uma sessao (session_start)
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    //
    if(!iseet(
        $_SESSION['usuario_id'])
        or
        ($_SESSION['tipo'] ?? '')
        !== 'admin'){
            header('location: ../login.php');
            exit;
        }
}