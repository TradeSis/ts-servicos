<!--------- MODAL ENTREGAR --------->
<div class="modal" id="entregarModal" tabindex="-1" role="dialog" aria-labelledby="entregarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <!-- lucas 22092023 ID 358 Modificado titulo do modal-->
                <h5 class="modal-title" id="exampleModalLabel">Chamado - <?php echo $demanda['tituloDemanda'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="container-fluid p-0">
                        <!-- lucas 27022024 - id853 nova chamada editor quill -->
                        <div id="ql-toolbarEntregar">
                            <?php include ROOT."/sistema/quilljs/ql-toolbar-min.php"  ?>
                            <input type="file" id="anexarEntregar" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileEntregar()" style=" display:none">
                            <label for="anexarEntregar">
                                <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                            </label>
                        </div>
                        <div id="ql-editorEntregar" style="height:30vh !important">
                        </div>
                        <textarea style="display: none" id="quill-entregar" name="comentario"></textarea>
                    </div>
                    <div class="col-md">
                        <input type="hidden" class="form-control" name="idDemanda" value="<?php echo $demanda['idDemanda'] ?>" readonly>
                        <input type="hidden" class="form-control" name="idCliente" value="<?php echo $demanda['idCliente'] ?>" readonly>
                        <input type="hidden" class="form-control" name="idUsuario" value="<?php echo $usuario['idUsuario'] ?>" readonly>
                        <input type="hidden" class="form-control" name="tipoStatusDemanda" value="<?php echo $demanda['idTipoStatus'] ?>" readonly>
                        <input type="hidden" class="form-control" name="origem" value="<?php echo $origem ?>" readonly>
                        <?php if ($acao == 'visaocli') { ?>
                            <input type="hidden" class="form-control ts-inputSemBorda" name="url" value="<?php echo $url_parametros ?>">
                            <input type="hidden" class="form-control ts-inputSemBorda" name="origem" value="<?php echo $origem ?>">
                        <?php } ?>
                    </div>

            </div>
            <div class="modal-footer">
                <?php if ($_SESSION['administradora'] == 1) { ?>
                    <div class="mt-2 form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="interno" id="interno" value="1">
                        <label class="form-check-label" for="interno">Interno</label>
                    </div>
                <?php } ?>
                <!-- lucas 22092023 ID 358 Modificado nome do botao-->
                <button type="submit" formaction="../database/demanda.php?operacao=atualizar&acao=entregar" class="btn btn-warning">Entregar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- lucas 27022024 - id853 nova chamada editor quill -->
<!-- gabriel 27052024 - id981 removido modalstatus.js para evitar redundancia do script -->
<script>
var quillEntregar = new Quill('#ql-editorEntregar', {
    modules: {
        toolbar: '#ql-toolbarEntregar'
    },
    placeholder: 'Digite o texto...',
    theme: 'snow'
});

quillEntregar.on('text-change', function(delta, oldDelta, source) {
    $('#quill-entregar').val(quillEntregar.container.firstChild.innerHTML);
});

async function uploadFileEntregar() {

    let endereco = '/tmp/';
    let formData = new FormData();
    var custombutton = document.getElementById("anexarEntregar");
    var arquivo = custombutton.files[0]["name"];

    formData.append("arquivo", custombutton.files[0]);
    formData.append("endereco", endereco);

    destino = endereco + arquivo;

    await fetch('/sistema/quilljs/quill-uploadFile.php', {
        method: "POST",
        body: formData
    });

    const range = this.quillEntregar.getSelection(true)

    this.quillEntregar.insertText(range.index, arquivo, 'user');
    this.quillEntregar.setSelection(range.index, arquivo.length);
    this.quillEntregar.theme.tooltip.edit('link', destino);
    this.quillEntregar.theme.tooltip.save();

    this.quillEntregar.setSelection(range.index + destino.length);

}
</script>