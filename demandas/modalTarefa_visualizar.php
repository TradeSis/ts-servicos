
<!-- Lucas 26062024 id1092 adicionado pagina para visualiza��o do cliente -->


<!--------- MODAL VISUALIZAR --------->
<div class="modal" id="alterarmodal" tabindex="-1" aria-labelledby="alterarmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Visualizar Tarefa   </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span class="titulo" id="tituloContratodeTarefas"></span>
                <span class="titulo" id="tituloDemandadeTarefas"></span>

                <ul class="nav nav-tabs mt-2" id="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link ts-tabModal active" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">Dados Tarefa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ts-tabModal" id="advanced-tab" data-bs-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-selected="false">Mais Opções</a>
                    </li>
                </ul>
                <form method="post" id="alterarForm">
                    <div class="tab-content" id="myTabsContent">
                        <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                            <div class="container">

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class='form-label ts-label'>Tarefa</label>
                                        <input type="text" class="form-control ts-input" id="titulo" name="tituloTarefa" disabled>
                                    </div>

                                    <!-- Lucas 09112023 ID 965 Removido Select de demandaRelacionada -->

                                    <input type="hidden" class="form-control ts-input" name="idTarefa" id="idTarefa" />

                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label class="form-label ts-label">Cliente</label>
                                        <select class="form-select ts-input ts-displayDisable" name="idCliente" id="idCliente">
                                            <?php
                                            foreach ($clientes as $cliente) {
                                            ?>
                                                <option value="<?php echo $cliente['idCliente'] ?>">
                                                    <?php echo $cliente['nomeCliente'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label ts-label">Responsável</label>
                                        <select class="form-select ts-input ts-displayDisable" name="idAtendente" id="idAtendente">
                                            <?php
                                            foreach ($atendentes as $atendente) {
                                            ?>
                                                <option value="<?php echo $atendente['idUsuario'] ?>">
                                                    <?php echo $atendente['nomeUsuario'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class='form-label ts-label'>Ocorrência</label>
                                        <select class="form-select ts-input ts-displayDisable" name="idTipoOcorrencia" id="idTipoOcorrencia">
                                            <option value="null"></option>
                                            <?php
                                            foreach ($ocorrencias as $ocorrencia) {
                                            ?>
                                                <option value="<?php echo $ocorrencia['idTipoOcorrencia'] ?>">
                                                    <?php echo $ocorrencia['nomeTipoOcorrencia'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <label class="form-label ts-label">Data Prevista</label>
                                        <input type="date" class="form-control ts-input" id="Previsto" name="Previsto" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label ts-label">Inicio</label>
                                        <input type="time" class="form-control ts-input" id="horaInicioPrevisto" name="horaInicioPrevisto" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label ts-label">Fim</label>
                                        <input type="time" class="form-control ts-input" id="horaFinalPrevisto" name="horaFinalPrevisto" disabled>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <?php if ($_SESSION['administradora'] == 1) { ?>
                                        <div class="col-md-4">
                                            <label class="form-label ts-label">Data Realizado</label>
                                            <input type="date" class="form-control ts-input" id="dataReal" name="dataReal" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label ts-label">Inicio</label>
                                            <input type="time" class="form-control ts-input" id="horaInicioReal" name="horaInicioReal" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label ts-label">Fim</label>
                                            <input type="time" class="form-control ts-input" id="horaFinalReal" name="horaFinalReal" readonly>
                                        </div>
                                    <?php } ?>
                                </div><!--row-->

                            </div>
                        </div>
                        <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                            <div class="container">
                                <!-- lucas 27022024 - id853 nova chamada editor quill -->
                                <div id="ql-toolbarTarefaAlterar">
                                    <?php include ROOT."/sistema/quilljs/ql-toolbar-min.php"  ?>
                                    <input type="file" id="anexarTarefaAlterar" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileTarefaAlterar()" style=" display:none">
                                    <label for="anexarTarefaAlterar">
                                        <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                                    </label>
                                </div>
                                <div id="ql-editorTarefaAlterar" style="height:30vh !important">
                                </div>
                                <textarea style="display: none" id="quill-tarefaAlterar" name="descricao"></textarea>
                            </div>
                        </div>
                    </div>

            </div><!--modal body-->

            </form>
        </div>
    </div>
</div>
<!-- LOCAL PARA COLOCAR OS JS -->

<?php include_once ROOT . "/vendor/footer_js.php"; ?>

<!-- lucas 27022024 - id853 nova chamada editor quill -->
<script>
    var quillTarefaAlterar = new Quill('#ql-editorTarefaAlterar', {
        modules: {
            toolbar: '#ql-toolbarTarefaAlterar'
        },
        placeholder: 'Digite o texto...',
        theme: 'snow'
    });

    quillTarefaAlterar.on('text-change', function() {
        $('#quill-tarefaAlterar').val(quillTarefaAlterar.container.firstChild.innerHTML);
    });

    async function uploadFileTarefaAlterar() {

        let endereco = '/tmp/';
        let formData = new FormData();
        var custombutton = document.getElementById("anexarTarefaAlterar");
        var arquivo = custombutton.files[0]["name"];

        formData.append("arquivo", custombutton.files[0]);
        formData.append("endereco", endereco);

        destino = endereco + arquivo;

        await fetch('/sistema/quilljs/quill-uploadFile.php', {
            method: "POST",
            body: formData
        });

        const range = this.quillTarefaAlterar.getSelection(true)

        this.quillTarefaAlterar.insertText(range.index, arquivo, 'user');
        this.quillTarefaAlterar.setSelection(range.index, arquivo.length);
        this.quillTarefaAlterar.theme.tooltip.edit('link', destino);
        this.quillTarefaAlterar.theme.tooltip.save();

        this.quillTarefaAlterar.setSelection(range.index + destino.length);

    }

    function BuscarAlterar(idTarefa) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo URLROOT ?>/servicos/database/tarefas.php?operacao=buscar',
            data: {
                idTarefa: idTarefa
            },
            success: function(data) {

                $('#idTarefa').val(data.idTarefa);
                $('#titulo').val(data.tituloTarefa);
                $('#idCliente').val(data.idCliente);
                $('#nomeCliente').val(data.nomeCliente);
                $('#idDemanda').val(data.idDemanda);
                $('#tituloDemanda').val(data.tituloDemanda);
                $('#idAtendente').val(data.idAtendente);
                $('#nomeUsuario').val(data.nomeUsuario);
                $('#idTipoOcorrencia').val(data.idTipoOcorrencia);
                $('#nomeTipoOcorrencia').val(data.nomeTipoOcorrencia);
                $('#Previsto').val(data.Previsto);
                $('#horaInicioPrevisto').val(data.horaInicioPrevisto);
                $('#horaFinalPrevisto').val(data.horaFinalPrevisto);
                $('#dataReal').val(data.dataReal);
                $('#horaInicioReal').val(data.horaInicioReal);
                $('#horaFinalReal').val(data.horaFinalReal);

                vidDemanda = data.idDemanda;
                vtituloDemanda = data.tituloDemanda;
                vnomeDemanda = data.nomeDemanda;
                vnomeContrato = data.nomeContrato;
                vtituloContrato = data.tituloContrato;
                vidContrato = data.idContrato;
                quillTarefaAlterar.root.innerHTML = data.descricao;



                $('#alterarmodal').modal('show');

                // Lucas 09112023 ID 965 informações da tarefa dinamica
                if ((vidContrato == null) && (vidDemanda == null)) {
                    var tituloModal = $("#tituloContratodeTarefas");
                    var text = ' ';
                    tituloModal.html(text);
                    var tituloModal = $("#tituloDemandadeTarefas");
                    var text = ' ';
                    tituloModal.html(text);
                }
                if ((vidDemanda !== null) && (vidContrato == null)) {
                    var tituloModal = $("#tituloContratodeTarefas");
                    var text = ' ';
                    tituloModal.html(text);
                    var tituloModal = $("#tituloDemandadeTarefas");
                    var text = vnomeDemanda + " :" + " " + vidDemanda + " - " + vtituloDemanda;
                    tituloModal.html(text);
                }
                if ((vidDemanda !== null) && (vidContrato !== null)) {
                    var tituloModal = $("#tituloContratodeTarefas");
                    var text = vnomeContrato + " :" + " " + vidContrato + " - " + vtituloContrato + "<br>";
                    tituloModal.html(text);
                    var tituloModal = $("#tituloDemandadeTarefas");
                    var text = vnomeDemanda + " :" + " " + vidDemanda + " - " + vtituloDemanda;
                    tituloModal.html(text);
                }

                function dataAtual() {
                    var data = new Date(),
                        dia = data.getDate().toString(),
                        diaF = (dia.length == 1) ? '0' + dia : dia,
                        mes = (data.getMonth() + 1).toString(),
                        mesF = (mes.length == 1) ? '0' + mes : mes,
                        anoF = data.getFullYear();
                    return anoF + "-" + mesF + "-" + diaF;
                }
                dataAtual_alterar = dataAtual();

                if (data.dataReal == dataAtual_alterar) {
                    $('#stopButtonModal').show();
                } else {
                    $('#stopButtonModal').hide();
                }

            }

        });
    }


    $(document).on('click', 'button[data-bs-target="#alterarmodal"]', function() {
        var idTarefa = $(this).attr("data-idtarefa");
        BuscarAlterar(idTarefa);
    });

    // Click event for tr[data-target="#alterarmodal"]
    $(document).on('click', 'td[data-bs-target="#alterarmodal"]', function() {
        var idTarefa = $(this).attr("data-idtarefa");
        BuscarAlterar(idTarefa);
    });
    //gabriel 13102023 id 596 sempre abrir pagina 1 do modal 
    $(document).ready(function() {
        $('#alterarmodal').on('shown.bs.modal', function() {
            $('#basic-tab').tab('show');
        });
    });
</script>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->