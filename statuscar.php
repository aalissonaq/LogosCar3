<?php
    require_once('config.php');
    require_once('session.php');
    // Capturando carros na base
    $query = $bd->prepare('SELECT id_veiculo, id_uf, id_cidade, placa, modelo, montadora, alias, kilometragem, cor, proprietario, status FROM tb_veiculo WHERE status = 1');
    $query->execute();
    $QtdeBase = $query->rowCount();
    $EmBase = $query->fetchAll(PDO::FETCH_OBJ);
    // Capturando carros em rota
    $query = $bd->prepare('SELECT id_veiculo, id_uf, id_cidade, placa, modelo, montadora, alias, kilometragem, cor, proprietario, status FROM tb_veiculo WHERE status = 2');
    $query->execute();
    $QtdeRota = $query->rowCount();
    $EmRota = $query->fetchAll(PDO::FETCH_OBJ);
    // Capturando carros que podem ir para manutenção (não estão nela)
    $query = $bd->prepare('SELECT id_veiculo, id_uf, id_cidade, placa, modelo, montadora, alias, kilometragem, cor, proprietario, status FROM tb_veiculo WHERE status <> 3');
    $query->execute();
    $QtdePodeIrManut = $query->rowCount();
    $PodeManut = $query->fetchAll(PDO::FETCH_OBJ);
    // Capturando carros em manutenção
    $query = $bd->prepare('SELECT id_veiculo, id_uf, id_cidade, placa, modelo, montadora, alias, kilometragem, cor, proprietario, status FROM tb_veiculo WHERE status = 3');
    $query->execute();
    $QtdeManut = $query->rowCount();
    $EmManut = $query->fetchAll(PDO::FETCH_OBJ);
    //capturando motoristas
    $query = $bd->prepare('SELECT * FROM tb_users WHERE motorista = true AND ativo = true AND disponivel = true AND data_validade > now()');
    $query->execute();
    $QtdeMotoristasDisp = $query->rowCount();
    $motoristas = $query->fetchAll(PDO::FETCH_OBJ);
?>