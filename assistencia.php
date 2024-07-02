<!DOCTYPE html>
<head>
        <title>Assistência</title>
</head>
<html>

<?php
include_once __DIR__ . "/../config.php";
include_once ROOT . "/sistema/painel.php";
include_once ROOT . "/sistema/database/loginAplicativo.php";

$nivelMenuLogin = buscaLoginAplicativo($_SESSION['idLogin'], 'Services');


$configuracao = 1;

$nivelMenu = $nivelMenuLogin['nivelMenu'];



?>


<div class="container-fluid mt-1">
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center">
            <ul class="nav a" id="myTabs">


                <?php
                $tab = ''; // default
                
                if (isset($_GET['tab'])) {
                    $tab = $_GET['tab'];
                }

                if ($nivelMenu >= 1) {
                    if ($tab == '') {
                        $tab = 'servicos';
                    } ?>

                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "servicos") {
                            echo " active ";
                        } ?>" href="?tab=servicos" role="tab">Serviços</a>
                    </li>

                <?php }

                if ($nivelMenu >= 1) { ?>
                    <li class="nav-item mr-1">
                        <a class="nav-link1 nav-link <?php if ($tab == "os") {
                            echo " active ";
                        } ?>" href="?tab=os" role="tab">O.S.</a>
                    </li>
                <?php }

                ?>


            </ul>


        </div>

    </div>

</div>

<?php
$src = "";

if ($tab == "servicos") {
    $src = "demandas/?tipo=os";
}

if ($tab == "solicitacoes") {
    $src = "demandas/?tipo=contratos";
}
if ($tab == "atividades") {
    $src = "demandas/?tipo=projetos";
}
if ($tab == "os") {
    $src = "contratos/?tipo=os";
}

if ($tab == "contratos") {
    $src = "contratos/?tipo=contratos";
}
if ($tab == "projetos") {
    $src = "contratos/?tipo=projetos";
}
if ($tab == "execucao") {
    $src = "demandas/tarefas.php";
}
if ($tab == "dashboard") {
    $src = "demandas/dashboard.php";
}
if ($tab == "agenda") {
    $src = "demandas/agenda.php";
}
if ($tab == "configuracao") {
    $src = "configuracao/";
    if (isset($_GET['stab'])) {
        $src = $src . "?stab=" . $_GET['stab'];
    }


}

if ($src !== "") {
    //echo URLROOT ."/services/". $src;
    ?>
    <div class="diviFrame">
        <iframe class="iFrame container-fluid " id="iFrameTab"
            src="<?php echo URLROOT ?>/services/<?php echo $src ?>"></iframe>
    </div>
    <?php
}
?>

</body>

</html>