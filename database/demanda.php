<?php
//lucas 28112023 id706 - Melhorias Demandas 2
// Lucas 25102023 id643 revisao geral
//Gabriel 05102023 ID 575 Demandas/Comentarios - Layout de chat
//lucas 26092023 ID 576 Demanda/BOTÕES de SITUACOES 
// Gabriel 22092023 id 544 Demandas - Botão Voltar
//lucas 22092023 ID 358 Demandas/Comentarios 
// Lucas 30032023 - modificado operação comentar para ser inserido anexos.
// gabriel 220323 11:19 - adicionado operação retornar demanda
// Lucas 21032023 adicionado a operação filtrar, Clientes,Usuarios,TipoStatus  e tipoOcorrencia.
// Lucas 20032023 adicionado operação filtrar
// gabriel 06032023 11:25 alteração de descricao demanda
// gabriel 02032023 12:13 alteração de titulo demanda
// Lucas 18022023 passado dois parametros na função buscaDemandas($idDemanda, $idTipoStatus)
// gabriel 06022023 adicionado inner join usuario linha 27, idatendente ao inserir demanda e prioridade no alterar
// helio 01022023 altereado para include_once, usando funcao conectaMysql
// gabriel 31012023 13:47 - nomeclaturas, operação encerrar
// helio 26012023 16:16

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

include_once __DIR__ . "/../conexao.php";

function buscaDemandas($idDemanda = null, $idTipoStatus = null, $idContrato = null, $idUsuario = null, $idCliente = null, $idContratoTipo = null)
{

	$demanda = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	if ($idContratoTipo == ""){
		$idContratoTipo = null;
	}
	$apiEntrada = array(
		'idDemanda' => $idDemanda,
		'idTipoStatus' => $idTipoStatus,
		'idContrato' => $idContrato,
		'idEmpresa' => $idEmpresa,
		'idUsuario' => $idUsuario,
		'idCliente' => $idCliente,
		'idContratoTipo' => $idContratoTipo
	);
	$demanda = chamaAPI(null, '/servicos/demanda', json_encode($apiEntrada), 'GET');

	return $demanda;
}

function buscaComentarios($idDemanda = null, $idComentario = null)
{

	$comentario = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idDemanda' => $idDemanda,
		'idComentario' => $idComentario,
		'idEmpresa' => $idEmpresa,
	);
	$comentario = chamaAPI(null, '/servicos/comentario', json_encode($apiEntrada), 'GET');
	return $comentario;
}

function buscaCardsDemanda()
{
	$cards = array();


	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	
	$apiEntrada = array(
		'idEmpresa' => $idEmpresa
	);
	$cards = chamaAPI(null, '/servicos/demandas/totais', json_encode($apiEntrada), 'GET');
	return $cards;
}

function buscaDemandasAbertas($statusDemanda=1) //Aberto
{
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	$demanda = array();
	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'statusDemanda' => $statusDemanda
	);
	$demanda = chamaAPI(null, '/servicos/demanda', json_encode($apiEntrada), 'GET');

	return $demanda;
}

function buscaTotalHorasCobrada($idContrato=null)
{
	$horas = array();


	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	
	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idContrato' => $idContrato
	);
	$horas = chamaAPI(null, '/servicos/demanda_horasCobrado', json_encode($apiEntrada), 'GET');
	return $horas;
}

function buscaTotalHorasReal($idContrato=null, $idDemanda=null)
{
	$horas = array();


	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	
	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idContrato' => $idContrato,
		'idDemanda' => $idDemanda
	);
	$horas = chamaAPI(null, '/servicos/demanda_horasReal', json_encode($apiEntrada), 'GET');
	return $horas;
}

