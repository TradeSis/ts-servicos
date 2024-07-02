<!--------- MODAL GERAR CONTRATO --------->
<div class="modal" id="aprovarModal" tabindex="-1" role="dialog" aria-labelledby="aprovarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <!-- lucas 22092023 ID 358 Modificado titulo do modal-->
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $orcamento['tituloOrcamento'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="container-fluid p-0 text-center">
                        <h4>CONFIRMA APROVAÇÃO DO ORÇAMENTO?</h4>
                    </div>
                    <input type="hidden" class="form-control" name="idOrcamento" value="<?php echo $orcamento['idOrcamento'] ?>">
            </div>
            <div class="modal-footer">
                <button type="submit" formaction="../database/orcamento.php?operacao=atualizar&acao=aprovar" class="btn btn-success">Aprovar</button>
            </div>
            </form>
        </div>
    </div>
</div>
