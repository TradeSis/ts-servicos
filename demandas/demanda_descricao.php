<form action="../database/demanda.php?operacao=descricao&acao=<?php echo $acao?>" method="post">
    <div class="col-md-12">
        <div class="container-fluid p-0 ts-containerDescricaoDemanda">
            <div class="row">
                <div class="col">
                    <span class="tituloEditor">Descrição</span>
                </div>
                <div class="col text-end">
                    <a class="ts-btnDescricaoEditar"><i class="bi bi-pen"></i>&#32;Editar</a>
                </div>
            </div>
            <div id="ql-toolbarDescricao">
                <?php include ROOT."/sistema/quilljs/ql-toolbar-min.php"  ?>
                <input type="file" id="anexarDescricao" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileDescricao()" style=" display:none">
                <label for="anexarDescricao">
                    <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                </label>
            </div>
            <div id="ql-editorDescricao" class="ts-displayDisable" style="height: auto!important;">
                <?php echo $demanda['descricao'] ?>
            </div>
            <textarea style="display: none" id="quill-demandadescricao" name="descricao"><?php echo $demanda['descricao'] ?></textarea>
            <input type="hidden" class="form-control ts-input" name="idDemanda" value="<?php echo $demanda['idDemanda'] ?>">
            <?php if ($acao == 'visaocli') { ?>
                <input type="hidden" class="form-control ts-inputSemBorda" name="url" value="<?php echo $url_parametros ?>">
            <?php } ?>
        </div>
    </div>

    <div class="col text-end">
        <button type="submit" class="btn btn-success mt-1 btnSalvarComentario ts-sumir">Salvar</button>
    </div>
</form>

<?php include_once 'comentarios.php'; ?>

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

