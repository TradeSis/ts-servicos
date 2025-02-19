    <!--------- MODAL DESASSOCIAR --------->
    <div class="modal" id="desassociarModal" tabindex="-1" role="dialog" aria-labelledby="desassociarModalLabel" aria-hidden="true">
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
                                <span style="white-space: nowrap; margin-right: 5px;"><strong>Usuários Associados: </strong></span>
                                <select class="form-select ts-input" name="idAssociado">
                                <?php foreach ($associados as $associado) {
                                if (in_array($associado['idUsuario'], $associadosIds)) {
                                    if ($_SESSION['administradora'] != 1) {
                                        if ($associado["idCliente"] == $demanda["idCliente"]) { ?>
                                    <option value="<?php echo $associado['idUsuario'] ?>"><?php echo $associado['nomeUsuario'] ?></option>
                                    <?php }
                                    } else { ?>
                                    <option value="<?php echo $associado['idUsuario'] ?>"><?php echo $associado['nomeUsuario'] ?></option>
                                <?php } } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md">
                            <input type="hidden" class="form-control" name="idDemanda" value="<?php echo $demanda['idDemanda'] ?>" readonly>
                            <input type="hidden" class="form-control" name="origem" value="<?php echo $origem ?>" readonly>
                            <input type="hidden" class="form-control" name="acao" value="desassociar" readonly>
                            <?php if ($acao == 'visaocli') { ?>
                                <input type="hidden" class="form-control ts-inputSemBorda" name="url" value="<?php echo $url_parametros ?>">
                            <?php } ?>
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- lucas 22092023 ID 358 Modificado nome do botao-->
                    <button type="submit" formaction="../database/demanda.php?operacao=associados&acao=<?php echo $acao?>" class="btn btn-warning">Excluir Associado</button>
                </div>
                </form>
            </div>
        </div>
    </div>
