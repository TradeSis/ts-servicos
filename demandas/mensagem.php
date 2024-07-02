<?php
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat
include_once '../header.php';
?>
<!-- Gabriel 05102023 ID 575 removido style, formato arquivo /excluido style -->
<link href="<?php URLROOT ?>/sistema/css/chat.css" rel="stylesheet" type="text/css">

<body class="bg-transparent">
    <div class="container-fluid" style="margin-bottom:70px">
        <section class="chat-section">
            <div class="chat-card">
                <!-- chat body -->
                <div class="chat-body">
                    <div class="chat-msg"></div>
                    <!-- chat footer -->
                    <div class="chat-footer" style="margin-bottom:-16px;">
                        <form method="post" id="formMsg" enctype="multipart/form-data">
                            <div class="form-inline input-group">
                                <!-- dados escondidos -->
                                <input type="hidden" class="form-control" name="idCliente"
                                    value="<?php echo $usuario['idCliente'] ?>" readonly>
                                <input type="hidden" class="form-control" name="idUsuario"
                                    value="<?php echo $usuario['idUsuario'] ?>" readonly>
                                <input type="hidden" name="idDemanda" value="<?php echo $idDemanda ?>" />
                                <input type="hidden" name="tipoStatusDemanda" value="<?php echo $idTipoStatus ?>" />
                                <textarea name="mensagem" id="mensagem" rows="3"
                                    style="flex: 1; max-width: 100%; min-width: 5%; width: 100%; margin-top: 15px; box-sizing: border-box; resize: none;"></textarea>
                                <input type="file" id="myFileMsg" class="custom-file-upload" name="nomeAnexoMsg"
                                    onchange="myFunction()" style="display: none;">
                                <label for="myFileMsg">
                                    <a class="btn btn-md btn-primary"
                                        style="width: 50px;height: 40px; margin-top: -19px;"><i
                                            class="bi bi-file-earmark-arrow-down-fill"></i></a>
                                </label>
                            </div>
                            <p id="mostraNomeAnexoMsg"></p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <div class="textoStyle1">
            <div class="textoStyle2"><span style="font-weight: bold;">Enter</span> para enviar</div>
            <div class="textoStyle2"><span style="font-weight: bold;">Shift + Enter</span> para quebrar linha</div>
            <div class="textoStyle2"><span style="font-weight: bold;">Botão Anexo</span> para adicionar midia</div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>


    <script>
        let chatCont = document.querySelector(".chat-msg");
        let errovl = $(".chat-msg-ovl");
        let incoming_msg = $("#chat-msg");

        chatCont.onmouseenter = () => {
            chatCont.classList.remove("active");
        }

        chatCont.onmouseleave = () => {
            chatCont.classList.add("active");
        };

        let fetchmsgfunc = setInterval(() => {
            $.ajax({
                url: "logic.php",
                method: "post",
                dataType: "text",
                data: {
                    fetch_msg: "true",
                    buscar: "mensagem",
                    idDemanda: <?php echo $idDemanda ?>,
                    idUsuario: <?php echo $_SESSION['idUsuario'] ?>
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

        document.addEventListener("keydown", function (e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                let formData = new FormData(document.querySelector("#formMsg"));
                $.ajax({
                    url: "../database/mensagem.php?operacao=comentar",
                    method: "post",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (data, stat) => {
                        scrollmsg();
                    }
                });
                $("#mensagem").val("");
                document.getElementById("mostraNomeAnexoMsg").innerHTML = "";
                document.getElementById("myFileMsg").value = "";
            }
        });

        function scrollmsg() {
            chatCont.scrollTop = chatCont.scrollHeight;
        }

        function myFunction() {
            var x = document.getElementById("myFileMsg");
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
            document.getElementById("mostraNomeAnexoMsg").innerHTML = txt;
        }
    </script>

</body>