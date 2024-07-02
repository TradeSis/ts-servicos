<!--------- MODAL ORCAMENTO ITENS INSERIR --------->
<div class="modal" id="inserirChecklistModal" tabindex="-1" aria-labelledby="inserirChecklistModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Inserir Checklist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="modalChecklistInserir">
                    <div class="row mt-2">
                        <div class="col-md">
                            <label class='form-label ts-label'>Descrição</label>
                            <input type="text" class="form-control ts-input" name="descricao" autocomplete="off">
                            <input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $idContrato ?>">
                        </div>

                        <div class="col-md-3">
                            <label class='form-label ts-label'>Previsto</label>
                            <input type="date" class="form-control ts-input" name="dataPrevisto" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="text-end mt-4">
                            <button type="submit" class="btn  btn-success"><i
                                    class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<script>
    //Envio form modalOrcamentoInserir
    $("#modalChecklistInserir").submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "../database/contratochecklist.php?operacao=inserir",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: refreshPage,
        });
    });

    function refreshPage() {
        window.location.reload();
    }
</script>