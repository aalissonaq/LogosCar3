<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    if($level=='OPR')
        header('Location: sys.php');
    if($level=='MTR')
        $query = $bd->prepare('SELECT * FROM tb_users');
    if($level=='ADM'){
        $query = $bd->prepare('SELECT * FROM tb_users WHERE uf = :myuf');
        $query->bindParam(':myuf',$myuf);
    }
    $query->execute();
    $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
    $hoje = date('d/m/Y');
?>
<head>
    <link rel="stylesheet" href="<?php echo BASE;?>/css/sys.css">
    <style>
        #rules{
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php
        require_once('nav.php');
    ?>
    <div id="loading" style="display: block" class="loading" align="center">
		<img src="<?php echo BASE;?>/img/preloader.gif"><br>
	    Carregando...
	</div>
    <div id="content" class="content container-fluid justify-content-center text-center" style="display: none">
        <div class="container">
            <?php
                if(!@$_GET['add']==''){
                    if($_GET['add']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Usuário cadastrado com sucesso </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível efetuar o cadastro... </div>';
                    }
                }
                if(!@$_GET['edt']==''){
                    if($_GET['edt']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Usuário editado com sucesso </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível editar o cadastro... </div>';
                    }
                }
                if(!@$_GET['psw']==''){
                    if($_GET['psw']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Senha alterada com sucesso </div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível alterar a senha do usuário... </div>';
                    }
                }
            ?>
            <div id="teste">
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Usuários</h4>
                </div>
                <div class="card-body badge-info">
                    <button class="btn btn-outline-light col-lg-5" id="btnAddVeiculo" data-toggle="modal" data-target="#modalAddUsuario"><i class="fas fa-plus"></i> Adicionar Usuário </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Lista de Usuários</h5>
                    <table id="listaUsuarios" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th style="display:none;">ID</th>
                                <th>Nome Completo</th>
                                <th>Localidade</th>
                                <th>Nível</th>
                                <th>Motorista?</th>
                                <th>CNH</th>
                                <th>Data Emissão</th>
                                <th>Data Validade</th>
                                <th>Ativo?</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($usuarios as $usuario){
                                    echo '<tr>';
                                    // ID
                                    echo '<td style="display:none;">'.$usuario->id_user.'</td>';
                                    // NOME DO USUÁRIO
                                    echo '<td>'.$usuario->nome_user.'</td>';
                                    // LOCAL
                                    echo '<td>'.$usuario->cidade.'/'.$usuario->uf.'</td>'; 
                                    // NÍVEL
                                    if($usuario->nivel == 'OPR'){
                                        echo '<td> Operação </td>';
                                    }
                                    if($usuario->nivel == 'ADM'){
                                        echo '<td> Administrador </td>';
                                    }
                                    if($usuario->nivel == 'MTR'){
                                        echo '<td> Master </td>';
                                    }
                                    // É MOTORISTA?
                                    if($usuario->motorista == 1)
                                        echo '<td><span style="display:none">Sim</span><abbr title="Sim" class="initialism"><i class="fas fa-check-circle fa-2x" style="color: green"></i></abbr></td>';
                                    elseif($usuario->motorista == 0)
                                        echo '<td><span style="display:none">Não</span><abbr title="Não" class="initialism"><i class="fas fa-times-circle fa-2x" style="color: red"></i></abbr></td>';
                                    elseif(($usuario->motorista == 1) && (date('d/m/Y') > date('d/m/Y', strtotime($usuario->data_validade))))
                                        echo '<td><span style="display:none">B</span><abbr title="CNH Vencida!" class="initialism"><i class="fas fa-exclamation-triangle fa-2x" style="color: yellow"></i></abbr></td>';
                                    // NUMERO DA CNH
                                    if($usuario->cnh != NULL){
                                        echo '<td>'.$usuario->cnh.'</td>';
                                    } else{
                                        echo '<td> -- --</td>';
                                    }
                                    // DATA DE EMISSÃO
                                    if($usuario->data_emissao == NULL){
                                        echo '<td> -- --</td>';
                                    } else{
                                        echo '<td>'.date('d/m/Y', strtotime($usuario->data_emissao)).'</td>';
                                    }
                                    // DATA DE VALIDADE
                                    if($usuario->data_validade == NULL){
                                        echo '<td> -- --</td>';
                                    } else{
                                        echo '<td>'.date('d/m/Y', strtotime($usuario->data_validade)).'</td>';
                                    }
                                    if($usuario->ativo == true){
                                        echo '<td><i class="fas fa-check-square" style="color: green;" title="Ativo"></i></td>';
                                    } else{
                                        echo '<td><i class="fas fa-times-square" style="color: red;" title="Inativo"></i></td>';
                                    }
                                    // OPÇÕES
                                    echo '<td> <button class="btn btn-warning btn-sm" onclick="editUser('.$usuario->id_user.')"><i class="fas fa-user-edit"></i></button>';
                                    echo '<button class="btn btn-info btn-sm" onclick="editPassword('.$usuario->id_user.')"><i class="fas fa-key"></i></button></td>';
                                    echo '</tr>';                                 
                                }
                            ?>
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    </div>
    <?php
        $json = json_encode($matr_exists);
        require_once('footer.php');
    ?>
    <!-- Modal Add Veículo -->
    <div class="modal fade" id="modalAddUsuario" tabindex="-1" role="dialog" aria-labelledby="modalAddVeiculo" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Adicionar Usuário</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAddUsuario" action="addusuario.php" method="post">
                <div class="form-group justify-content-center text-center">
                    <label for="inputNomeCompleto inputMatricula">Colaborador:</label></br>
                    <input class="col-lg-8" type="text" name="inputNomeCompleto" id="inputNomeCompleto" placeholder="Nome Completo" required>
                    <input class="col-lg-3" type="text" name="inputMatricula" id="inputMatricula" placeholder="Matrícula" onkeydown="fMasc(this,mNum)" maxlength="5" required>
                </div>
                <div class="form-group justify-content-center text-center">
                    <labelfor="inputNivel">Nível:</label></br>
                    <select class="col-lg-11" name="inputNivel" id="inputNivel">
                        <option value="OPR">Operação</option>
                        <option value="ADM">Administrador</option>
                    <?php if($level=='MTR'){ ?>
                        <option value="MTR">Máster</option>
                    <?php } ?>
                    </select>
                </div>
                <div class="form-group justify-content-center text-center">
                    <label for="inputUF inputCidade">Lotação:</label></br>
                    <select class="col-lg-3" name="inputUF" id="inputUF">
                    <!-- Cidades -->
                    </select>
                    <select class="col-lg-8" name="inputCidade" id="inputCidade">
                    <!-- Cidades -->
                    </select>
                </div>
                <div class="form-group justify-content-center text-center">
                    <label class="col-lg-5" for="radioMotorista">Será motorista?</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioMotorista" id="motoraSim" value="1">
                        <label class="form-check-label" for="exampleRadios1">
                            Sim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="radioMotorista" id="motoraNao" value="0" checked>
                        <label class="form-check-label" for="exampleRadios2">
                            Não
                        </label>
                    </div>
                </div>
                <div class="form-group justify-content-center text-center" id="dadosMotorista">
                    <hr/>
                    <label class="col-lg-5" for="inputNumCNH">Nº da CNH:</label>
                    <input class="col-lg-5" type="tel" name="inputNumCNH" id="inputNumCNH" onkeydown="fMasc(this,mNum)">
                    <label class="col-lg-5" for="inputDataEmissao">Categoria:</label>
                    <select class="col-lg-5" name="inputCategoria" id="inputCategoria">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="C">C</option>
                        <option value="AC">AC</option>
                        <option value="D">D</option>
                        <option value="AD">AD</option>
                    </select></br>
                    <label class="col-lg-5" for="inputDataEmissao">Data de Emissão:</label>
                    <input class="col-lg-5" type="date" name="inputDataEmissao" id="inputDataEmissao">
                    <label class="col-lg-5" for="inputDataValidade">Data de Validade:</label>
                    <input class="col-lg-5" type="date" name="inputDataValidade" id="inputDataValidade">
                </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary">Limpar</button>
                <button type="submit" id="salvarNovoColab" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    
    <!-- END OF CODE -->
    <!-- Modais -->
    <?php
        require_once('modaisMenu.php');
    ?>
    <!-- Modal Alterar Senha-->
    <div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="modalPassword" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modalPasswordLabel"></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modalEdtPasswd" action="edtpasswd.php" method="post">
                        <input type="hidden" name="inputID" id="inputID">
                        <div class="row">
                            <div class="form-group justify-content-center text-center">
                                <label class="col-lg-5" for="novaSenha">Digite a nova senha:</label>
                                <input class="col-lg-5" type="password" name="novaSenha" id="novaSenha" required>
                            <div class="form-group justify-content-center text-center">
                                <label class="col-lg-5" for="novaSenha">Confirme a nova senha:</label>
                                <input class="col-lg-5" type="password" name="confirmaSenha" id="confirmaSenha" required>
                            </div>
                        </div>
                </div>
                    <div class="modal-body" id="rules"><hr>
                        <label for="ul li">Requisitos:</label><hr>
                        <ul>
                            <li id="rule1">Letra maiúscula</li>
                            <li id="rule2">Letra minúscula</li>
                            <li id="rule3">Numeral</li>
                            <li id="rule4">Caractere especial (!,@,#,$, etc...)</li>
                        </ul>
                    </div>
                <div class="modal-footer">
                    <button type="reset" id="cleanPasswd" class="btn btn-secondary">Limpar</button>
                    <button type="submit" id="submitPasswd" class="btn btn-primary">Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Alterar Senha-->
    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- DataTables -->
    <script type="text/javascript" src="vendor/datatables.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/register.js"></script>
    <script src="<?php echo BASE;?>/js/usuarios.js"></script>
    <script src="<?php echo BASE;?>/js/ajax.js"></script>
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>