<?php
include_once(__DIR__ . '/../header.php');

include_once "../database/tipostatus.php";
include_once "../database/demanda.php";
include_once '../database/contratotipos.php';
include_once(ROOT . '/cadastros/database/usuario.php');
include_once(ROOT . '/cadastros/database/clientes.php');

$idContratoTipo = null;
// lucas 26062024 - id1090, manter no session tipo de contrato.
if(isset($_SESSION['idContratoTipo'])){
    $idContratoTipo = $_SESSION['idContratoTipo'];  
}

if (isset($_GET["idContratoTipo"])) {
    $idContratoTipo = $_GET["idContratoTipo"];
    $_SESSION['idContratoTipo'] = $idContratoTipo;
    $contratoDemanda = buscaContratoTipos($idContratoTipo);
} 

/*gabriel 14032024 - $contratotipo para ser usado no select e $contratoDemanda para ser usado no modal */
$contratoTipo = buscaContratoTipos();
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);

//echo json_encode(buscaDemandas(null, TIPOSTATUS_FILA, null, $usuario['idUsuario']))."<HR>";
if ($usuario["idCliente"] == null) {
    $clientes = buscaClientes($usuario["idCliente"]);
} else {
    $clientes = array(buscaClientes($usuario["idCliente"]));
}

?>


<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>

<body>
    
    <div class="container-fluid ts-kanbanFundo">
        <div class="row d-flex align-items-center justify-content-center pt-1 ">

            <div class="col-2 col-md-2">
                <h2 class="ts-tituloPrincipal">Fila de Atendimento</h2>
            </div>

            <div class="col-2 col-md-2">
                <form class="form-inline left" method="GET">
                    <div class="form-group">
                                              
                        <select class="form-select ts-input" name="idContratoTipo" class="form-control" onchange="this.form.submit()">
                            
                        <option value="<?php echo null ?>">
                                <?php echo "Todos" ?>
                            </option>
                            <?php
                            foreach ($contratoTipo as $tipo) {
                               
                                ?>
                                <option <?php
                                if ($tipo['idContratoTipo'] == $idContratoTipo) {
                                    echo "selected";
                                }
                                ?> value="<?php echo $tipo['idContratoTipo'] ?>">
                                    <?php echo $tipo['nomeContrato'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </div>

                                              
            <div class="col-8 col-md-8 text-end">
                <?php 
                    if ($idContratoTipo == "contratos" && $usuario["idCliente"] != null) {
                ?> 
                <button type="button" class="ms-4 btn btn-success ml-4" data-bs-toggle="modal" data-bs-target="#inserirDemandaCliente"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
                <?php 
                    }
                ?> 
            </div>
            
        </div>

        <?php include_once 'kanban.php' ?>

        <!-- Modal Inserir -->
        <?php include_once 'modalDemanda_inserir.php' ?>

        <div class="row row-cols-1 row-cols-md-5 pt-2 ts-kanban">
            <div class="col ps-1 pe-1">
                <div class="card p-1">
                    <div class="card-header ts-kanbanTitulo">
                        <?php $buscaTipoStatus = buscaTipoStatus(null, TIPOSTATUS_FILA) ?>
                        <h6><?php echo $buscaTipoStatus['nomeTipoStatus']; ?></h6>
                    </div>
                    <?php foreach (buscaDemandas(null, TIPOSTATUS_AGENDADO, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>

                    <?php foreach (buscaDemandas(null, TIPOSTATUS_FILA, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>

                </div>
            </div>

            <div class="col px-1">
                <div class="card p-1">
                    <div class="card-header ts-kanbanTitulo">
                        <?php $buscaTipoStatus = buscaTipoStatus(null, TIPOSTATUS_RETORNO) ?>
                        <h6><?php echo $buscaTipoStatus['nomeTipoStatus']; ?></h6>
                    </div>

                    <?php foreach (buscaDemandas(null, TIPOSTATUS_RETORNO, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>
                    <?php foreach (buscaDemandas(null, TIPOSTATUS_RESPONDIDO, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>

                </div>
            </div>

            <div class="col px-1">
                <div class="card p-1">
                    <div class="card-header ts-kanbanTitulo">
                        <?php $buscaTipoStatus = buscaTipoStatus(null, TIPOSTATUS_FAZENDO) ?>
                        <h6><?php echo $buscaTipoStatus['nomeTipoStatus']; ?></h6>
                    </div>

                    <?php foreach (buscaDemandas(null, TIPOSTATUS_FAZENDO, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>
                    <?php foreach (buscaDemandas(null, TIPOSTATUS_PAUSADO, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col px-1">
                <div class="card p-1">
                    <div class="card-header ts-kanbanTitulo">
                        <?php $buscaTipoStatus = buscaTipoStatus(null, TIPOSTATUS_AGUARDANDOSOLICITANTE) ?>
                        <h6><?php echo $buscaTipoStatus['nomeTipoStatus']; ?></h6>
                    </div>

                    <?php foreach (buscaDemandas(null, TIPOSTATUS_AGUARDANDOSOLICITANTE, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>
                </div>
            </div>


            <div class="col ps-1 pe-1">
                <div class="card p-1">
                    <div class="card-header ts-kanbanTitulo">
                        <?php $buscaTipoStatus = buscaTipoStatus(null, TIPOSTATUS_REALIZADO) ?>
                        <h6><?php echo $buscaTipoStatus['nomeTipoStatus']; ?></h6>
                    </div>

                    <?php foreach (buscaDemandas(null, TIPOSTATUS_REALIZADO, null, $usuario['idUsuario'], $usuario["idCliente"], $idContratoTipo) as $kanbanDemanda) : ?>
                        <?php echo montaKanban($kanbanDemanda); ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div><!-- row -->


    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>

    <script>
        //lucas 28032024 - adicionado na url idContratoTipo
        $(document).on('click', '#kanbanCard', function() {
            window.location.href = 'visualizar.php?idDemanda=' + $(this).attr('data-idDemanda') + '&&' + $(this).attr('data-idContratoTipo');
        });
    </script>
</body>

</html>