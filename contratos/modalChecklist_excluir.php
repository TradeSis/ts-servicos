<!--------- MODAL ORCAMENTO ITENS EXCLUIR --------->
<div class="modal" id="excluirChecklistModal" tabindex="-1" aria-labelledby="excluirChecklistModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Excluir Checklist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="modalChecklistExcluir">
                    <div class="row mt-2">
                        <div class="col-md">
                            <label class='form-label ts-label'>Descrição</label>
                            <input type="text" class="form-control ts-input" name="descricao" id="EXCdescricao" autocomplete="off" readonly>
                            <input type="hidden" class="form-control ts-input" name="idChecklist" id="EXCidChecklist">
                            <input type="hidden" class="form-control ts-input" name="idContrato" id="EXCidContrato">
                        </div>

                        <div class="col-md-3">
                            <label class='form-label ts-label'>Previsto</label>
                            <input type="date" class="form-control ts-input" name="dataPrevisto" id="EXCdataPrevisto"
                                autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="text-end mt-4">
                            <button type="submit" class="btn  btn-danger"><i
                                    class="bi bi-sd-card-fill"></i>&#32;Excluir</button>
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
    //Envio form modalOrcamentoExcluir
    $("#modalChecklistExcluir").submit(function (event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "../database/contratochecklist.php?operacao=excluir",
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