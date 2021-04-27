<?php
    require_once('config.php');
    require_once('isMobile.php');
    $msgGet = NULL;
    if(isset($_GET['erro'])){
        $action = @$_GET['erro'];
        if($action==0){
            $msgGet = '<div class="alert alert-danger" role="alert"> Usuário desabilitado. Favor contacte o gestor para averiguar seu status. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
        if($action==1){
            $msgGet = '<div class="alert alert-danger" role="alert"> Ops... Usuário e/ou senha não conferem. Verifique seus dados e tente novamente. <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
        }
    }
?>
<!DOCTYPE html>
<html>
<head>

    <title> <?php echo NOMESYS;?> </title>
    <meta charset="utf-8" />
    <meta name="Fabio Henrique Silva furtado" content="">

    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon" />

    <!-- Manifest JSON-->
    <link rel="manifest" href="<?php echo BASE;?>/manifest.json">
    <!-- jQuery AJax-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE;?>/js/signature.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/carga.css">

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Page level plugin CSS-->
    <link href="vendor/DataTables-1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="vendor/font-awesome/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/font-awesome/css/fontawesome.min.css" rel="stylesheet" type="text/css">
    <?php if($isMobile){?>
    <style>
        #login{
            margin-top: 15vh !important;
            height: 80% !important;
        }
        h5{
             font-size: 70px !important;
        }
        #logo{
            width: 90% !important;
        }
        #logo-footer{
            width: 140px !important;
        }
        #btnLogin{
            width: 35%;
            height: 90px;
            font-size: 40px;
        }
        #inputLogin, #inputSenha{
            height: 60px;
            font-size: 30px;
        }
        #greetings{
            font-size: 25px;
        }

    </style>
    <?php } ?>
    <?php
        $mes = date('m');
        if($mes=='09'){
            echo '<style>#nav,#footer{background-color:#cfa306 !important;}</style>';
        }
        if($mes=='10'){
            echo '<style>#nav,#footer{background-color:#ff4787 !important;}</style>';
        }
        if($mes=='11'){
            echo '<style>#nav,#footer{background-color:#0646cf !important;}</style>';
        }
    ?>
</head>
<body>
    <div id="sidebar">
        <nav id="nav" class="navbar navbar-collapse navbar-expand static-top" role="navigation" style="margin-bottom: 0">
        <?php
            $hora = date('H');
            if($hora>=0 && $hora <=11){
                echo '<span id="greetings" class="text-white">Olá, Bom Dia!</span>';
            } elseif ($hora>=12 && $hora <=17){
                echo '<span id="greetings" class="text-white">Olá, Boa Tarde!</span>';
            } else{
                echo '<span id="greetings" class="text-white">Olá, Boa Noite!</span>';
            }
            if($mes=='09'){
                echo '<a class="navbar-brand" href="#"  data-toggle="tooltip" data-placement="right" title="Setembro Amarelo"><img src="img/campanha/'.$mes.'.png" height="40px" class="d-inline-block align-top" alt="'. NOMESYS .'"></a>';
            }
            if($mes=='10'){
                echo '<a class="navbar-brand" href="#"  data-toggle="tooltip" data-placement="right" title="Outubro Rosa"><img src="img/campanha/'.$mes.'.png" height="40px" class="d-inline-block align-top" alt="'. NOMESYS .'"></a>';
            }
            if($mes=='11'){
                echo '<a class="navbar-brand" href="#"  data-toggle="tooltip" data-placement="right" title="Novembro Azul"><img src="img/campanha/'.$mes.'.png" height="40px" class="d-inline-block align-top" alt="'. NOMESYS .'"></a>';
            }
        ?>
        </nav>
    </div>
    <div id="content" class="content container-fluid justify-content-center text-center">
        <div class="container">
            <?php echo $msgGet;?>
            <div class="row justify-content-center text-center">
                <div id="login" class="col-lg-4 card">
                    <div class="card-header">
                        <a class="navbar-brand mr-1" href="index.php"><img src="img/logo.png" width="220" id="logo" alt="R&S Logos"></a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Login</h5>
                        <form id="loginLC3" action="login.php" method="POST">
                            <p class="card-text">
                                <div class="form-group">
                                    <input type="text" class="form-control <?php if($isMobile){ echo 'form-control-lg';}?>" id="inputLogin" name="inputLogin" placeholder="Seu usuário" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control <?php if($isMobile){ echo 'form-control-lg';}?>" id="inputSenha" name="inputSenha" placeholder="Sua senha" required>
                                </div>
                                <button id="btnLogin" type="submit" class="btn btn-outline-primary">Entrar</button>
                            </p>
                        </form>
                    </div>
                </div>
            </div></br>
        </div>
    </div>
    <div class="footer" id="footer" align="center">
        Powered by: <img src="img/logos.png" width="90px" id="logo-footer" alt=""> &copy;
    </div>
    <script>
        $('#login').hide();
        $('#login').fadeIn(1000);
    </script>
    
    <!-- END OF CODE -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="<?php echo BASE;?>/vendor/DataTables-1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/DataTables-1.10.23/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="vendor/font-awesome/js/all.min.js"></script>
    <script src="vendor/font-awesome/js/fontawesome.min.js"></script>
    <script src="js/index.js"></script>
    <script src="sw.js"></script>
</body>
</html>