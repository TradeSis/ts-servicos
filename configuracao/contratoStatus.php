<?php
//Lucas 17102023 novo padrao
// helio 26012023 16:16
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/contratoStatus.php');

$contratoStatus = buscaContratoStatus();

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
                <h2 class="ts-tituloPrincipal">Contrato Status</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->

            </div>

            <div class="col-2 text-end">
                <a href="contratoStatus_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
            </div>
        </div>


        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Nome Status</th>
                        <th>Ação</th>

                    </tr>
                </thead>

                <?php

                foreach ($contratoStatus as $contratostatus) {
                ?>
                    <tr>
                        <td>
                            <?php echo $contratostatus['nomeContratoStatus'] ?>
                            <?php //echo json_encode($contratoStatus) 
                            ?>
                        </td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="contratoStatus_alterar.php?idContratoStatus=<?php echo $contratostatus['idContratoStatus'] ?>" role="button"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm" href="contratoStatus_excluir1.php?idContratoStatus=<?php echo $contratostatus['idContratoStatus'] ?>" role="button"><i class="bi bi-trash3"></i></a>

                            <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#?idContratoStatus=<?php echo $contratostatus['idContratoStatus'] ?>" role="button">Excluir</a>

                            <div class="modal fade" id="?idContratoStatus=<?php echo $contratostatus['idContratoStatus'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Excluir Status</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="card-header">

                                        </div>
                                        <div class="container" style="margin-top: 10px">
                                            <form action="../database/contratoStatus.php?operacao=excluir" method="post">
                                                <div class="form-group" style="margin-top:10px">
                                                    <label>Nome Status</label>
                                                    <input type="text" class="form-control" name="nomeContratoStatus" value="<?php echo $contratostatus['nomeContratoStatus'] ?>">
                                                    <input type="text" class="form-control" name="idContratoStatus" value="<?php echo $contratostatus['idContratoStatus'] ?>" style="display: none">
                                                </div>
                                                <div class="card-footer py-2">
                                                    <div style="text-align:right">
                                                        <button type="submit" class="btn btn-sm btn-success">Excluir</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>


                                    </div>
                                </div>
                            </div>


                        </td>
                    </tr>

                <?php } ?>

            </table>
        </div>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>