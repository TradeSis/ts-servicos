<!--------- MODAL GERAR CONTRATO --------->
<div class="modal" id="gerarContratoModal" tabindex="-1" role="dialog" aria-labelledby="gerarContratoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <!-- lucas 22092023 ID 358 Modificado titulo do modal-->
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $orcamento['tituloOrcamento'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" onsubmit="return validaForm()">
                    <div class="container-fluid p-0">
                        <div class="col-md">
                            <label class="form-label ts-label">Selecione o tipo de contrato</label>
                            <select class="form-select ts-input" name="idContratoTipo">
                                <?php
                                foreach ($contratoTipos as $contratoTipo) { // ABRE o 
                                ?>
                                    <option value="<?php echo $contratoTipo['idContratoTipo'] ?>"><?php echo $contratoTipo['nomeContrato'] ?></option>
                                <?php  } ?> <!--FECHA while-->
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <input type="hidden" class="form-control" name="idOrcamento" value="<?php echo $orcamento['idOrcamento'] ?>">
                        <input type="hidden" class="form-control" name="idSolicitante" value="<?php echo $usuario['idUsuario'] ?>">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" formaction="../database/orcamento.php?operacao=gerarcontrato" class="btn btn-warning">Gerar Contrato</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
function validaForm() {
    var valorOrcamento = "<?php echo $orcamento['valorOrcamento'] ?>";
    var valorHora = "<?php echo $orcamento['valorHora'] ?>";
    var horas = "<?php echo $orcamento['horas'] ?>";
    if (valorOrcamento === null || valorOrcamento === "" || valorHora === null || valorHora === "" || horas === null || horas === "") {
        alert("Favor definir valor do orçamento");
        return false;
    }
    return true;
}
</script>