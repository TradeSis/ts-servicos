 <!--------- MODAL ORCAMENTO ITENS ALTERAR --------->
 <div class="modal" id="alterarOrcamentoItensModal" tabindex="-1" aria-labelledby="alterarOrcamentoItensModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Alterar Item Orçamento</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form method="post" id="modalOrcamentoItensAlterar">
                    <div class="row mt-2">
                        <div class="col-md">
                            <label class='form-label ts-label'>Titulo Item</label>
                            <input type="text" class="form-control ts-input" name="tituloItemOrcamento" id="tituloItemOrcamento" autocomplete="off">
                            <input type="hidden" class="form-control ts-input" name="idItemOrcamento" id="idItemOrcamento">
                            <input type="hidden" class="form-control ts-input" name="idOrcamento" id="idOrcamento">
                        </div>
    
                        <div class="col-md">
                            <label class='form-label ts-label'>Horas</label>
                            <input type="number" class="form-control ts-input" name="horas" id="horas" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="text-end mt-4">
                            <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
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
     //Envio form modalOrcamentoAlterar
     $("#modalOrcamentoItensAlterar").submit(function(event) {
        event.preventDefault();
         var formData = new FormData(this);
         $.ajax({
             url: "../database/orcamento.php?operacao=itensalterar",
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