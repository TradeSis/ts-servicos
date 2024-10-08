<?php
include_once '../header.php';
?>

<div class="input-group mt-2 ts-inputComentario">
    <span class="input-group-text" id="basic-addon1"><i class="bi bi-chat-dots"></i></span>
    <input type="button" class="form-control text-start btnAdicionarComentario" value="Adicionar comentário">
</div>

<div class="container-fluid mt-3 containerComentario ts-sumir">
    <form method="post" id="form-inserirComentario" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php
                    $nomeCliente = "Interno";
                    if ($usuario["idCliente"]) {
                        $clientes = buscaClientes($usuario["idCliente"]);
                        $nomeCliente = $clientes["nomeCliente"];
                    } ?>
                    <input type="hidden" class="form-control ts-label" name="idCliente" value="<?php echo $usuario['idCliente'] ?>" readonly>
                    <input type="hidden" class="form-control ts-label" name="idUsuario" value="<?php echo $usuario['idUsuario'] ?>" readonly>
                    <!-- <input type="text" class="form-control ts-input" value="<?php echo $_SESSION['usuario'] ?> - <?php echo $nomeCliente ?>" readonly> -->
                </div>
                <div class="form-group">

                    <div class="container-fluid p-0">
                        <div id="ql-toolbarComentario">
                            <?php include ROOT."/sistema/quilljs/ql-toolbar-min.php"  ?>
                            <input type="file" id="anexarComentario" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileComentario()" style=" display:none">
                            <label for="anexarComentario">
                                <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                            </label>
                        </div>
                        <div id="ql-editorComentario" style="height:30vh !important">
                        </div>
                        <textarea style="display: none" id="quill-comentario" name="comentario"></textarea>

                    </div>

                    <input type="hidden" name="idDemanda" value="<?php echo $idDemanda ?>" />
                    <input type="hidden" name="tipoStatusDemanda" value="<?php echo $idTipoStatus ?>" />

                    <div class="row mt-2">
                        <div class="col-md-9">
                            <input type="file" id="myFile" class="custom-file-upload" name="nomeAnexo" onchange="myFunction()" style="color:#567381; display:none">
                            <label for="myFile">
                                <a class="btn btn-primary"><i class="bi bi-file-earmark-arrow-down-fill" style="color:#fff"></i>&#32;<h7 style="color: #fff;">Anexos</h7></a>

                            </label>
                        </div>
                        <?php if ($_SESSION['administradora'] == 1) { ?>
                        <div class="col-1">
                            <div class="mt-2 form-check form-switch">
                                <input type="hidden" name="interno" value="0">
                                <input class="form-check-input" type="checkbox" name="interno" id="interno" value="1" checked>
                                <label class="form-check-label" for="interno">Interno</label>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md">
                            <!-- Lucas 22112023 id 688 - Removido visão do cliente -->
                            <button type="submit" formaction="../database/demanda.php?operacao=comentar" class="btn btn-success" style="float: right;">Salvar</button>
                        </div>
                    </div>

                    <p id="mostraNomeAnexo"></p>
                </div>

            </div>

        </div>
    </form>
</div>

<div class="container mt-3 col-md-12">
    <?php foreach ($comentarios as $comentario) {  
        if ($comentario['interno'] == 1 && $_SESSION['administradora'] != 1) {
            continue;
        }
    ?>

        <div class="row">
            <div class="col ms-2 pe-0" style="margin-bottom: -10px;">
                <i class="bi bi-person-circle" style="font-size: 30px;"></i>
            </div>
            <div class="col-md-11 ms-0 ps-0" style="margin-bottom: -10px;">
                <p class="mt-3 ms-0">
                    <span class="" style="font-style: italic;">
                        <strong><?php echo $comentario['nomeUsuario'] ?></strong> &#32;

                        &#32;<?php echo date('H:i d/m/Y', strtotime($comentario['dataComentario'])) ?>
                        <?php if ($comentario['interno'] == 1) { ?>
                            &#32; (Interno)
                        <?php } ?>
                </p>
            </div>
        </div>
        <div class="card mb-3" style="border-radius: 15px;box-shadow: 0px 10px 15px -3px rgba(0,0,0,0.1);">
            <div class="card-body p-1">
                <div class="row">

                    <div class="col-md-11">
                        <div class="ts-comentario">
                            <div class="clearfix"></div>
                            <p>
                                <?php echo $comentario['comentario'] ?>
                            </p>
                            <p>
                                <?php if ($comentario['pathAnexo'] != '') { ?>
                                    <span>anexo:</span>
                                    <a target="_blank" href="<?php echo $comentario['pathAnexo'] ?>"><?php echo $comentario['nomeAnexo'] ?></a>
                                    <span class="float-right">
                                        <a href="<?php echo $comentario['pathAnexo'] ?>">
                                            <i class="bi bi-file-earmark-arrow-down-fill" style="font-size: 2rem;"></i>
                                        </a>
                                    </span>
    
                                <?php } else {  ?>
                                    <a target="_blank" href="<?php echo $comentario['pathAnexo'] ?>"><?php echo $comentario['nomeAnexo'] ?></a>
                                <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<!-- LOCAL PARA COLOCAR OS JS -->

<script>
    /* $("#form-inserirComentario").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "../database/demanda.php?operacao=comentar",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            }); */

    function myFunction() {
        var x = document.getElementById("myFile");
        var txt = "";
        if ('files' in x) {
            if (x.files.length == 0) {
                txt = "";
            } else {
                for (var i = 0; i < x.files.length; i++) {
                    /* txt += "<br><strong>" + (i+1) + ". file</strong><br>"; */
                    var file = x.files[i];
                    if ('name' in file) {
                        txt += "Arquivo a ser anexado: " + "</br>" + "<i>" + file.name + "</i>" + "<br>";
                    }
                }
            }
        }
        document.getElementById("mostraNomeAnexo").innerHTML = txt;
    }
</script>




<!-- LOCAL PARA COLOCAR OS JS -FIM -->