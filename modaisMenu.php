<!-- Modais -->
    <!-- Modal Configurações -->
    <div class="modal fade" id="modalConfig" tabindex="-1" role="dialog" aria-labelledby="modalConfig" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-cogs"></i> Configurações</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if($level=='MTR'){?>
                    <a href="log.php" class="btn btn-dark btn-lg btn-block text-white"><i class="fas fa-eye"></i> Log</a>
                    <?php } if($level!='OPR'){?>
                    <a href="fuel.php" class="btn btn-info btn-lg btn-block text-white"><i class="fas fa-gas-pump"></i> Abastecimento</a>
                    <a href="licenciamento.php" class="btn btn-primary btn-lg btn-block text-white"><i class="fas fa-file-invoice-dollar"></i> Licenciamento</a>
                    <a href="multas.php" class="btn btn-info btn-lg btn-block text-white"><i class="fas fa-exclamation-triangle"></i> Multas</a>
                    <a href="usuarios.php" class="btn btn-primary btn-lg btn-block text-white"><i class="fas fa-id-card"></i> Usuários</a>
                    <a href="veiculos.php" class="btn btn-info btn-lg btn-block text-white"><i class="fas fa-car-alt"></i> Veiculos</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Configurações -->
    <!-- Modal Histórico-->
    <div class="modal fade" id="modalHistory" tabindex="-1" role="dialog" aria-labelledby="modalHistory" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-history"></i> Histórico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="hst_mov.php" class="btn btn-primary btn-lg btn-block text-white"><i class="fas fa-car-alt"></i> Movimentações</a>
                    <a href="hst_manut.php" class="btn btn-info btn-lg btn-block text-white"><i class="fas fa-tools"></i> Manutenções</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Histórico-->