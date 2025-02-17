    <!--------- MODAL ACOMPANHANTE --------->
    <div class="modal" id="acompanhanteModal" tabindex="-1" role="dialog" aria-labelledby="acompanhanteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- lucas 22092023 ID 358 Modificado titulo do modal-->
                    <h5 class="modal-title" id="exampleModalLabel">Chamado - <?php echo $demanda['tituloDemanda'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="container-fluid">
                            <div class="d-flex">
                                <span style="white-space: nowrap; margin-right: 5px;"><strong>Adicionar Acompanhante à Demanda: </strong></span>
                                <select class="form-select ts-input" name="idAcompanhante">
                                    <?php
                                   foreach ($acompanhantes as $acompanhante) {
                                       if (!in_array($acompanhante['idUsuario'], $acompanhantesIds)) {
                                   ?>
                                       <option value="<?php echo $acompanhante['idUsuario'] ?>"><?php echo $acompanhante['nomeUsuario'] ?></option>
                                   <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md">
                            <input type="hidden" class="form-control" name="idDemanda" value="<?php echo $demanda['idDemanda'] ?>" readonly>
                            <input type="hidden" class="form-control" name="origem" value="<?php echo $origem ?>" readonly>
                            <?php if ($acao == 'visaocli') { ?>
                                <input type="hidden" class="form-control ts-inputSemBorda" name="url" value="<?php echo $url_parametros ?>">
                            <?php } ?>
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- lucas 22092023 ID 358 Modificado nome do botao-->
                    <button type="submit" formaction="../database/demanda.php?operacao=acompanhantes&acao=<?php echo $acao?>" class="btn btn-success">Adicionar Acompanhante</button>
                </div>
                </form>
            </div>
        </div>
    </div>
