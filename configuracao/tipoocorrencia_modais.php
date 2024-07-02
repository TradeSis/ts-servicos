<div id="tipoocorrenciaModalAlterar<?php echo $ocorrencia['idTipoOcorrencia'] ?>" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    <h4 class="modal-title">Alterar ocorrencia</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../database/tipoocorrencia.php?operacao=alterar" method="post">
                        <div class="row">
                        <div class="col-md-2">
                                <label class="form-label ts-label">ID</label>
                                <input type="text" name="idTipoOcorrencia" value="<?php echo $ocorrencia['idTipoOcorrencia'] ?>" class="form-control ts-input" readonly/>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label ts-label">Ocorrência</label>
                                <input type="text" name="nomeTipoOcorrencia" value="<?php echo $ocorrencia['nomeTipoOcorrencia'] ?>" class="form-control ts-input" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label ts-label">Inicial</label>
                                <select class="form-control ts-input" name="ocorrenciaInicial">
                                    <option value="<?php echo $ocorrencia['ocorrenciaInicial'] ?>">
                                        <?php if($ocorrencia['ocorrenciaInicial'] == "1"){
                                            echo "Sim";
                                        } if($ocorrencia['ocorrenciaInicial'] == "0"){
                                            echo "Não";
                                        }?>
                                    </option>
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                       <button type="submit" class="btn btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div id="tipoocorrenciaModalExcluir<?php echo $ocorrencia['idTipoOcorrencia'] ?>" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    <h4 class="modal-title">Excluir ocorrencia</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../database/tipoocorrencia.php?operacao=excluir" method="post">
                        <div class="row">
                        <div class="col-md-2">
                                <label class="form-label ts-label">ID</label>
                                <input type="text" name="idTipoOcorrencia" value="<?php echo $ocorrencia['idTipoOcorrencia'] ?>" class="form-control ts-input" readonly/>
                            </div>
                            <div class="col-md-10">
                                <label class="form-label ts-label">Ocorrência</label>
                                <input type="text" name="nomeTipoOcorrencia" value="<?php echo $ocorrencia['nomeTipoOcorrencia'] ?>" class="form-control ts-input" />
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                       <button type="submit" class="btn btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>

