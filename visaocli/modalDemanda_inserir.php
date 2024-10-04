<!-- lucas 28112023 id706 - Melhorias Demandas 2 -->
<!--------- MODAL DEMANDA INSERIR --------->
<div class="modal" id="inserirDemandaCliente" tabindex="-1" aria-labelledby="inserirDemandaClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Inserir
                    <?php echo $contratoDemanda['nomeDemanda'] ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="modalDemandaInserir" enctype="multipart/form-data">
                    <div class="row mt-1">

                        <div class="col-sm-8 col-md">
                            <label class='form-label ts-label'><?php echo $contratoDemanda['nomeDemanda'] ?></label>
                            <input type="text" class="form-control ts-input" name="tituloDemanda" autocomplete="off" required>
                            <input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $contrato['idContrato'] ?>" readonly>
                            <input type="hidden" class="form-control ts-input" name="idContratoTipo" value="<?php echo $contratoDemanda['idContratoTipo'] ?>" readonly>
                            <input type="hidden" class="form-control ts-input" name="idUsuario" value="<?php echo $usuario['idUsuario'] ?>">
                        </div>

                        <div class="col-sm-4 col-md">
                            <label class="form-label ts-label">Cliente</label>
                            <input type="hidden" class="form-control ts-input" name="idSolicitante" value="<?php echo $usuario['idUsuario'] ?>" readonly>

                            <?php if (isset($contrato)) { ?>
                                <input type="text" class="form-control ts-input" value="<?php echo $contrato['nomeCliente'] ?>" readonly>
                                <input type="hidden" class="form-control ts-input" name="idCliente" value="<?php echo $contrato['idCliente'] ?>" readonly>
                            <?php } else { ?>

                                <select class="form-select ts-input" name="idCliente" autocomplete="off" <?php if (isset($contrato)) {
                                                                                                                echo " disabled ";
                                                                                                            } ?>required>
                                    <option value="">
                                        <?php if (isset($contrato)) {
                                            echo $contrato['nomeCliente'];
                                        } else {
                                            echo "Selecione";
                                        } ?>
                                    </option>
                                    <?php
                                    foreach ($clientes as $cliente) {
                                    ?>
                                        <!-- gabriel 05022024 id738 - seleciona cliente automatico -->
                                        <option <?php if ($usuario["idCliente"] == $cliente['idCliente']) {
                                                    echo "selected";
                                                } ?> value="<?php echo $cliente['idCliente'] ?>">
                                            <?php echo $cliente['nomeCliente'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="container-fluid p-0">
                                <div class="col">
                                    <span class="tituloEditor">Descrição</span>
                                </div>
                                <!-- lucas 27022024 - id853 nova chamada editor quill -->
                                <div id="ql-toolbarClienteDemandaInserir">
                                    <?php include ROOT . "/sistema/quilljs//ql-toolbar-min.php"  ?>
                                    <input type="file" id="anexarClienteDemandaInserir" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileClienteDemandaInserir()" style=" display:none">
                                    <label for="anexarClienteDemandaInserir">
                                        <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                                    </label>
                                </div>
                                <div id="ql-editorClienteDemandaInserir" style="height:30vh !important">
                                </div>
                                <textarea style="display: none" id="quill-clienteDemandaInserir" name="descricao"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 mt-3">
                                    <label class="form-label ts-label"><?php echo $contratoDemanda['nomeContrato'] ?> Vinculado</label>
                                    <?php
                                    if (isset($contrato)) { ?>
                                        <input type="text" class="form-control ts-input" value="<?php echo $contrato['tituloContrato'] ?>" readonly>
                                        <input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $contrato['idContrato'] ?>" readonly disabled>
                                    <?php } else { ?>
                                        <select class="form-select ts-input" name="idContrato" id='selectContratos' required>
                                            <!-- gabriel 05022024 id738 - removido disabled seleciona contrato -->
                                            <!-- options montados via ajax -->
                                        </select>

                                    <?php  } ?>
                                </div>

                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label ts-label">Previsão</label>
                                    <input type="time" class="form-control ts-input" name="horasPrevisao" value="<?php echo $demanda['horasPrevisao'] ?>" disabled>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label ts-label">tempo Cobrado</label>
                                    <input type="time" class="form-control ts-input" name="tempoCobrado" disabled>
                                </div>
                                <!-- lucas 28112023 id706 - removido tipoOcorrencia -->
                            </div><!--fim row 1-->

                            <div class="row mt-3">
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label ts-label">Previsão Inicio</label>
                                    <input type="date" class="form-control ts-input" name="dataPrevisaoInicio" value="<?php echo $demanda['dataPrevisaoInicio'] ?>" disabled>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label ts-label">Previsão Entrega</label>
                                    <input type="date" class="form-control ts-input" name="dataPrevisaoEntrega" value="<?php echo $demanda['dataPrevisaoEntrega'] ?>" disabled>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- lucas 21112023 ID 688 - removido campo tamanho -->
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label ts-label">Serviço</label>
                                    <select class="form-select ts-input" name="idServico" disabled>
                                        <option value="<?php echo null ?>">
                                            <?php echo "Selecione" ?>
                                        </option>
                                        <?php foreach ($servicos as $servico) { ?>
                                            <option value="<?php echo $servico['idServico'] ?>">
                                                <?php echo $servico['nomeServico'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label ts-label">Responsável</label>
                                    <select class="form-select ts-input" name="idAtendente" disabled>
                                        <option value="<?php echo null ?>">
                                            <?php echo "Selecione" ?>
                                        </option>
                                        <?php foreach ($atendentes as $atendente) { ?>
                                            <option <?php
                                                    if ($atendente['idUsuario'] == $usuario['idUsuario']) {
                                                        echo "selected";
                                                    }
                                                    ?> value="<?php echo $atendente['idUsuario'] ?>">
                                                <?php echo $atendente['nomeUsuario'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div><!--fim row 2-->

                        </div><!--col-md-6-->


                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<script>
    //gabriel 05022024 id738 - trigger cliente automatico 
    $(document).ready(function() {
        var selectedPadrao = "<?php echo $usuario["idCliente"] ?>";
        if (selectedPadrao !== null && selectedPadrao !== "") {
            $('select[name="idCliente"]').val(selectedPadrao).trigger('change');
        }
    });
    //Select de Contrato Vinculado troca de acordo com o Select de Cliente
    $('select[name="idCliente"]').on('change', function() {
        var idCliente = this.value;
        //lucas 28032024 - adicionado idContratoTipo
        var idContratoTipo = "<?php echo $_GET["idContratoTipo"] ?>";

        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '<?php echo URLROOT ?>/servicos/database/contratos.php?operacao=buscar',
            beforeSend: function() {
                $("#selectContratos").html("Carregando...");
            },
            data: {
                idCliente: idCliente,
                idContratoTipo: idContratoTipo
            },
            success: function(msg) {
                var json = JSON.parse(msg);
                var linha = "";
                linha = linha + "<option value=''>Selecione</option>";
                if (json.length === 1) {
                    var object = json[0];
                    linha = linha + "<option selected value='" + object.idContrato + "'>" + object.tituloContrato + "</option>";
                } else {
                    for (var i = 0; i < json.length; i++) {
                        var object = json[i];
                        linha = linha + "<option value='" + object.idContrato + "'>" + object.tituloContrato + "</option>";
                    }
                }
                $("#selectContratos").html(linha);

            }
        });

    });

    //lucas 27022024 - id853 nova chamada editor quill
    var quillClienteDemandaInserir = new Quill('#ql-editorClienteDemandaInserir', {
        modules: {
            toolbar: '#ql-toolbarClienteDemandaInserir'
        },
        placeholder: 'Digite o texto...',
        theme: 'snow'
    });

    quillClienteDemandaInserir.on('text-change', function() {
        $('#quill-clienteDemandaInserir').val(quillClienteDemandaInserir.container.firstChild.innerHTML);
    });

    async function uploadFileClienteDemandaInserir() {

        let endereco = '/tmp/';
        let formData = new FormData();
        var custombutton = document.getElementById("anexarClienteDemandaInserir");
        var arquivo = custombutton.files[0]["name"];

        formData.append("arquivo", custombutton.files[0]);
        formData.append("endereco", endereco);

        destino = endereco + arquivo;

        await fetch('/sistema/quilljs/quill-uploadFile.php', {
            method: "POST",
            body: formData
        });

        const range = this.quillClienteDemandaInserir.getSelection(true)

        this.quillClienteDemandaInserir.insertText(range.index, arquivo, 'user');
        this.quillClienteDemandaInserir.setSelection(range.index, arquivo.length);
        this.quillClienteDemandaInserir.theme.tooltip.edit('link', destino);
        this.quillClienteDemandaInserir.theme.tooltip.save();

        this.quillClienteDemandaInserir.setSelection(range.index + destino.length);

    }

    //Envio form modalDemandaInserir
    $("#modalDemandaInserir").submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        var idContratoTipo = formData.get('idContratoTipo');
        $.ajax({
            url: "../database/demanda.php?operacao=inserir&acao=visaocli",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                var msg = JSON.parse(response);
                console.log(msg);
                if (msg.status == 200) {
                    window.location.href = '../visaocli/index.php?idContratoTipo=' + idContratoTipo;
                } else {
                    alert(msg.retorno); 
                }
            }
        });
    });
</script>
