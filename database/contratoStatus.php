<?php
// helio 21032023 - compatibilidade chamada chamaApi
// helio 01022023 altereado para include_once, usando funcao conectaMysql

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


include_once __DIR__ . "/../conexao.php";


function buscaContratoStatus($idContratoStatus=null)
{
	
	$contratoStatus = array();
	
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idContratoStatus' => $idContratoStatus,
	);
	$contratoStatus = chamaAPI(null, '/services/contratostatus', json_encode($apiEntrada), 'GET');

	//echo json_encode ($contratoStatus);
	return $contratoStatus;
}


if (isset($_GET['operacao'])) {

	$operacao = $_GET['operacao'];
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	if ($operacao=="inserir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'nomeContratoStatus' => $_POST['nomeContratoStatus'],
			'mudaStatusPara' => $_POST['mudaStatusPara']
		);
		
		$contratoStatus = chamaAPI(null, '/services/contratostatus', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idContratoStatus' => $_POST['idContratoStatus'],
			'nomeContratoStatus' => $_POST['nomeContratoStatus'],
			'mudaStatusPara' => $_POST['mudaStatusPara']
		);
		$contratoStatus = chamaAPI(null, '/services/contratostatus', json_encode($apiEntrada), 'POST');

	}
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idContratoStatus' => $_POST['idContratoStatus']
		);
		$contratoStatus = chamaAPI(null, '/services/contratostatus', json_encode($apiEntrada), 'DELETE');

	}

	header('Location: ../configuracao?stab=contratoStatus');
}



?>