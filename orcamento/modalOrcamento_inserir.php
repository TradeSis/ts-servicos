 <!--------- MODAL ORCAMENTO INSERIR --------->
 <div class="modal" id="novoinserirOrcamentoModal" tabindex="-1" aria-labelledby="novoinserirOrcamentoModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Inserir Orçamento</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form method="post" id="modalOrcamentoInserir">
                    <div class="row">
                        <div class="col-sm-6 col-md">
                            <label class='form-label ts-label'>Titulo</label>
                            <input type="text" class="form-control ts-input" name="tituloOrcamento" required>
                        </div>
                        <div class="col-sm-3 col-md">
                            <label class="form-label ts-label">Cliente</label>
                            <input type="hidden" class="form-control ts-input" name="idSolicitante" value="<?php echo $usuario['idUsuario'] ?>" readonly>
                            <select class="form-select ts-input" name="idCliente">
                                <?php
                                foreach ($clientes as $cliente) { // ABRE o 
                                ?>
                                    <option value="<?php echo $cliente['idCliente'] ?>"><?php echo $cliente['nomeCliente'] ?></option>
                                <?php  } ?> <!--FECHA while-->
                            </select>
                        </div>
                        <div class="col-md-3 ">
                            <label class="form-label ts-label">Serviço</label>
                            <select class="form-select ts-input" name="idServico" autocomplete="off">
                                <option value="<?php echo null ?>">
                                    <?php echo "Selecione" ?>
                                </option>
                                <?php foreach ($servicos as $servico) { ?>
                                    <option value="<?php echo $servico['idServico'] ?>"> <?php echo $servico['nomeServico'] ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="container-fluid p-0">
                            <div class="col">
                                <span class="tituloEditor">Descrição</span>
                            </div>
                            <!--  gabriel 31052024 - id1000 nova chamada editor quilll -->
                            <div id="ql-toolbarOrcamentoInserir">
                                <?php include ROOT . "/sistema/quilljs//ql-toolbar-min.php"  ?>
                                <input type="file" id="anexarOrcamentoInserir" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileOrcamentoInserir()" style=" display:none">
                                <label for="anexarOrcamentoInserir">
                                    <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                                </label>
                            </div>
                            <div id="ql-editorOrcamentoInserir" style="height:30vh !important">
                            </div>
                            <textarea style="display: none" id="quill-orcamentoInserir" name="descricao"></textarea>
                        </div>
                    </div><!--col-md-6-->

                    <div class="col-md-12 mt-4">
                        <div class="text-end mt-4">
                            <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
                        </div>
                    </div>
             </form>
         </div>
     </div>
 </div>

 <!-- LOCAL PARA COLOCAR OS JS -->

 <?php include_once ROOT . "/vendor/footer_js.php"; ?>

<script>
    //gabriel 31052024 - id1000 nova chamada editor quill
    var quillOrcamentoInserir = new Quill('#ql-editorOrcamentoInserir', {
        modules: {
            toolbar: '#ql-toolbarOrcamentoInserir'
        },
        placeholder: 'Digite o texto...',
        theme: 'snow'
    });

    quillOrcamentoInserir.on('text-change', function() {
        $('#quill-orcamentoInserir').val(quillOrcamentoInserir.container.firstChild.innerHTML);
    });

    async function uploadFileOrcamentoInserir() {

        let endereco = '/tmp/';
        let formData = new FormData();
        var custombutton = document.getElementById("anexarOrcamentoInserir");
        var arquivo = custombutton.files[0]["name"];

        formData.append("arquivo", custombutton.files[0]);
        formData.append("endereco", endereco);

        destino = endereco + arquivo;

        await fetch('/sistema/quilljs/quill-uploadFile.php', {
            method: "POST",
            body: formData
        });

        const range = this.quillOrcamentoInserir.getSelection(true)

        this.quillOrcamentoInserir.insertText(range.index, arquivo, 'user');
        this.quillOrcamentoInserir.setSelection(range.index, arquivo.length);
        this.quillOrcamentoInserir.theme.tooltip.edit('link', destino);
        this.quillOrcamentoInserir.theme.tooltip.save();

        this.quillOrcamentoInserir.setSelection(range.index + destino.length);

    }
</script>

 <script>
     //Envio form modalOrcamentoInserir
     $("#modalOrcamentoInserir").submit(function(event) {
        event.preventDefault();
         var formData = new FormData(this);
         $.ajax({
             url: "../database/orcamento.php?operacao=inserir",
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