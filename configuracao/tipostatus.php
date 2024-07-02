<?php
// Lucas 17102023 novo padrao
// helio 07082023 - Botao POPUP
// helio 01022023 altereado para include_once
// helio 26012023 16:16
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/tipostatus.php');
$tiposstatus = buscaTipoStatus();
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>


<body>
    <div class="container-fluid">
        <div class="row">
             <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
             <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Tipos de Status</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->

            </div>

            <div class="col-2 text-end">
                <a href="tipostatus_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
            </div>
        </div>


        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Status</th>
                        <th>Ação</th>

                    </tr>
                </thead>

                <?php
                foreach ($tiposstatus as $tipostatus) {
                ?>
                    <tr>
                        <td><?php echo $tipostatus['nomeTipoStatus'] ?></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="tipostatus_alterar.php?idTipoStatus=<?php echo $tipostatus['idTipoStatus'] ?>" role="button"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm" href="tipostatus_excluir.php?idTipoStatus=<?php echo $tipostatus['idTipoStatus'] ?>" role="button"><i class="bi bi-trash3"></i></a>
                            
                            <button id="<?php echo $tipostatus['idTipoStatus'] ?>" class='btn btn-outline-warning btn-sm' onclick="popTipoStatus(<?php echo $tipostatus['idTipoStatus'] ?>)">Editar</button>
                        </td>
                       
                    </tr>
                <?php } ?>

            </table>
        </div>

    </div>


    <div class="modal fade" id="popTipoStatus" tabindex="-1" aria-labelledby="popTipoStatusLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUsuarioModalLabel">Tipo de Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-form">
                        <span id="msgAlertaErroEdit"></span>


                        <input type="text" id="idTipoStatus" name="idTipoStatus" class="form-control" placeholder="">
                        <div class="mb-3">

                            <input type="text" name="nomeTipoStatus" class="form-control" id="nomeTipoStatus" placeholder="">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="labelForm">Atendimento(0=Atendente 1=Cliente)</label>
                                <select class="form-control" id="mudaPosicaoPara" name="mudaPosicaoPara">

                                    <option>0</option>
                                    <option>1</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="labelForm">Situação (0=Fechado 1=Aberto)</label>
                                <select class="form-control" id="mudaStatusPara" name="mudaStatusPara">

                                    <option>0</option>
                                    <option>1</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                            <input type="submit" class="btn btn-outline-warning btn-sm" id="edit-btn" value="Salvar" />
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>

        const editForm = document.getElementById("edit-form");
        const popTipoStatusModal = new bootstrap.Modal(document.getElementById("popTipoStatus"));

        // Logica para Visualizar via Modal
        async function popTipoStatus(idTipoStatus) {

            const dados = await fetch("<?php echo URLROOT ?>/services/database/tipostatus.php?operacao=GET_JSON&idTipoStatus=" + idTipoStatus);
            const resposta = await dados.json();
            alert(JSON.stringify(resposta, null, 2));
            //const popTipoStatus = new bootstrap.Modal(document.getElementById("popTipoStatus"));
            popTipoStatusModal.show();
            document.getElementById("idTipoStatus").innerHTML = resposta.idTipoStatus;
            //  document.getElementById("nomeTipoStatus").innerHTML = resposta.nomeTipoStatus;
            // document.getElementById("mudaPosicaoPara").innerHTML = resposta.mudaPosicaoPara;

            document.getElementById("idTipoStatus").value = resposta.idTipoStatus;
            document.getElementById("nomeTipoStatus").value = resposta.nomeTipoStatus;
            document.getElementById("mudaPosicaoPara").value = resposta.mudaPosicaoPara;
            document.getElementById("mudaStatusPara").value = resposta.mudaStatusPara;

        }



        editForm.addEventListener("submit", async (e) => {

            e.preventDefault();

            document.getElementById("edit-btn").value = "Salvando...";

            const dadosForm = new FormData(editForm);
            var str = $("edit-form").serialize();
            //console.log(str);
            //console.log(editForm);
            //console.log(dadosForm);

            /*for (var dadosFormEdit of dadosForm.entries()){
                console.log(dadosFormEdit[0] + " - " + dadosFormEdit[1]);
            }*/

            const dados = await fetch("<?php echo URLROOT ?>/services/database/tipostatus.php?operacao=JSON_alterar", {
                method: "POST",
                body: dadosForm
            });

            const resposta = await dados.json();
            console.log(resposta);

            document.getElementById("edit-btn").value = "Salvar";

            editForm.reset();
            popTipoStatusModal.hide();
            top.window.location = "<?php echo URLROOT ?>/services/?tab=configuracao&stab=tipostatus";

        });
    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>