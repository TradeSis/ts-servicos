<!--------- ALTERAR --------->
<div class="modal fade bd-example-modal-lg" id="alterarModalNotas" tabindex="-1"
    aria-labelledby="alterarModalNotasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="alterarFormNotaContrato">
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="form-label ts-label">Tomador/Cliente</label>
                            <select class="form-select ts-input" name="idPessoaTomador" id="idPessoaTomador">
                                <?php
                                foreach ($pessoas as $pessoa) {
                                    ?>
                                <option value="<?php echo $pessoa['idPessoa'] ?>">
                                    <?php echo $pessoa['nomePessoa'] ?>
                                </option>
                                <?php } ?>
                            </select>
                            <input type="hidden" class="form-control ts-input" name="idNotaServico" id="idNotaServico">
                        </div>
                        <div class="col-md-3">
                            <label class='form-label ts-label'>Competência</label>
                            <input type="date" class="form-control ts-input" name="dataCompetencia"
                                id="dataCompetencia">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Município</label>
                            <select class="form-select ts-input" name="codMunicipio" id="codMunicipio">
                                <?php
                                foreach ($cidades as $cidade) {
                                    ?>
                                <option value="<?php echo $cidade['codigoCidade'] ?>">
                                    <?php echo $cidade['nomeCidade'] ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class='form-label ts-label'>valorNota</label>
                            <input type="text" class="form-control ts-input" name="valorNota" id="valorNota" required>
                        </div>

                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <span class="tituloEditor">Descrição/Título Serviço</span>
                        </div>
                        <div class="quill-descricaoServicoalterar" style="height:20vh !important"></div>
                        <textarea style="display: none" id="quill-descricaoServicoalterar"
                            name="descricaoServico"></textarea>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <span class="tituloEditor">condicao</span>
                        </div>
                        <div class="quill-condicaoalterar" style="height:20vh !important"></div>
                        <textarea style="display: none" id="quill-condicaoalterar" name="condicao"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="salvarBtn">Salvar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    var condicaoalterar = new Quill('.quill-condicaoalterar', {
        theme: 'snow'
    });
    condicaoalterar.on('text-change', function (delta, oldDelta, source) {
        $('#quill-condicaoalterar').val(condicaoalterar.container.firstChild.innerHTML);
    });
    var descricaoServicoalterar = new Quill('.quill-descricaoServicoalterar', {
        theme: 'snow'
    });
    descricaoServicoalterar.on('text-change', function (delta, oldDelta, source) {
        $('#quill-descricaoServicoalterar').val(descricaoServicoalterar.container.firstChild.innerHTML);
    });
</script>