if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];

	if ($operacao == "inserir") {

		$acao = '';
		if(isset($_GET['acao'])){
			$acao = $_GET['acao'];
		}
		if($acao == ''){
			$apiEntrada = array(
				'idEmpresa' => $_SESSION['idEmpresa'],
				'idCliente' => $_POST['idCliente'],
				'idSolicitante' => $_POST['idSolicitante'],
				'tituloDemanda' => $_POST['tituloDemanda'],
				'descricao' => $_POST['descricao'],
				'idServico' => $_POST['idServico'], //SERVICOS_PADRAO,
				//'idTipoStatus' => $_POST['idTipoStatus'], //TIPOSTATUS_FILA,
				'idContrato' => $_POST['idContrato'],
				'idContratoTipo' => $_POST['idContratoTipo'],
				'horasPrevisao' => $_POST['horasPrevisao'],
				// lucas 21112023 - removido campo tamanho
				'idAtendente' => $_POST['idAtendente'],
				'dataPrevisaoEntrega' => $_POST['dataPrevisaoEntrega'],
				'dataPrevisaoInicio' => $_POST['dataPrevisaoInicio'],
				'tempoCobrado' => $_POST['tempoCobrado'],
			);
		}

		if($acao == 'visaocli'){
			$apiEntrada = array(
				'idEmpresa' => $_SESSION['idEmpresa'],
				'tituloDemanda' => $_POST['tituloDemanda'],
				'descricao' => $_POST['descricao'],
				'idSolicitante' => $_POST['idSolicitante'], 
				'idUsuario' => $_POST['idUsuario'],
				// gabriel 05022024 - adicionado campo idContrato
				'idContrato' => $_POST['idContrato'],
				'idContratoTipo' => $_POST['idContratoTipo'],
				'idCliente' => $_POST['idCliente']
			);
		}
		
		$demanda = chamaAPI(null, '/servicos/demanda', json_encode($apiEntrada), 'PUT');

		//Gabriel 29052024 removido enviar email antes de cadastrar demanda, ajustado em api
		
		echo json_encode($demanda);
		return $demanda;
	}

	//Gabriel 05102023 ID 575 inserir com mensagens do chat
	//Gabriel 28052024 removido chat
	

	if ($operacao == "alterar") {

		$acao = "";
        if (isset($_GET['acao'])) {
            $acao = $_GET['acao'];
        }

		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idDemanda' => $_POST['idDemanda'],
			'idContrato' => $_POST['idContrato'],
			'tituloDemanda' => $_POST['tituloDemanda'],
			// lucas 06122023 id715  - removido descricao
			'prioridade' => $_POST['prioridade'],
			'idServico' => $_POST['idServico'],
			// lucas 21112023 id 688 - removido campo tamanho
			// gabriel 28052024 id 981 - removido atendente de alterar
			'horasPrevisao' => $_POST['horasPrevisao'],
			// lucas 21112023 id 688 - removido campo idContratoTipo
			'dataPrevisaoEntrega' => $_POST['dataPrevisaoEntrega'],
			'dataPrevisaoInicio' => $_POST['dataPrevisaoInicio'],
			'tempoCobrado' => $_POST['tempoCobrado'],
			'acao' => $acao
		);
		$demanda = chamaAPI(null, '/servicos/demanda', json_encode($apiEntrada), 'POST');

		if($acao == "visaocli"){
			header('Location: ../visaocli/visualizar.php?idDemanda=' . $apiEntrada['idDemanda']);
		}
		header('Location: ../demandas/visualizar.php?idDemanda=' . $apiEntrada['idDemanda']);
	}
	
	if ($operacao == "atualizar") {

		$acao = "";
        if (isset($_GET['acao'])) {
            $acao = $_GET['acao'];
        }

		$comentario = isset($_POST["comentario"]) && $_POST["comentario"] !== "" ? $_POST["comentario"] : null;
		$idAtendente = isset($_POST["idAtendente"]) && $_POST["idAtendente"] !== "" ? $_POST["idAtendente"] : null;
		$idLogin = isset($_POST["idLogin"]) && $_POST["idLogin"] !== "" ? $_POST["idLogin"] : null;
		$interno = isset($_POST["interno"]) && $_POST["interno"] !== "" ? $_POST["interno"] : 0;

		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idDemanda' => $_POST['idDemanda'],
			'idAtendente' => $idAtendente,//utilizado quando ação for encaminhar
			'idLogin' => $idLogin,//utilizado quando ação for subdemanda
			'comentario' => $comentario,
			'interno' => $interno,
			'acao' => $acao
		);

		if(isset($_POST['comentario']) && ($_POST['comentario']) !== ""){
			$apiEntrada2 = array(
				'idEmpresa' => $_SESSION['idEmpresa'],
				'idUsuario' => $_POST['idUsuario'],
				'idCliente' => $_POST['idCliente'],
				'idDemanda' => $_POST['idDemanda'],
				'interno' => $interno,
				'comentario' => $_POST['comentario'],
			);
			$comentario2 = chamaAPI(null, '/servicos/comentario/cliente', json_encode($apiEntrada2), 'PUT');
		}
		
		$demanda = chamaAPI(null, '/servicos/demanda/atualizar', json_encode($apiEntrada), 'POST');
		if ($_POST['origem'] == "demandas") {
			header('Location: ../demandas/visualizar.php?idDemanda=' . $apiEntrada['idDemanda']);
		}
		if ($_POST['origem'] == "visaocli")  {
			header('Location: ../visaocli/visualizar.php?idDemanda=' . $apiEntrada['idDemanda'] . '&&' . $_POST['idTipoContrato']);
		}

	}
	

	// lucas 22112023 id 688 - removido operação comentarioAtendente
	if ($operacao == "comentar") {
	
		$enviaEmailComentario = '';
		if(isset($enviaEmailComentario)){
			$enviaEmailComentario = $_POST['enviaEmailComentario'];
		}
		$interno = isset($_POST["interno"]) && $_POST["interno"] !== "" ? $_POST["interno"] : 0;

		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idUsuario' => $_POST['idUsuario'],
			'idCliente' => $_POST['idCliente'],
			'idDemanda' => $_POST['idDemanda'],
			'interno' => $interno,
			'comentario' => $_POST['comentario'],
			'enviaEmailComentario' => $enviaEmailComentario

		);

		$comentario = chamaAPI(null, '/servicos/comentario', json_encode($apiEntrada), 'PUT');
		
		if ($_POST['origem'] == "demandas") {
			header('Location: ../demandas/visualizar.php?idDemanda=' . $apiEntrada['idDemanda']);
		}
		if ($_POST['origem'] == "visaocli")  {
			header('Location: ../visaocli/visualizar.php?idDemanda=' . $apiEntrada['idDemanda'] . '&&' . $_POST['idTipoContrato']);
		}
	}

	if ($operacao == "descricao") {
		
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idDemanda' => $_POST['idDemanda'],
			'descricao' => $_POST['descricao'],
		);
		$demanda = chamaAPI(null, '/servicos/demanda_descricao', json_encode($apiEntrada), 'POST');

		header('Location: ../demandas/visualizar.php?idDemanda=' . $apiEntrada['idDemanda']);
	}

	if ($operacao == "filtrar") {

		$idCliente = $_POST['idCliente'];
		$idSolicitante = $_POST['idSolicitante'];
		$idTipoStatus = $_POST['idTipoStatus'];
		//Lucas 28112023 id706 - removido idTipoOcorrencia e adicionado idServico
		$idServico = $_POST['idServico'];
		$idAtendente = $_POST['idAtendente'];
		$statusDemanda = $_POST['statusDemanda'];
		$buscaDemanda = $_POST['buscaDemanda'];
		$idContratoTipo = $_POST["urlContratoTipo"];

		if ($idCliente == "") {
			$idCliente = null;
		}

		if ($idSolicitante == "") {
			$idSolicitante = null;
		}

		if ($idAtendente == "") {
			$idAtendente = null;
		}

		if ($idTipoStatus == "") {
			$idTipoStatus = null;
		}

		if ($idServico == "") {
			$idServico = null;
		}

		if ($statusDemanda == "") {
			$statusDemanda = null;
		}


		if ($buscaDemanda == "") {
			$buscaDemanda = null;
		}

		if ($idContratoTipo == ""){
			$idContratoTipo = null;
		}


		$idEmpresa = null;
		if (isset($_SESSION['idEmpresa'])) {
			$idEmpresa = $_SESSION['idEmpresa'];
		}

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idCliente' => $idCliente,
			'idSolicitante' => $idSolicitante,
			'idAtendente' => $idAtendente,
			'idTipoStatus' => $idTipoStatus,
			'idServico' => $idServico,
			'statusDemanda' => $statusDemanda,
			'buscaDemanda' => $buscaDemanda,
			'idContratoTipo' => $idContratoTipo,
		);

		$_SESSION['filtro_demanda'] = $apiEntrada;
		$demanda = chamaAPI(null, '/servicos/demanda', json_encode($apiEntrada), 'GET');

		echo json_encode($demanda);
		return $demanda;
	}

	if ($operacao == "dashboard") {

		$nomeCard = isset($_POST["nomeCard"]) && $_POST["nomeCard"] !== "" ? $_POST["nomeCard"] : null;
		$idContratoTipo = isset($_POST["idContratoTipo"]) && $_POST["idContratoTipo"] !== ""  ? $_POST["idContratoTipo"]  : null;
		$idCliente = isset($_POST["idCliente"]) && $_POST["idCliente"] !== ""  ? $_POST["idCliente"]  : null;
		
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'nomeCard'=> $nomeCard,
			'idContratoTipo'=> $idContratoTipo,
			'idCliente'=> $idCliente,
			'mes'=> $_POST["mes"],
			'ano'=> $_POST["ano"]	
		);

		$dashboard = chamaAPI(null, '/servicos/demanda_dashboard', json_encode($apiEntrada), 'GET');

		echo json_encode($dashboard);
		return $dashboard;

		
	}

	if ($operacao == "dashboardtabela") {

		$idCliente = $_POST['idCliente'];
		$idSolicitante = $_POST['idSolicitante'];
		$idTipoStatus = $_POST['idTipoStatus'];
		$idServico = $_POST['idServico'];
		$idAtendente = $_POST['idAtendente'];
		$statusDemanda = $_POST['statusDemanda'];
		$buscaDemanda = $_POST['buscaDemanda'];
		$idContratoTipo = $_POST["urlContratoTipo"];
		$card = $_POST["card"];
		if ($idCliente == "") {
			$idCliente = null;
		}

		if ($idSolicitante == "") {
			$idSolicitante = null;
		}

		if ($idAtendente == "") {
			$idAtendente = null;
		}

		if ($idTipoStatus == "") {
			$idTipoStatus = null;
		}

		if ($idServico == "") {
			$idServico = null;
		}

		if ($statusDemanda == "") {
			$statusDemanda = null;
		}


		if ($buscaDemanda == "") {
			$buscaDemanda = null;
		}

		if ($idContratoTipo == ""){
			$idContratoTipo = null;
		}


		$idEmpresa = null;
		if (isset($_SESSION['idEmpresa'])) {
			$idEmpresa = $_SESSION['idEmpresa'];
		}

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idCliente' => $idCliente,
			'idSolicitante' => $idSolicitante,
			'idAtendente' => $idAtendente,
			'idTipoStatus' => $idTipoStatus,
			'idServico' => $idServico,
			'statusDemanda' => $statusDemanda,
			'buscaDemanda' => $buscaDemanda,
			'idContratoTipo' => $idContratoTipo,
			'card' => $card,
			'mes'=> $_POST["mes"],
			'ano'=> $_POST["ano"]
		);

		$demanda = chamaAPI(null, '/servicos/demandatabela_dashboard', json_encode($apiEntrada), 'GET');

		echo json_encode($demanda);
		return $demanda;
		
	}

	if ($operacao == "tempoatendimento") {

		$idContratoTipo = isset($_POST["idContratoTipo"]) && $_POST["idContratoTipo"] !== "" ? $_POST["idContratoTipo"] : null;
		$idCliente = isset($_POST["idCliente"]) && $_POST["idCliente"] !== "" ? $_POST["idCliente"] : null;
		
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idCliente'=> $idCliente,
			'idContratoTipo'=> $idContratoTipo,
			'mes'=> $_POST["mes"],
			'ano'=> $_POST["ano"]
		);

		$dashboard = chamaAPI(null, '/servicos/tempoatendimento', json_encode($apiEntrada), 'GET');

		echo json_encode($dashboard);
		return $dashboard;
	}
	if ($operacao == "ocorrencia_dashboard") {

		$idContratoTipo = isset($_POST["idContratoTipo"]) && $_POST["idContratoTipo"] !== "" ? $_POST["idContratoTipo"] : null;
		$idCliente = isset($_POST["idCliente"]) && $_POST["idCliente"] !== "" ? $_POST["idCliente"] : null;
		
		$apiEntrada = array(
			'idEmpresa' => $_SESSION['idEmpresa'],
			'idCliente'=> $idCliente,
			'idContratoTipo'=> $idContratoTipo,
			'mes'=> $_POST["mes"],
			'ano'=> $_POST["ano"]
		);

		$dashboard = chamaAPI(null, '/servicos/tempoocorrencia', json_encode($apiEntrada), 'GET');

		echo json_encode($dashboard);
		return $dashboard;
	}


	//Gabriel 22092023 id544 operação grava origem em session 
	if ($operacao == "origem") {
		$_SESSION['origem'] = $_POST['origem'];
	  }
}