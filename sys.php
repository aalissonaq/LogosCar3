<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    require_once('statuscar.php');
?>
<head>
    <link rel="stylesheet" href="<?php echo BASE;?>/css/sys.css">
<?php
    if(!$isMobile){
        echo '<link rel="stylesheet" href="'. BASE .'/css/sysnotmob.css">';
    }
?>
</head>
<body>
<meta http-equiv="refresh" content="180">
    <?php
        require_once('nav.php');
    ?>
    <div id="loading" style="display: block" class="loading" align="center">
		<img src="<?php echo BASE;?>/img/preloader.gif"><br>
	    Carregando...
	</div>
    <div id="content" class="content container-fluid justify-content-center text-center" style="display: none">
        <div class="progress" style="height: 5px;">
            <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="background-color: #8a8883; width: 0%; height: 5px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <?php
            require_once('check_geral.php');
            if($level=='MTR'){
                $query = $bd->prepare('SELECT * FROM tb_veiculo WHERE status = 1');
            } else{
                $query = $bd->prepare('SELECT * FROM tb_veiculo WHERE id_uf = :myuf');
                $query->bindParam(':myuf',$myuf);
            }
            if(!@$_GET['startRota']==''){
                if($_GET['startRota']=='1'){
                    echo '<div id="avisoGet" class="alert alert-success" role="alert"> Rota cadastrado com sucesso! </div>';
                } else{
                    echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o cadastro da rota... </div>';
                }
                header("Refresh: 5;url=sys.php");
            }
            if(!@$_GET['retornoRota']==''){
                if($_GET['retornoRota']=='1'){
                    echo '<div id="avisoGet" class="alert alert-success" role="alert"> Rota finalizada com sucesso! </div>';
                } else{
                    echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o cadastro de fim da rota... </div>';
                }
                header("Refresh: 5;url=sys.php");
            }
            if(!@$_GET['retornaManut']==''){
                if($_GET['retornaManut']=='1'){
                    echo '<div id="avisoGet" class="alert alert-success" role="alert"> Veículo já está de volta à base. </div>';
                } else{
                    echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível retornar o veículo à base... </div>';
                }
                header("Refresh: 5;url=sys.php");
            }
        ?>
        <div <?php if(!$isMobile) echo 'class="row"'; ?> id="mainview">
            <div class="card" id="cardCasa">
                <div class="card-header">
                    <strong>Na Base </strong>
                </div>
                <div class="card-body">
                <?php
                    if($QtdeBase>0){
                        foreach($EmBase as $base){
                            echo '<div class="card" style="width: 45%; float: left;"></br>';
                            echo '<center><i class="fas fa-car fa-2x" style="color: '.$base->cor.'"></i></center>';
                            echo '<h6><strong>'.$base->alias.'</strong> ('.$base->placa.')</h6>';
                            echo '<p>Aguardando atividade.</p>';
                            echo '<div class="card-footer">';
                            echo '<a class="btn btn-info" href="#" data-toggle="modal" data-target="#modalSaida'.$base->id_veiculo.'" title="Iniciar Rota"><i class="fas fa-play"></i></a>';
                            echo '<a class="btn btn-danger" href="#" data-toggle="modal" data-target="#modalManut'.$base->id_veiculo.'" title="Manutenção"><i class="fas fa-tools"></i></a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else{
                        echo 'Não há carros na base!';
                    }
                ?>
                </div>
            </div>
            <div class="card" id="cardMissao">
                <div class="card-header">
                    <strong>Em Rota </strong>
                </div>
                <div class="card-body">
                    <?php
                        if($QtdeRota>0){
                            foreach($EmRota as $rota){
                                echo '<div class="card">';
                                echo '<p><center><i class="fas fa-car fa-2x" style="color: '.$rota->cor.'"></i> <strong> &nbsp &nbsp'.$rota->alias.'</strong> ('.$rota->placa.')</center></p>';
                                $query = $bd->prepare('SELECT data_lancamento, rota FROM tb_viagem WHERE id_veiculo = :id_veiculo AND em_andamento = 1');
                                $query->bindParam(':id_veiculo',$rota->id_veiculo);
                                $query->execute();
                                $rotarealizada = $query->fetch();
                                echo '<p> Rota: '.$rotarealizada['rota'].'</br> Saída: '.date('d/m/Y H:i:s', strtotime($rotarealizada['data_lancamento'])).'</p>';
                                echo '<div class="card-footer">';
                                echo '<a class="btn btn-success" href="#" data-toggle="modal" data-target="#modalSaida'.$rota->id_veiculo.'" title="Retorno"><i class="fas fa-undo-alt"></i> </a>';
                                echo '<a class="btn btn-danger" href="#" data-toggle="modal" data-target="#modalManut'.$rota->id_veiculo.'" title="Manutenção"><i class="fas fa-tools"></i> </a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else{
                            echo 'Não há carros em rota!';
                        }
                    ?>
                </div>
            </div>
            <div class="card" id="cardManut">
                <div class="card-header">
                    <strong>Em Oficina</strong>
                </div>
                <div class="card-body">
                    <?php
                        if($QtdeManut>0){
                            foreach($EmManut as $manut){
                                echo '<div class="card"></br>';
                                echo '<center><i class="fas fa-car fa-2x" style="color: '.$manut->cor.'"></i></center>';
                                echo '<h6><strong>'.$manut->alias.'</strong> ('.$manut->placa.')</h6>';
                                $query = $bd->prepare('SELECT descricao_manut FROM tb_manutencao WHERE status_manut = 1 AND id_veiculo = :id_veiculo');
                                $query->bindParam(':id_veiculo',$manut->id_veiculo);
                                $query->execute();
                                $returnManut = $query->fetch();
                                echo '<p class="text-truncate">'.$returnManut['descricao_manut'].'</p>';
                                echo '<div class="card-footer">';
                                echo '<a class="btn btn-success col-lg-5" href="#" data-toggle="modal" data-target="#modalRetorno'.$manut->id_veiculo.'" title="Retornar da Manutenção"><i class="fas fa-clipboard-check"></i> Retornar</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else{
                            echo 'Não há carros em manutenção!';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
        require_once('footer.php');
    ?>
    
    <!-- END OF CODE -->
    <!-- Modais -->
    <?php
    require_once('modaisMenu.php');
    // Modal para Saída
    foreach($EmBase as $base){
        echo '<div class="modal fade" id="modalSaida'.$base->id_veiculo.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo '<div class="modal-dialog modal-lg" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="exampleModalLabel">Saida: '.$base->montadora.' '.$base->modelo.' ('.$base->alias.')</h5>';
        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        if($QtdeMotoristasDisp>0){
        echo '<form action="iniciarRota.php" method="post">';
        echo '<div class="form-group justify-content-center text-center">';
        echo '<label class="col-lg-5" for="inputRota">KM atual:</label>';
        echo '<input type="hidden" name="inputIDCarro" id="inputIDCarroBase'.$base->id_veiculo.'" value="'.$base->id_veiculo.'">';
        echo '<input type="hidden" name="inputUFBase" id="inputUFBase'.$base->id_veiculo.'" value="'.$base->id_uf.'">';
        echo '<input type="hidden" name="inputCidadeBase" id="inputCidadeBase'.$base->id_veiculo.'" value="'.$base->id_cidade.'">';
        echo '<input class="col-lg-5" type="text" name="inputKMatual" id="inputKMatualBase'.$base->id_veiculo.'" value="'.$base->kilometragem.'" readonly><hr/>';
        echo '<p><label class="col-lg-5" for="inputHoraRetorno">Hora de Saída:</label>';
        echo '<input class="col-lg-5" type="datetime-local" name="inputHoraSaida" id="inputHoraSaidaBase'.$base->id_veiculo.'" required></p>';
        echo '<label class="col-lg-5" for="inputDriver">Qual motorista executará o rota?</label>';
        echo '<select class="col-lg-5" name="inputDriver" id="inputDriverBase'.$base->id_veiculo.'">';
        foreach($motoristas as $motorista){
            if($motorista->disponivel==true)
                
                echo '<option value="'.$motorista->matr_user.'">'.$motorista->nome_user.'</option>';
        }
        echo '</select>';
        echo '<label class="col-lg-5" for="inputRota">Qual a rota?</label>';
        echo '<input class="col-lg-5" type="text" name="inputRota" id="inputRotaBase'.$base->id_veiculo.'" required>';
        echo '</div>';
        } else{
            echo '<div class=" justify-content-center align-center text-center">';
            echo '<h3>Não há motorista disponível para realizar a viagem.</h3>';
            echo '</div></br>';
            if($level!='OPR'){
                echo '<div class=" justify-content-center align-center text-center">';
                echo '<a href="usuarios.php" class="btn btn-info col-lg-4"><i class="far fa-id-card"></i> Add Novo Motorista</a>';
                echo '</div>';
            }
        }
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>';
        if($QtdeMotoristasDisp>0){
        echo '<button type="submit" class="btn btn-primary">Iniciar Rota</button>';
        echo '</form>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    // Modal para Retorno
    foreach($EmRota as $rota){
        echo '<div class="modal fade" id="modalSaida'.$rota->id_veiculo.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo '<div class="modal-dialog modal-lg" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="exampleModalLabel">Retorno: '.$rota->montadora.' '.$rota->modelo.' ('.$rota->alias.')</h5>';
        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '<form action="iniciarRetorno.php" method="post">';
        echo '<div class="form-group justify-content-center text-center">';
        $query = $bd->prepare('SELECT u.id_user, u.matr_user, u.nome_user, v.id_viagem, v.rota, v.data_lancamento, v.data_retorno FROM tb_users as u, tb_viagem as v WHERE u.motorista = true AND u.ativo = 1 AND u.disponivel = 0 AND u.data_validade > now() AND v.id_user = u.matr_user AND v.em_andamento = 1 AND v.id_veiculo = :id_veiculo');
        $query->bindParam(':id_veiculo',$rota->id_veiculo);
        $query->execute();
        $dirigindo = $query->fetch();
        echo '<p><label class="col-lg-5" for="nothing">Motorista: </label>';
        echo '<label class="col-lg-5" for="nothing">'.$dirigindo['nome_user'].'</label>';
        echo '<p><label class="col-lg-5" for="inputHoraSaida">Hora saida: </label>';
        echo '<input class="col-lg-5" type="datetime-local" value="'.str_replace(" ","T",$dirigindo['data_lancamento']).'" name="inputHoraSaida" id="inputHoraSaidaEmRota'.$rota->id_veiculo.'" required></p>';
        echo '<p><label class="col-lg-5" for="nothing">KM atual:</label>';
        echo '<input class="col-lg-5" type="text" name="inputKMatual" id="inputKMatualEmRota'.$rota->id_veiculo.'" value="'.$rota->kilometragem.'" readonly required></p>';
        echo '<p><label class="col-lg-5" for="inputHoraRetorno">Hora retorno:</label>';
        echo '<input class="col-lg-5" type="datetime-local" name="inputHoraRetorno" id="inputHoraRetornoEmRota'.$rota->id_veiculo.'" onblur="testarMomentoRetorno('.$rota->id_veiculo.')" required></p>';
        echo '<p><label class="col-lg-5" for="inputKMretorno">KM de retorno:</label>';
        echo '<input class="col-lg-5" type="text" name="inputKMretorno" id="inputKMretornoEmRota'.$rota->id_veiculo.'" onkeydown="fMasc(this,mNum)" onblur="testarKmRetorno('.$rota->id_veiculo.')" required></p>';
        $query = $bd->prepare('SELECT rota FROM tb_viagem WHERE id_veiculo = :id_veiculo AND em_andamento = 1');
        $query->bindParam(':id_veiculo',$rota->id_veiculo);
        $query->execute();
        $rotarealizada = $query->fetch();
        echo '<p><label class="col-lg-5" for="nothing">Rota indicada:</label>';
        echo '<label class="col-lg-5" for="nothing">'.$rotarealizada['rota'].'</label></p>';
        echo '<div class="form-check form-switch">';
        echo '<input class="form-check-input" type="checkbox" name="radioAlteracao" id="radioAlteracao'.$rota->id_veiculo.'" value="aceso" onchange="alteraRota('.$rota->id_veiculo.')">';
        echo '<label class="form-check-label" for="radioAlteracao'.$rota->id_veiculo.'"> Houve alteração na rota?</label>';
        echo '</div>';
        echo '<div id="alterado'.$rota->id_veiculo.'" style="display:none;">';
        echo '<input type="hidden" name="inputIDviagem" id="inputIDviagem" value="'.$dirigindo['id_viagem'].'">';
        echo '<input type="hidden" name="inputRota" id="inputRota" value="'.$dirigindo['rota'].'">';
        echo '<input type="hidden" name="inputDriver" id="inputDriver" value="'.$dirigindo['matr_user'].'">';
        echo '<input type="hidden" name="inputIDCarro" id="inputIDCarro" value="'.$rota->id_veiculo.'">';
        echo '<input type="hidden" name="inputUFBase" id="inputUFBase" value="'.$rota->id_uf.'">';
        echo '<input type="hidden" name="inputCidadeBase" id="inputCidadeBase" value="'.$rota->id_cidade.'">';
        echo '<label for="exampleFormControlTextarea1">Descreva a alteração</label></br>';
        echo '<textarea class="form-control " name="inputAlteracao" id="inputAlteracao" rows="3">'.$rotarealizada['rota'].'</textarea>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>';
        echo '<button type="submit" id="fimRota'.$rota->id_veiculo.'" class="btn btn-primary">Finalizar</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    // Modal para Manutenção
    foreach($PodeManut as $irmanut){
        echo '<div class="modal fade" id="modalManut'.$irmanut->id_veiculo.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo '<div class="modal-dialog modal-lg" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="exampleModalLabel">Manutenção: '.$irmanut->montadora.' '.$irmanut->modelo.' ('.$irmanut->alias.')</h5>';
        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        echo '<form id="irManut'.$irmanut->id_veiculo.'" action="irManut.php" method="post">';
        echo '<div class="form-group justify-content-center text-center">';
        echo '<label class="col-lg-5" for="inputRota">KM atual:</label>';
        echo '<input type="hidden" name="inputIDCarro" id="inputIDCarro" value="'.$irmanut->id_veiculo.'">';
        echo '<input type="hidden" name="inputUFBase" id="inputUFBase" value="'.$irmanut->id_uf.'">';
        echo '<input type="hidden" name="inputCidadeBase" id="inputCidadeBase" value="'.$irmanut->id_cidade.'">';
        echo '<input class="col-lg-5" type="text" name="inputKMatual" id="inputKMatualManut'.$irmanut->id_veiculo.'" value="'.$irmanut->kilometragem.'" readonly><hr/>';
        echo '<label class="col-lg-5" for="inputTipoManutencao">Tipo de Manutenção:</label>';
        echo '<select class="col-lg-5" id="inputTipoManutencao'.$irmanut->id_veiculo.'" name="inputTipoManutencao">';
        echo '<option value="1">Programada</option>';
        echo '<option value="0">Não programada</option>';
        echo '</select>';
        echo '<label name="lblinputKMProg" id="lblinputKMProg'.$irmanut->id_veiculo.'" class="col-lg-5" for="inputKMProg">Revisão de:</label>';
        echo '<select class="col-lg-5" name="inputKMProg" id="inputKMProg'.$irmanut->id_veiculo.'">';
        echo '<option value="10000">10000 KM</option>';
        echo '<option value="20000">20000 KM</option>';
        echo '<option value="30000">30000 KM</option>';
        echo '<option value="40000">40000 KM</option>';
        echo '<option value="50000">50000 KM</option>';
        echo '<option value="60000">60000 KM</option>';
        echo '<option value="70000">70000 KM</option>';
        echo '<option value="80000">80000 KM</option>';
        echo '<option value="90000">90000 KM</option>';
        echo '<option value="100000">100000 KM</option>';
        echo '</select>';
        echo '<label class="col-lg-5" for="inputDataManut">Data da Manutenção</label>';
        echo '<input class="col-lg-5" type="datetime-local" name="inputDataManut" id="inputDataManut" required>';
        echo '<label class="col-lg-5" for="inputLocalManut">Nome da Oficina:</label>';
        echo '<input class="col-lg-5" type="text" name="inputLocalManut" id="inputLocalManut" min="3" max="70" required>';
        echo '</br><label class="col-lg-5" for="inputDescricao">Descrição</label></br>';
        echo '<textarea class="col-lg-8" name="inputDescricao" id="inputDescricao" rows="5"></textarea>';
        echo '</div>';
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>';
        echo '<button type="submit" class="btn btn-primary">Enviar à Manutenção</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    // Modal para Retorno da Manutenção
    foreach($EmManut as $voltamanut){
        echo '<div class="modal fade" id="modalRetorno'.$voltamanut->id_veiculo.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        echo '<div class="modal-dialog modal-lg" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="exampleModalLabel">Manutenção: '.$voltamanut->montadora.' '.$voltamanut->modelo.' ('.$voltamanut->alias.')</h5>';
        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        $query = $bd->prepare('SELECT id_manut, data_manut, local_manut, descricao_manut FROM tb_manutencao WHERE status_manut = 1 AND id_veiculo = :id_veiculo');
        $query->bindParam(':id_veiculo',$voltamanut->id_veiculo);
        $query->execute();
        $retornoManut = $query->fetch();
        echo '<form id="irManut'.$voltamanut->id_veiculo.'" action="retornaManut.php" method="post">';
        echo '<div class="form-group justify-content-center text-center">';
        echo '<input type="hidden" name="inputIDManut" id="inputIDManut" value="'.$retornoManut['id_manut'].'">';
        echo '<input type="hidden" name="inputIDCarro" id="inputIDCarro" value="'.$voltamanut->id_veiculo.'">';
        echo '<p><label class="col-lg-5" for="inputKMIdaManut">KM de ida à manutenção:</label>';
        echo '<input class="col-lg-5" type="text" name="inputKMIdaManut" id="inputKMIdaManut'.$voltamanut->id_veiculo.'" value="'.$voltamanut->kilometragem.'" readonly required></p>';
        echo '<label class="col-lg-5" for="inputRota">KM de retorno à base:</label>';
        echo '<input class="col-lg-5" type="number" name="inputKMRetorno" id="inputKMRetornoManut'.$voltamanut->id_veiculo.'" min="'.$voltamanut->kilometragem.'" onkeydown="fMasc(this,mNum)" onblur="testarKmManut('.$voltamanut->id_veiculo.')" required><hr/>';
        echo '<label class="col-lg-5" for="inputLocalManut">Nome da Oficina:</label>';
        echo '<input class="col-lg-5" type="text" name="inputLocalManut" id="inputLocalManut'.$voltamanut->id_veiculo.'" min="3" max="70" value="'.$retornoManut['local_manut'].'" required>';
        echo '<label class="col-lg-5" for="inputDataManut">Data de Ida</label>';
        echo '<input class="col-lg-5" type="datetime-local" name="inputDataIda" id="inputDataIdaManut'.$voltamanut->id_veiculo.'" value="'.str_replace(" ","T",$retornoManut['data_manut']).'" readonly required>';
        echo '<label class="col-lg-5" for="inputDataManut">Data de Retorno</label>';
        echo '<input class="col-lg-5" type="datetime-local" name="inputDataRetorno" id="inputDataRetornoManut'.$voltamanut->id_veiculo.'" min="'.str_replace(" ","T",$retornoManut['data_manut']).'" onblur="testarMomentoManut('.$voltamanut->id_veiculo.')" required>';
        echo '<label class="col-lg-5" for="inputValorManut">Valor Total</label>';
        echo '<input class="col-lg-5" type="text" name="inputValorManut" id="inputValorManut" onkeydown="fMasc(this,mCash)" required>';
        echo '</br><label class="col-lg-5" for="inputDescricao">Descrição</label></br>';
        echo '<textarea class="col-lg-8" name="inputDescricao" id="inputDescricao" rows="5" required>'.$retornoManut['descricao_manut'].'</textarea>';
        echo '</div>';
        echo '</div>';
        echo '<div class="modal-footer">';
        echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>';
        echo '<button type="submit" id="btnRetornoManut'.$voltamanut->id_veiculo.'" class="btn btn-primary">Retornar da Manutenção</button>';
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/js/register.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>