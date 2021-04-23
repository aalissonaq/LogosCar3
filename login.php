<!DOCTYPE html>
<html>
<head>
    <?php
        require_once('config.php');
    ?>
    <title> <?php echo NOMESYS;?> </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Fabio Henrique Silva furtado" content="">

    <link rel="shortcut icon" href="img/icon.png" type="image/x-icon" />

    
    <!-- jQuery AJax-->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo BASE;?>/js/signature.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/carga.css">

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
            echo '<style>#nav,#footer{background-color:#cfa306 !important;}</style>';
        }
        if($mes=='10'){
            echo '<style>#nav,#footer{background-color:#ff4787 !important;}</style>';
        }
        if($mes=='11'){
            echo '<style>#nav,#footer{background-color:#0646cf !important;}</style>';
        }
    $mobile = FALSE;
   $user_agents = array("iPhone","iPad","Android","webOS","BlackBerry","iPod","Symbian","IsGeneric");
    $modelo = "Desktop";
   foreach($user_agents as $user_agent){
     if (strpos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== FALSE) {
        $modelo	= $user_agent;
        $mobile = TRUE;
	    break;
     }
   }
    if(isset($_POST['inputLogin']) && isset($_POST['inputSenha'])){
        require_once('src/conn/connect.php');
        $user = @$_POST['inputLogin'];
        $psw = @md5($_POST['inputSenha']);
        $stmt = $bd->prepare('SELECT * FROM tb_users');
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        //$statusInternet = exec("ping www.google.com.br",$output,$resultadoPing);
        if($resultadoPing == 1){
            $meuip['ip'] = $_SERVER['REMOTE_ADDR'];
        } else {
            $meuip = json_decode(file_get_contents('https://api.ipify.org?format=json'),true);
        }
        foreach($result as $res){
            if($user == $res->matr_user && $psw == $res->senha){
                if($res->ativo == 1){
                    $save = $bd->prepare('INSERT INTO tb_log (id_user,uf,cidade,ip,dispositivo,data_login,acao) VALUES (:user,:uf,:cidade,:ip,:dispositivo,NOW(),:acao)');
                    $save->bindParam(':user',$res->id_user);
                    $save->bindParam(':uf',$res->uf);
                    $save->bindParam(':cidade',$res->cidade);
                    $save->bindParam(':dispositivo',$modelo);
                    $save->bindParam(':ip',$meuip['ip']);
                    $acao = 'efetuou o login via '.$modelo;
                    $save->bindParam(':acao',$acao);
                    $save->execute();
                    session_start();
                    $_SESSION['user'] = $res->id_user;
                    $_SESSION['uf'] = $res->uf;
                    $_SESSION['cidade'] = $res->cidade;
                    $_SESSION['nivel'] = $res->nivel;
                    $_SESSION['isMobile'] = $mobile;
                    require_once('session.php');
                    header('Location: sys.php');
                    exit();
                } else{
                    header('Location: index.php?erro=0');
                }
            } else{
                header('Location: index.php?erro=1');
            }
        }
    }
    ?>
</head>
<body>
    <div id="sidebar">
        <nav id="nav" class="navbar navbar-collapse navbar-expand static-top" role="navigation" style="margin-bottom: 0">
        </nav>
    </div>
    </br></br></br></br>
    <div id="loading" style="display: block" class="loading" align="center">
		<img src="img/preloader.gif"><br>
	    Carregando...
	</div>
    <div class="footer" id="footer" align="center">
        Powered by: <img src="img/logos.png" width="90px" alt=""> &copy;
    </div>
    
    <!-- END OF CODE -->

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo BASE;?>/vendor/jquery/jquery.js"></script>
    <script src="<?php echo BASE;?>/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page level plugin JavaScript-->
    <script src="<?php echo BASE;?>/vendor/DataTables-1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/DataTables-1.10.23/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/all.min.js"></script>
    <script src="<?php echo BASE;?>/vendor/font-awesome/js/fontawesome.min.js"></script>
    </body>
</html>