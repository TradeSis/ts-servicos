<!--------- INSERIR --------->
<div class="modal fade bd-example-modal-lg" id="inserirModalNotas" tabindex="-1"
    aria-labelledby="inserirModalNotasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inserir Nota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="inserirFormNotaContrato">
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label class="form-label ts-label">Tomador/Cliente</label>
                            <select class="form-select ts-input" name="idPessoaTomador">
                                <?php
                                foreach ($pessoas as $pessoa) {
                                    ?>
                                <option value="<?php echo $pessoa['idPessoa'] ?>">
                                    <?php echo $pessoa['nomePessoa'] ?>
                                </option>
                                <?php } ?>
                            </select>
                            <input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $idContrato ?>">
                        </div>
                        <div class="col-md-3">
                            <label class='form-label ts-label'>Competência</label>
                            <input type="date" class="form-control ts-input" name="dataCompetencia" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ts-label">Município</label>
                            <select class="form-select ts-input" name="codMunicipio">
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
                            <input type="text" class="form-control ts-input" name="valorNota" autocomplete="off"
                                required>
                        </div>

                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <span class="tituloEditor">Descrição/Título Serviço</span>
                        </div>
                        <!-- lucas 27022024 - id853 nova chamada editor quill -->
                        <div id="ql-toolbarDescricaoServicoinserir">
                            <?php include ROOT . "/sistema/quilljs//ql-toolbar-min.php"  ?>
                            <input type="file" id="anexarDescricaoServicoinserir" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileDescricaoServicoinserir()" style=" display:none">
                            <label for="anexarDescricaoServicoinserir">
                                <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                            </label>
                        </div>
                        <div id="ql-editorDescricaoServicoinserir" style="height:30vh !important">
                        </div>
                        <textarea style="display: none" id="quill-descricaoServicoinserir" name="descricaoServico"></textarea>    
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <span class="tituloEditor">condicao</span>
                        </div>
                        <!-- lucas 27022024 - id853 nova chamada editor quill -->
                        <div id="ql-toolbarCondicaoInserir">
                            <?php include ROOT . "/sistema/quilljs//ql-toolbar-min.php"  ?>
                            <input type="file" id="anexarCondicaoInserir" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileCondicaoInserir()" style=" display:none">
                            <label for="anexarCondicaoInserir">
                                <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                            </label>
                        </div>
                        <div id="ql-editorCondicaoInserir" style="height:30vh !important">
                        </div>
                        <textarea style="display: none" id="quill-condicaoInserir" name="condicao"></textarea>    
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Cadastrar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script>
    //lucas 27022024 - id853 nova chamada editor quill
    var quillDescricaoServicoinserir = new Quill('#ql-editorDescricaoServicoinserir', {
        modules: {
            toolbar: '#ql-toolbarDescricaoServicoinserir'
        },
        placeholder: 'Digite o texto...',
        theme: 'snow'
    });

    quillDescricaoServicoinserir.on('text-change', function() {
        $('#quill-descricaoServicoinserir').val(quillDescricaoServicoinserir.container.firstChild.innerHTML);
    });

    async function uploadFileDescricaoServicoinserir() {

        let endereco = '/tmp/';
        let formData = new FormData();
        var custombutton = document.getElementById("anexarDescricaoServicoinserir");
        var arquivo = custombutton.files[0]["name"];

        formData.append("arquivo", custombutton.files[0]);
        formData.append("endereco", endereco);

        destino = endereco + arquivo;

        await fetch('/sistema/quilljs/quill-uploadFile.php', {
            method: "POST",
            body: formData
        });

        const range = this.quillDescricaoServicoinserir.getSelection(true)

        this.quillDescricaoServicoinserir.insertText(range.index, arquivo, 'user');
        this.quillDescricaoServicoinserir.setSelection(range.index, arquivo.length);
        this.quillDescricaoServicoinserir.theme.tooltip.edit('link', destino);
        this.quillDescricaoServicoinserir.theme.tooltip.save();

        this.quillDescricaoServicoinserir.setSelection(range.index + destino.length);

    }

    //lucas 27022024 - id853 nova chamada editor quill
    var quillCondicaoInserir = new Quill('#ql-editorCondicaoInserir', {
        modules: {
            toolbar: '#ql-toolbarCondicaoInserir'
        },
        placeholder: 'Digite o texto...',
        theme: 'snow'
    });

    quillCondicaoInserir.on('text-change', function() {
        $('#quill-condicaoInserir').val(quillCondicaoInserir.container.firstChild.innerHTML);
    });

    async function uploadFileCondicaoInserir() {

        let endereco = '/tmp/';
        let formData = new FormData();
        var custombutton = document.getElementById("anexarCondicaoInserir");
        var arquivo = custombutton.files[0]["name"];

        formData.append("arquivo", custombutton.files[0]);
        formData.append("endereco", endereco);

        destino = endereco + arquivo;

        await fetch('/sistema/quilljs/quill-uploadFile.php', {
            method: "POST",
            body: formData
        });

        const range = this.quillCondicaoInserir.getSelection(true)

        this.quillCondicaoInserir.insertText(range.index, arquivo, 'user');
        this.quillCondicaoInserir.setSelection(range.index, arquivo.length);
        this.quillCondicaoInserir.theme.tooltip.edit('link', destino);
        this.quillCondicaoInserir.theme.tooltip.save();

        this.quillCondicaoInserir.setSelection(range.index + destino.length);

    }
</script>