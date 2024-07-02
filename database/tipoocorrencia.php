<?php
// helio 21032023 - compatibilidade chamada chamaApi
// helio 01022023 altereado para include_once, usando funcao conectaMysql
// helio 26012023 16:16

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


include_once __DIR__ . "/../conexao.php";


function buscaTipoOcorrencia($ocorrenciaInicial=null,$idTipoOcorrencia=null)
{
	
	$tipoocorrencia = array();

	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}
	
	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'ocorrenciaInicial' => $ocorrenciaInicial,
		'idTipoOcorrencia' => $idTipoOcorrencia,
	
	);
	$tipoocorrencia = chamaAPI(null, '/services/tipoocorrencia', json_encode($apiEntrada), 'GET');
	return $tipoocorrencia;
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
			'nomeTipoOcorrencia' => $_POST['nomeTipoOcorrencia']
		);
		$tipoocorrencia = chamaAPI(null, '/services/tipoocorrencia', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idTipoOcorrencia' => $_POST['idTipoOcorrencia'],
			'ocorrenciaInicial' => $_POST['ocorrenciaInicial'],
			'nomeTipoOcorrencia' => $_POST['nomeTipoOcorrencia']
		);
		$tipoocorrencia = chamaAPI(null, '/services/tipoocorrencia', json_encode($apiEntrada), 'POST');
	}
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idTipoOcorrencia' => $_POST['idTipoOcorrencia']
		);
		$tipoocorrencia = chamaAPI(null, '/services/tipoocorrencia', json_encode($apiEntrada), 'DELETE');
	}
	if ($operacao == "buscar") {


		$apiEntrada = array(
			'idTipoOcorrencia' => $_POST['idTipoOcorrencia']
		);
		$ocorrencia = chamaAPI(null, '/services/tipoocorrencia', json_encode($apiEntrada), 'GET');

		echo json_encode($ocorrencia);
		return $ocorrencia;
	}

/*
	include "../configuracao/tipoocorrencia_ok.php";
*/
	header('Location: ../configuracao?stab=tipoocorrencia');
	
}

?>