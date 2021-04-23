<?php
    require_once('../config.php');
    require_once('../session.php');
    require_once('../isMobile.php');
    // OPERAÇÃO NÃO DEVE ACESSAR ESSE PAINEL
    if($level=="OPR"){
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html>
<head>

    <title> <?php echo NOMESYS;?> </title>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Fabio Henrique Silva furtado" content="">

    <link rel="shortcut icon" href="<?php echo BASE;?>/img/icon.png" type="image/x-icon" />

    <!-- Manifest JSON-->
    <link rel="manifest" href="<?php echo BASE;?>/manifest.json">
    <!-- jQuery AJax-->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE;?>/js/signature.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE;?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE;?>/css/carga.css">

    <!-- Bootstrap core CSS-->
    <link href="<?php echo BASE;?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Page level plugin CSS-->
    <link href="<?php echo BASE;?>/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="<?php echo BASE;?>/vendor/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo BASE;?>/vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">

    <?php
    $mes = date('m');
    if($mes=='09'){
        echo '<style>#nav,#footer{background-color:#f9be07 !important;}</style>';
    }
    if($mes=='10'){
        echo '<style>#nav,#footer{background-color:#ff4787 !important;}</style>';
    }
    if($mes=='11'){
        echo '<style>#nav,#footer{background-color:#42a7a4 !important;}</style>';
    }
    ?>
    <script type="text/javascript">
    $(window).load(function() {
        document.getElementById("loading").style.display = "none";
        document.getElementById("content").style.display = "inline";
    });
    </script>
    <style>
        #botoesNav{
            margin-left: 7%;
        }
        #btnSair{
            position: absolute;
            right: 3%;
        }
        #nav-secundary{
            background-color: black;
            height: 33px;
        }
        #content{
            margin-top: 75px;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <nav id="nav" class="navbar navbar-collapse navbar-expand static-top" role="navigation" style="margin-bottom: 0">
            <a class="navbar-brand" href="index.php"><img src="../img/logo2.png" width="150" class="d-inline-block align-top" alt="<?php echo NOMESYS;?>"></a>
            <?php if($isMobile==false){ ?>
            <div class="btn-group btn-group-sm" id="botoesNav" role="group" aria-label="Menu <?php echo NOMESYS;?>">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <?php if($level!='OPR'){?>
                    <a class="btn btn-outline-light" href="config.php"><i class="fas fa-cogs"></i> Configurações</a>
                    <?php }?>
                    <a class="btn btn-outline-light" href="register.php"><i class="fas fa-tachometer-alt"></i> Registrar</a>
                    <a class="btn btn-outline-light" href="history.php"><i class="fas fa-history"></i> Histórico</a>
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
                <?php if($level!='OPR'){?>
                <a class="btn btn-secondary" href="config.php"><i class="fas fa-cogs fa-3x"></i></a>
                <?php }?>
                <a class="btn btn-secondary" href="register.php"><i class="fas fa-tachometer-alt fa-3x"></i></a>
                <a class="btn btn-secondary" href="history.php"><i class="fas fa-history fa-3x"></i></a>
            </div>
        </nav>
        <?php } ?>
    </div>
    <div id="loading" style="display: block" class="loading" align="center">
		<img src="<?php echo BASE;?>/img/preloader.gif"><br>
	    Carregando...
	</div>
    <div id="content" class="content container-fluid justify-content-center text-center" style="display: none">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h4>Configurações
                    </h4>
                </div>
                <div class="card-body btn btn-info" id="gVeiculos">
                    <h5 class="card-title">Gerenciar Frota</h5>
                    <p class="card-text">Adicionar, alterar ou remover a sua frota</p>
                </div>
                <div class="card-body btn btn-light" id="gUsers">
                    <h5 class="card-title">Gerenciar Usuários</h5>
                    <p class="card-text">Adicionar, alterar ou remover os motoristas</p>
                </div>
                <div class="card-body btn btn-info" id="gFranquia">
                    <h5 class="card-title">Gerenciar Franquia</h5>
                    <p class="card-text">Efetuar alterações da quilometragem da equipe</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer" id="footer" align="center">
        Powered by: <img src="<?php echo BASE;?>/img/logos.png" width="90px" alt=""> &copy;
    </div>
    
    <!-- END OF CODE -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="<?php echo BASE;?>/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="<?php echo BASE;?>/js/config.js"></script>
    <script src="<?php echo BASE;?>/sw.js"></script>
    </body>
</html>