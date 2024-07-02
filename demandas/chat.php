<?php
//Gabriel 05102023 ID 575 Demandas/Comentarios - Layout de chat

include_once(ROOT . '/cadastros/database/clientes.php');
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(__DIR__ . '/../database/tipoocorrencia.php');
include_once(__DIR__ . '/../database/contratos.php');

$usuarios = buscaUsuarios();
$clientes = buscaClientes();
$tipoocorrencias = buscaTipoOcorrencia();
$contratos = buscaContratosAbertos();
?>

<!-- Gabriel 26092023 ID 575 novo CHAT e script  -->
<section class="chat-section-pequeno" id="usuarioSection" style="display:none">
    <div class="chat-card-pequeno">
        <div class="chat-body mb-3">
            <div class="usuarios" style="margin-bottom:-30px">
                <select class="form-control text-center" name="OUTidUsuario" id="usuario">
                    <option value="<?php echo null ?>">
                        <?php echo "Selecione" ?>
                    </option>
                    <?php
                    foreach ($usuarios as $usuario) { ?>
                        <option value="<?php echo $usuario['idUsuario'] ?>">
                            <?php echo $usuario['nomeUsuario'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
</section>
<section class="chat-section-pequeno" id="live-chat" style="display:none">
    <div class="chat-card-pequeno">
        <div class="chat-card-pequeno-head">
            <div class="user-info">
                <p class="username" id="chat-geral"></i>Chat TradeSIS</p>
                <p class="username" id="chat-privado"></i>Chat Privado</p>
                <div class="col-sm" style="text-align:right">
                <!-- Gabriel 06102023 ID 575 somente usuarios internos podem criar demanda -->
                    <?php if ($_SESSION['idCliente'] == null) { ?>
                        <button type="button" id="btnNovo" class="btn btn-sm btn-success" data-toggle="modal"
                            data-target="#inserirDemandaModal"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                    <?php } ?>
                </div>
                <sup><ion-icon class="online-circle ml-1" name="ellipse"></ion-icon></sup>
            </div>
            <a href="#" class="chat-close" style="color:black">x</a>
        </div>
        <!-- chat body -->
        <div class="chat-body mb-3">
            <div class="chat-msg-pequeno" style="margin-bottom:-30px"></div>
            <!-- chat footer -->
            <div class="chat-footer" style="margin-bottom:-13px">
                <form method="post" id="formMsg" enctype="multipart/form-data">
                    <div class="form-inline input-group">
                        <!-- dados escondidos -->
                        <input type="hidden" class="form-control" name="INidUsuario"
                            value="<?php echo $_SESSION['idUsuario'] ?>" readonly>
                        <!-- Gabriel 05102023 ID 575 ID caixa de texto -->
                        <textarea name="chat" id="caixa-chat"
                            style="flex: 1; max-width: 80%; min-width: 5%; width: 78%; height: 30px; margin-top: 15px; box-sizing: border-box; resize: none;"></textarea>
                        <button type="submit" id="send-btn" class="btn btn-sm btn-success"
                            style="height: 30px; margin-top: 15px; min-width: 5%%; width: 22%; box-sizing: border-box;">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!--------- MODAL DEMANDA INSERIR --------->
<!-- <div class="modal fade bd-example-modal-lg" id="inserirDemandaModal" tabindex="-1" role="dialog"
    aria-labelledby="inserirDemandaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Inserir Solicitações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" id="form1">
                    <div class="row">
                        <div class="col-md form-group" style="margin-top: 5px;">
                            <label class='control-label' for='inputNormal' style="margin-top: 4px;">
                                Solicitação
                            </label>
                            <input type="text" class="form-control" name="tituloDemanda" autocomplete="off" required>
                            <input type="hidden" class="form-control" name="idContratoTipo" value="contratos" readonly>
                            <input type="hidden" class="form-control" name="INidUsuario"
                                value="<?php echo $_SESSION['idUsuario'] ?>" readonly>
                            <input type="hidden" class="form-control" name="OUTidUsuario" id="OUTidUsuario" readonly>
                        </div>
                        <div class="col-md-2 form-group-select" style="margin-top: -20px;">
                            <div class="form-group">
                                <label class="labelForm">Cliente</label>
                                <input type="hidden" class="form-control" name="idSolicitante"
                                    value="<?php echo $usuario['idUsuario'] ?>" readonly>
                                <select class="select form-control" name="idCliente" autocomplete="off"
                                    style="margin-top: -10px;">
                                    <?php
                                    foreach ($clientes as $cliente) {
                                        ?>
                                    <option value="<?php echo $cliente['idCliente'] ?>">
                                        <?php echo $cliente['nomeCliente'] ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-6">
                            <div class="container-fluid p-0">
                                <div class="col">
                                    <span class="tituloEditor">Descrição</span>
                                </div>
                                <div class="quill-demandainserir" style="height:20vh !important"></div>
                                <textarea style="display: none" id="quill-demandainserir" name="descricao"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6" style="margin-top: 25px;">
                            <div class="row">
                                <div class="col-md-6 form-group" style="margin-top: -25px;">
                                    <label class="labelForm">Previsão</label>
                                    <input type="number" class="data select form-control" name="horasPrevisao">
                                </div>
                                <div class="col-md-6 form-group-select" style="margin-top: -25px;">
                                    <label class="labelForm">Ocorrência</label>
                                    <select class="select form-control" name="idTipoOcorrencia" autocomplete="off">
                                        <option value="<?php echo null ?>">
                                            <?php echo "Selecione" ?>
                                        </option>
                                        <?php
                                        foreach ($tipoocorrencias as $tipoocorrencia) { ?>
                                        <option <?php if ($tipoocorrencia['ocorrenciaInicial'] == 1) {
                                            echo "selected";
                                        } ?> value="
                                            <?php echo $tipoocorrencia['idTipoOcorrencia'] ?>">
                                            <?php echo $tipoocorrencia['nomeTipoOcorrencia'] ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group-select" style="margin-top: -25px;">
                                    <label class="labelForm">Tamanho</label>
                                    <select class="select form-control" name="tamanho">
                                        <option value="<?php echo null ?>">
                                            <?php echo "Selecione" ?>
                                        </option>
                                        <option value="P">P</option>
                                        <option value="M">M</option>
                                        <option value="G">G</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group-select" style="margin-top: -25px; ">
                                    <label class="labelForm">Serviço</label>
                                    <select class="select form-control" name="idServico" autocomplete="off">
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
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group" style="margin-top: 40px;">
                                    <label class="labelForm">Responsável</label>
                                    <input type="text" class="data select form-control"
                                        value="<?php echo $_SESSION['usuario'] ?>" disabled>
                                    <input type="text" class="data select form-control" name="idAtendente"
                                        value="<?php echo $_SESSION['idUsuario'] ?>" hidden>
                                </div>
                                <div class="col-md-6 form-group-select" style="margin-top: 40px;">
                                    <label class="labelForm">Contrato Vinculado</label>
                                    <select class="select form-control" name="idContrato" autocomplete="off">
                                        <option value="<?php echo null ?>">
                                            <?php echo "Selecione" ?>
                                        </option>
                                        <?php foreach ($contratos as $contrato) { ?>
                                        <option value="<?php echo $contrato['idContrato'] ?>">
                                            <?php echo $contrato['tituloContrato'] ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit"
                            formaction="<?php echo URLROOT ?>/servicos/database/demanda.php?operacao=inserirChat"
                            class="btn btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

<script>
    $(document).ready(function () {
        var chatGeralValue = "false";
        var OUTidUsuarioValue = null;
        (function () {
            $('.chat-card-pequeno-head').on('click', function () {
                $('.chat-body').slideToggle(300, 'swing');
            });
            $('#chatTodos').on('click', function () {
                $('#live-chat').css('display', '');
                $('#btnNovo').css('display', 'none');
                $('#chat-geral').css('display', '');
                $('#chat-privado').css('display', 'none');
                $('.chat-card-pequeno-head').css('display', '');
                $('.chat-body').css('display', '');
                OUTidUsuarioValue = null;
                chatGeralValue = "true";
            });
            $('#chatUnico').on('click', function () {
                $('#live-chat').css('display', 'none');
                $('#usuarioSection').css('display', '');
            });
            $('#btnNovo').on('click', function () {
                $('#OUTidUsuario').val(OUTidUsuarioValue);
            });

            $("#usuario").change(function () {
                $('#live-chat').css('display', '');
                $('#btnNovo').css('display', '');
                $('#chat-geral').css('display', 'none');
                $('#chat-privado').css('display', '');
                $('.chat-card-pequeno-head').css('display', '');
                $('.chat-body').css('display', '');
                var selectedValue = $(this).val();
                OUTidUsuarioValue = selectedValue;
                chatGeralValue = "false";
            });
            $('.chat-close').on('click', function (e) {
                e.preventDefault();
                $('#usuario').val("");
                $('#live-chat').fadeOut(300);
                $('.chat-body').fadeOut(300);
                $('#usuarioSection').fadeOut(300);
            });
        })();

        let chatCont = document.querySelector(".chat-msg-pequeno");
        let errovl = $(".chat-msg-pequeno-ovl");
        let incoming_id = $("#incoming_id_inp");
        let subbtn = $("#send-btn");
        let incoming_msg = $("#chat-msg-pequeno");

        chatCont.onmouseenter = () => {
            chatCont.classList.remove("active");
        }

        chatCont.onmouseleave = () => {
            chatCont.classList.add("active");
        };

        let fetchmsgfunc = setInterval(() => {
            $.ajax({
                url: "demandas/logic.php",
                method: "post",
                dataType: "text",
                data: {
                    fetch_msg: "true",
                    buscar: "chat",
                    OUTidUsuario: OUTidUsuarioValue,
                    chat_geral: chatGeralValue
                },
                success: (data, stat) => {
                    if (data == "Null") {
                        errovl.show();
                        errovl.css("display", "flex");
                    } else if (data) {
                        errovl.hide();
                        errovl.css("display", "none");
                        chatCont.innerHTML = data;
                        if (chatCont.classList.contains("active")) {
                            scrollmsg();
                        }
                    }
                }
            });
        }, 500);

        let sendbtn = document.querySelector("#send-btn");
        subbtn.on("click", (e) => {
            e.preventDefault();
            let formData = new FormData(document.querySelector("#formMsg"));
            formData.append("OUTidUsuario", OUTidUsuarioValue);
            $.ajax({
                url: "database/mensagem.php?operacao=chat",
                method: "post",
                dataType: "json",
                data: formData,
                processData: false,
                contentType: false,
                success: (data, stat) => {
                    scrollmsg();
                },
            });
            //Gabriel 05102023 ID 575 limpa caixa de texto
            $("#caixa-chat").val("");
        });
        //Gabriel 05102023 ID 575 envia ao apertar enter
        document.addEventListener("keydown", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault(); 
                sendbtn.click();
            }
        });

        function scrollmsg() {
            chatCont.scrollTop = chatCont.scrollHeight;
        }
    });

    var demandaContrato = new Quill('.quill-demandainserir', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote'],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'direction': 'rtl'
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                ['link', 'image'],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                [{
                    'font': []
                }],
                [{
                    'align': []
                }],
            ]
        }
    });

    demandaContrato.on('text-change', function (delta, oldDelta, source) {
        $('#quill-demandainserir').val(demandaContrato.container.firstChild.innerHTML);
    });

</script>