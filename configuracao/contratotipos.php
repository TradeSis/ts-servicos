<?php
//Lucas 17102023 novo padrao
include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/contratotipos.php');
$contratoTipos = buscaContratoTipos();

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
                <h2 class="ts-tituloPrincipal">Contrato Tipos</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->

            </div>

            <div class="col-2 text-end">
                <a href="contratotipos_inserir.php" role="button" class="btn btn-success"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
            </div>
        </div>

        <div class="table mt-2 ts-divTabela">
            <table class="table table-hover table-sm align-middle">
                <thead class="ts-headertabelafixo">
                    <tr>
                        <th>Nome</th>
                        <th>Nome Contrato</th>
                        <th>Nome Demanda</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <?php
                foreach ($contratoTipos as $contratoTipo) {
                ?>
                    <tr>
                        <td><?php echo $contratoTipo['idContratoTipo'] ?></td>
                        <td><?php echo $contratoTipo['nomeContrato'] ?></td>
                        <td><?php echo $contratoTipo['nomeDemanda'] ?></td>

                        <td>
                            <a class="btn btn-warning btn-sm" href="contratotipos_alterar.php?idContratoTipo=<?php echo $contratoTipo['idContratoTipo'] ?>" role="button"><i class="bi bi-pencil-square"></i></a>
                            <a class="btn btn-danger btn-sm" href="contratotipos_excluir.php?idContratoTipo=<?php echo $contratoTipo['idContratoTipo'] ?>" role="button"><i class="bi bi-trash3"></i></a>
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