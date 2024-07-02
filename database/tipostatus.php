<?php
// helio 21032023 - compatibilidade chamada chamaApi
// helio 01022023 altereado para include_once, usando funcao conectaMysql
// helio 26012023 16:16

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


include_once __DIR__ . "/../conexao.php";

function buscaTipoStatus($statusInicial=null, $idTipoStatus=null)
{
	
	$tipostatus = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'statusInicial' => $statusInicial,
		'idTipoStatus' => $idTipoStatus,
		
	);
	$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'GET');
	return $tipostatus;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	if ($operacao=="GET_JSON") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idTipoStatus' => $_GET['idTipoStatus']
		);
		
		
		$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'GET');
		echo json_encode($tipostatus);
		return $tipostatus;
	}
	if ($operacao=="JSON_alterar") {
		//echo json_encode($_POST);
	
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idTipoStatus' => $_POST['idTipoStatus'],
			'nomeTipoStatus' => $_POST['nomeTipoStatus'],
			'mudaPosicaoPara' => $_POST['mudaPosicaoPara'],
			'mudaStatusPara' => $_POST['mudaStatusPara']
		);
		$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'POST');
		echo json_encode($tipostatus);
		
		return;
		
	}

	if ($operacao=="GET_JSON") {
		$apiEntrada = array(
			'idTipoStatus' => $_GET['idTipoStatus']
		);
		
		
		$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'GET');
		echo json_encode($tipostatus);
		return $tipostatus;
	}
	if ($operacao=="JSON_alterar") {
		//echo json_encode($_POST);
	
		$apiEntrada = array(
			'idTipoStatus' => $_POST['idTipoStatus'],
			'nomeTipoStatus' => $_POST['nomeTipoStatus'],
			'mudaPosicaoPara' => $_POST['mudaPosicaoPara'],
			'mudaStatusPara' => $_POST['mudaStatusPara']
		);
		$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'POST');
		echo json_encode($tipostatus);
		
		return;
		
	}

	if ($operacao=="inserir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'nomeTipoStatus' => $_POST['nomeTipoStatus'],
			'mudaPosicaoPara' => $_POST['mudaPosicaoPara'],
			'mudaStatusPara' => $_POST['mudaStatusPara']
		);
		$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idTipoStatus' => $_POST['idTipoStatus'],
			'nomeTipoStatus' => $_POST['nomeTipoStatus'],
			'mudaPosicaoPara' => $_POST['mudaPosicaoPara'],
			'mudaStatusPara' => $_POST['mudaStatusPara']
		);
		$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'POST');

	}
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idTipoStatus' => $_POST['idTipoStatus']
		);
		$tipostatus = chamaAPI(null, '/servicos/tipostatus', json_encode($apiEntrada), 'DELETE');
	}

/*
	include "../configuracao/tipostatus_ok.php";
*/
	//header('Location: ../configuracao/tipostatus.php');	
	header('Location: ../configuracao?stab=tipostatus');	
	
	
}
