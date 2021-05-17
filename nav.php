<div id="sidebar">
    <nav id="nav" class="navbar navbar-collapse navbar-expand static-top" role="navigation" style="margin-bottom: 0">
        <a class="navbar-brand" href="sys.php"><img src="img/logo.png" width="150" class="d-inline-block align-top" alt="<?php echo NOMESYS;?>"></a>
        <?php if($isMobile==false){ ?>
        <div class="btn-group btn-group-sm" id="botoesNav" role="group" aria-label="Menu <?php echo NOMESYS;?>">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a class="btn btn-outline-light" href="sys.php"><i class="fas fa-tachometer-alt"></i> Início</a>
                <?php if($level!='OPR'){?>
                <a class="btn btn-outline-light hover-black" data-toggle="modal" data-target="#modalConfig"><i class="fas fa-cogs"></i> Opções</a>
                <?php } ?>
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
            <?php if($level!='OPR'){?>
            <a class="btn btn-secondary text-white hover-black" data-toggle="modal" data-target="#modalConfig"><i class="fas fa-cogs fa-3x"></i></a>
            <?php }?>
            <a class="btn btn-secondary text-white hover-black" data-toggle="modal" data-target="#modalHistory"><i class="fas fa-history fa-3x"></i></a>
        </div>
    </nav><br/>
    <?php } ?>
</div>