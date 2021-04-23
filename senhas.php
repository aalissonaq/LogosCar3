<!DOCTYPE html>
<html>
<?php
    require_once('config.php');
    require_once('session.php');
    require_once('isMobile.php');
    require_once('header.php');
    if($level=='MTR'){
        $query = $bd->prepare('SELECT * FROM tb_users WHERE ativo = 1');
    } else{
        $query = $bd->prepare('SELECT * FROM tb_users WHERE uf = :myuf AND ativo = 1');
        $query->bindParam(':myuf',$myuf);
    }
    $query->execute();
    $usuarios = $query->fetchAll(PDO::FETCH_OBJ);
    $hoje = date('d/m/Y');
?>
<head>
    <link rel="stylesheet" href="<?php echo BASE;?>/css/sys.css">
</head>
<body>
    <div id="sidebar">
    <nav id="nav" class="navbar navbar-collapse navbar-expand static-top" role="navigation" style="margin-bottom: 0">
            <a class="navbar-brand" href="sys.php"><img src="img/logo.png" width="150" class="d-inline-block align-top" alt="<?php echo NOMESYS;?>"></a>
            <?php if($isMobile==false){ ?>
            <div class="btn-group btn-group-sm" id="botoesNav" role="group" aria-label="Menu <?php echo NOMESYS;?>">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-outline-light" href="sys.php"><i class="fas fa-tachometer-alt"></i> Início</a>
                    <a class="btn btn-outline-light hover-black" data-toggle="modal" data-target="#modalConfig"><i class="fas fa-cogs"></i> Configurações</a>
                    <a class="btn btn-outline-light hover-black" data-toggle="modal" data-target="#modalHistory"><i class="fas fa-history"></i> Histórico</a>
                </div>
            </div>
            <?php } ?>
            <div id="btnSair">
                <?php
                    $hora = date('H');
                    if($hora>=0 && $hora <=11){
                        echo '<span class="text-white">Bom Dia, '.$nome_curto[0] .'</span>';
                    } elseif ($hora>=12 && $hora <=17){
                        echo '<span class="text-white">Boa Tarde, '.$nome_curto[0] .'</span>';
                    } else{
                        echo '<span class="text-white">Boa Noite, '.$nome_curto[0] .'</span>';
                    }
                ?>
                <a class="btn btn-outline-light" href="signout.php" onclick="return confirm('Tem certeza que deseja sair?');">Sair</a>
            </div>
        </nav>
        <?php if($isMobile){?>
        <nav id="nav-secundary" class="justify-content-center text-center">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a class="btn btn-secondary" href="sys.php"><i class="fas fa-tachometer-alt fa-3x"></i></a>
                <a class="btn btn-secondary text-white hover-black" data-toggle="modal" data-target="#modalConfig"><i class="fas fa-cogs fa-3x"></i></a>
                <a class="btn btn-secondary text-white hover-black" data-toggle="modal" data-target="#modalHistory"><i class="fas fa-history fa-3x"></i></a>
            </div>
        </nav><br/>
        <?php } ?>
    </div>
    <div id="loading" style="display: block" class="loading" align="center">
		<img src="<?php echo BASE;?>/img/preloader.gif"><br>
	    Carregando...
	</div>
    <div id="content" class="content container-fluid justify-content-center text-center" style="display: none">
        <div class="container">
            <?php
                if(!@$_GET['psw']==''){
                    if($_GET['psw']==1){
                        echo '<div id="avisoGet" class="alert alert-success" role="alert"> Senha resetada com sucesso. A senha padrão é: logos@*login* (onde substitui-se "login" pela matrícula).</div>';
                    } else{
                        echo '<div id="avisoGet" class="alert alert-danger" role="alert"> Ops! Não foi possível alterar a senha... </div>';
                    }
                }
            ?>
            <div id="teste">
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Senhas</h4>
                </div>
                <div class="card-body">
                    <table id="listaUsuarios" class="table table-striped table-bordered <?php if($isMobile){ echo 'table-responsive';}?>" style="width:100%">
                        <thead>
                            <tr>
                                <th style="display:none;">ID</th>
                                <th>Nome Completo</th>
                                <th>Localidade</th>
                                <th>Nível</th>
                                <th>Login</th>
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
                                    // LOGIN
                                    echo '<td>'.$usuario->matr_user.'</td>';
                                    
                                    // OPÇÕES
                                    echo '<td> <button class="btn btn-dark" data-toggle="modal" data-target="#modalAltSenha'.$usuario->id_user.'"><i class="fas fa-key"></i></button> </td>';
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
        require_once('footer.php');
    ?>
    <!-- Modal Add Veículo -->
    <?php
        foreach($usuarios as $usuario){
            echo '<div class="modal fade" id="modalAltSenha'.$usuario->id_user.'" tabindex="-1" role="dialog" aria-labelledby="modalAddVeiculo" aria-hidden="true">';
                echo '<div class="modal-dialog" role="document">';
                    echo '<div class="modal-content">';
                        echo '<div class="modal-header">';
                            echo '<h5 class="modal-title" id="exampleModalLabel">Resetar Senha</h5>';
                            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                                echo '<span aria-hidden="true">&times;</span>';
                            echo '</button>';
                        echo '</div>';
                        echo '<div class="modal-body">';
                            echo '<h6>Tens certeza que deseja resetar a senha de '.$usuario->nome_user.'?</h6>';
                        echo '</div>';
                        echo '<div class="modal-footer">';
                            echo '<form id="formAddVeiculo" action="resetSenhaUser.php" method="post">';
                            echo '<input type="hidden" name="inputUserID" id="inputUserID" value="'.$usuario->id_user.'">';
                            echo '<button type="reset" class="btn btn-secondary">Limpar</button>';
                            echo '<button type="submit" class="btn btn-success">Salvar</button>';
                            echo '</form>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        }
    ?>
    
    <!-- END OF CODE -->
        <!-- Modais -->
    <?php
        require_once('modaisMenu.php');
    ?>

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
    <script src="<?php echo BASE;?>/js/masks.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>