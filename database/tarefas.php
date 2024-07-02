<?php
//Lucas 08112023 - id965 Melhorias Tarefas
//Lucas 07112023 id965 - Melhorias Tarefas 
// lucas id654 - Melhorias Tarefas
//Gabriel 06102023 ID 596 mudanças em agenda e tarefas
//lucas 25092023 ID 358 Demandas/Comentarios
//lucas 22092023 ID 358 Demandas/Comentarios 
// helio 21032023 - compatibilidade chamada chamaApi
// gabriel 06022023 calculo timediff
// gabriel 15:10
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


include_once __DIR__ . "/../conexao.php";

function buscaTarefas($idDemanda = null, $idTarefa = null, $idAtendente = null, $statusTarefa = null, $idCliente = null)
{

    $tarefas = array();

    $idEmpresa = null;
    if (isset($_SESSION['idEmpresa'])) {
        $idEmpresa = $_SESSION['idEmpresa'];
    }

    $apiEntrada = array(
        'idEmpresa' => $idEmpresa,
        'idDemanda' => $idDemanda,
        'idTarefa' => $idTarefa,
        'idAtendente' => $idAtendente,
        'statusTarefa' => $statusTarefa,
        'idCliente' => $idCliente
    );
    $tarefas = chamaAPI(null, '/servicos/tarefas', json_encode($apiEntrada), 'GET');
    return $tarefas;
}

function buscaTarefasGrafico1()
{

    $dadosGrafico1 = array();

    $idEmpresa = null;
    if (isset($_SESSION['idEmpresa'])) {
        $idEmpresa = $_SESSION['idEmpresa'];
    }
    $apiEntrada = array(
        'idEmpresa' => $idEmpresa,
    );
    $dadosGrafico1 = chamaAPI(null, '/servicos/tarefas/grafico1', json_encode($apiEntrada), 'GET');
    return $dadosGrafico1;
}
function buscaTarefasGrafico2()
{

    $idEmpresa = null;
    if (isset($_SESSION['idEmpresa'])) {
        $idEmpresa = $_SESSION['idEmpresa'];
    }

    $apiEntrada = array(
        'idEmpresa' => $idEmpresa
    );
    $dadosGrafico2 = array();
    $dadosGrafico2 = chamaAPI(null, '/servicos/tarefas/grafico2', json_encode($apiEntrada), 'GET');
    return $dadosGrafico2;
}
function buscaTarefasGrafico3()
{

    $idEmpresa = null;
    if (isset($_SESSION['idEmpresa'])) {
        $idEmpresa = $_SESSION['idEmpresa'];
    }

    $apiEntrada = array(
        'idEmpresa' => $idEmpresa
    );
    $dadosGrafico3 = array();

    $dadosGrafico3 = chamaAPI(null, '/servicos/tarefas/grafico3', json_encode($apiEntrada), 'GET');
    return $dadosGrafico3;
}

function buscaTarefasGrafico4()
{
    $idEmpresa = null;
    if (isset($_SESSION['idEmpresa'])) {
        $idEmpresa = $_SESSION['idEmpresa'];
    }

    $apiEntrada = array(
        'idEmpresa' => $idEmpresa
    );

    $dadosGrafico4 = array();
    $dadosGrafico4 = chamaAPI(null, '/servicos/tarefas/grafico4', json_encode($apiEntrada), 'GET');
    return $dadosGrafico4;
}
function buscaHoras($idDemanda)
{

    $horas = array();

    $idEmpresa = null;
    if (isset($_SESSION['idEmpresa'])) {
        $idEmpresa = $_SESSION['idEmpresa'];
    }

    $apiEntrada = array(
        'idEmpresa' => $idEmpresa,
        'idDemanda' => $idDemanda
    );
    $horas = chamaAPI(null, '/servicos/horas', json_encode($apiEntrada), 'GET');
    return $horas;
}


