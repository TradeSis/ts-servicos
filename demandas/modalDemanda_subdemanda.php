<!--------- MODAL SUBDEMANDA --------->
<div class="modal" id="subdemandaModal" tabindex="-1" role="dialog" aria-labelledby="subdemandaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <!-- lucas 22092023 ID 358 Modificado titulo do modal-->
                <h5 class="modal-title" id="exampleModalLabel">ID <?php echo $demanda['idDemanda'] ?> - <?php echo $demanda['tituloDemanda'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="container-fluid p-0 text-center">
                        <h4>CRIAR SUBDEMANDA?</h4>
                    </div>
                    <input type="hidden" class="form-control" name="idDemanda" value="<?php echo $demanda['idDemanda'] ?>">
                    <input type="hidden" class="form-control" name="idLogin" value="<?php echo $_SESSION['idLogin'] ?>">
            </div>
            <div class="modal-footer">
                <button type="submit" formaction="../database/demanda.php?operacao=atualizar&acao=subdemanda" class="btn btn-success">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>