if (isset($_GET['operacao'])) {

    $operacao = $_GET['operacao'];
    $idEmpresa = null;
    if (isset($_SESSION['idEmpresa'])) {
        $idEmpresa = $_SESSION['idEmpresa'];
    }

    if ($operacao == "inserir") {

        $acao = "";
        if (isset($_GET['acao'])) {
            $acao = $_GET['acao'];
        }
        
        $idTipoOcorrencia = $_POST['idTipoOcorrencia'];
        if($idTipoOcorrencia == ''){
            $idTipoOcorrencia = OCORRENCIA_PADRAO;
        }
       
        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'tituloTarefa' => $_POST['tituloTarefa'],
            'idCliente' => $_POST['idCliente'],
            'idDemanda' => $_POST['idDemanda'],
            'idAtendente' => $_POST['idAtendente'],
            'idTipoOcorrencia' => $idTipoOcorrencia,
            'Previsto' => $_POST['Previsto'],
            'horaInicioPrevisto' => $_POST['horaInicioPrevisto'],
            'horaFinalPrevisto' => $_POST['horaFinalPrevisto'],
            'acao' => $acao
            
        );
     
        $tarefas = chamaAPI(null, '/servicos/tarefas', json_encode($apiEntrada), 'PUT');
      
        if(isset($_GET['redirecionarDemanda'])){
            header('Location: ../demandas/visualizar.php?id=tarefas&&idDemanda=' . $apiEntrada['idDemanda']);
        }
        echo json_encode($tarefas);
        return $tarefas;

    }



    if ($operacao == "alterar") {

        //Gabriel 23102023 novo modelo de sql para alterar
        // Lucas 20112023 - ID 965 - alterado teste de entrada para api

        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idTarefa' => $_POST['idTarefa'],
            // Lucas 20112023 - ID 965 removido idDemanda
            //Gabriel 11102023 ID 596 adicionado Descriçao e idAtendente
            'descricao' => $_POST['descricao'],
            'idAtendente' => $_POST['idAtendente'],
            //Gabriel 11102023 ID 596 adicionado idCliente
            'idCliente' => $_POST['idCliente'],
            'tituloTarefa' => $_POST['tituloTarefa'],
            'idTipoOcorrencia' => $_POST['idTipoOcorrencia'],
            // Lucas 08112023 - id965 removido horascobrado
            // Lucas 20112023 - id965 removido horaReal,horaInicioReal e horaFinalReal
            'Previsto' => $_POST['Previsto'],
            'horaInicioPrevisto' => $_POST['horaInicioPrevisto'],
            'horaFinalPrevisto' => $_POST['horaFinalPrevisto']
        );
        
        $tarefas = chamaAPI(null, '/servicos/tarefas', json_encode($apiEntrada), 'POST');
        echo json_encode($apiEntrada);
        return $tarefas;

    }

    if ($operacao == "realizado") {
        // Operações de : REALIZADO, START, STOP e ENTREGUE
        $acao = "realizado";
        if (isset($_GET['acao'])) {
            $acao = $_GET['acao'];
        }

		$comentario = isset($_POST["comentario"]) && $_POST["comentario"] !== "" ? $_POST["comentario"] : null;

        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idTarefa' => $_POST['idTarefa'],
            'comentario' => $comentario,
            'acao' => $acao 
        );
    
        $tarefas = chamaAPI(null, '/servicos/tarefas/realizado', json_encode($apiEntrada), 'POST');

        //if (($acao == "stop") || ($acao == "entregue")) {  
            if(isset($_POST['comentario']) && ($_POST['comentario']) !== ""){
                $apiEntrada2 = array(
                    'idEmpresa' => $_SESSION['idEmpresa'],
                    'idUsuario' => $_POST['idUsuario'],
                    'idCliente' => $_POST['idCliente'],
                    'idDemanda' => $_POST['idDemanda'],
                    'comentario' => $_POST['comentario'],
                );
                $comentario2 = chamaAPI(null, '/servicos/comentario/cliente', json_encode($apiEntrada2), 'PUT');
            }
            $idDemanda = $_POST['idDemanda'];
            if(isset($_GET['redirecionarDemanda'])){
                header('Location: ../demandas/visualizar.php?id=tarefas&&idDemanda=' . $idDemanda);
            }
            
        //}
        
        echo json_encode($tarefas);
        return $tarefas;
    }

  

    if ($operacao == "filtrar") {

        $idCliente = $_POST['idCliente'];
        $idAtendente = $_POST['idAtendente'];
        $tituloTarefa = $_POST['tituloTarefa'];
        $idTipoOcorrencia = $_POST['idTipoOcorrencia'];
        $statusTarefa = $_POST['statusTarefa'];
        //Lucas 07112023 id965 - removido variavel do filtro periodo 
        $PeriodoInicio = $_POST['PeriodoInicio'];
        $PeriodoFim = $_POST['PeriodoFim'];
        // lucas id654 - Removido PrevistoOrderm e RealOrdem, e adicionado dataOrdem no lugar
        $dataOrdem = $_POST['dataOrdem'];
        $buscaTarefa = $_POST['buscaTarefa'];

        if ($idCliente == "") {
            $idCliente = null;
        }
        if ($idAtendente == "") {
            $idAtendente = null;
        }
        if ($tituloTarefa == "") {
            $tituloTarefa = null;
        }
        if ($idTipoOcorrencia == "") {
            $idTipoOcorrencia = null;
        }
        if ($statusTarefa == "") {
            $statusTarefa = null;
        }
        
        //Lucas 07112023 id965 - removido variavel do filtro periodo 
        
        if ($PeriodoInicio == "") {
            $PeriodoInicio = null;
        }
        if ($PeriodoFim == "") {
            $PeriodoFim = null;
        }
        if ($dataOrdem == "") {
            $dataOrdem = null;
        }
        if ($buscaTarefa == "") {
            $buscaTarefa = null;
        }

        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idCliente' => $idCliente,
            'idAtendente' => $idAtendente,
            'tituloTarefa' => $tituloTarefa,
            'idTipoOcorrencia' => $idTipoOcorrencia,
            'statusTarefa' => $statusTarefa,
            //Lucas 07112023 id965 - removido variavel do filtro periodo 
            'PeriodoInicio' => $PeriodoInicio,
            'PeriodoFim' => $PeriodoFim,
            'dataOrdem' => $dataOrdem,
            'buscaTarefa' => $buscaTarefa
        );

        $_SESSION['filtro_tarefas'] = $apiEntrada;
        $tarefas = chamaAPI(null, '/servicos/tarefas', json_encode($apiEntrada), 'GET');

        echo json_encode($tarefas);
        return $tarefas;
    }

    if ($operacao == "buscar") {
        $idTarefa = $_POST['idTarefa'];
        if ($idTarefa == "") {
            $idTarefa = null;
        }
        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idTarefa' => $idTarefa
        );
        $tarefas = chamaAPI(null, '/servicos/tarefas', json_encode($apiEntrada), 'GET');

        echo json_encode($tarefas);
        return $tarefas;
    }


    if ($operacao == "filtroAgenda") {

        $idAtendente = $_POST['idAtendente'];
        $statusTarefa = $_POST['statusTarefa'];
        $idCliente = $_POST['idCliente'];
      
        if ($idAtendente == "") {
            $idAtendente = null;
        }
        if ($statusTarefa == "") {
            $statusTarefa = null;
        }
        if ($idCliente == "") {
            $idCliente = null;
        }

        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,  
            'statusTarefa' => $statusTarefa,
            'idAtendente' => $idAtendente,
            'idCliente' => $idCliente
        );

        $_SESSION['filtro_agenda'] = $apiEntrada;
    }

    //Gabriel 22092023 id542 operação ultimoTab em session
    if ($operacao == "ultimoTab") {
        $_SESSION['ultimoTab'] = $_POST['ultimoTab'];
    }
    /*
        include "../demandas/tarefas_ok.php";
    */
    //header('Location: ../demandas/tarefas.php');
}