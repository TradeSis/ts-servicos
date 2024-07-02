<?php

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}


include_once __DIR__ . "/../conexao.php";


function buscaOrcamentoStatus($idOrcamentoStatus=null)
{
	
	$orcamentoStatus = array();
	
	$idEmpresa = null;
	if (isset($_SESSION['idEmpresa'])) {
    	$idEmpresa = $_SESSION['idEmpresa'];
	}

	$apiEntrada = array(
		'idEmpresa' => $idEmpresa,
		'idOrcamentoStatus' => $idOrcamentoStatus
	);
	$orcamentoStatus = chamaAPI(null, '/services/orcamentostatus', json_encode($apiEntrada), 'GET');

	//echo json_encode ($orcamentoStatus);
	return $orcamentoStatus;
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
			'nomeOrcamentoStatus' => $_POST['nomeOrcamentoStatus']
		);
		
		$orcamentoStatus = chamaAPI(null, '/services/orcamentostatus', json_encode($apiEntrada), 'PUT');
	}

	if ($operacao=="alterar") {

		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idOrcamentoStatus' => $_POST['idOrcamentoStatus'],
			'nomeOrcamentoStatus' => $_POST['nomeOrcamentoStatus']
		);
		$orcamentoStatus = chamaAPI(null, '/services/orcamentostatus', json_encode($apiEntrada), 'POST');

	}
	if ($operacao=="excluir") {
		$apiEntrada = array(
			'idEmpresa' => $idEmpresa,
			'idOrcamentoStatus' => $_POST['idOrcamentoStatus']
		);
		$orcamentoStatus = chamaAPI(null, '/services/orcamentostatus', json_encode($apiEntrada), 'DELETE');

	}

	if ($operacao == "buscar") {
        $idOrcamentoStatus = $_POST["idOrcamentoStatus"];
        if ($idOrcamentoStatus == "") {
            $idOrcamentoStatus = null;
        }
        $apiEntrada = array(
            'idEmpresa' => $idEmpresa,
            'idOrcamentoStatus' => $idOrcamentoStatus
        );
        $orcamentoStatus = chamaAPI(null, '/services/orcamentostatus', json_encode($apiEntrada), 'GET');

        echo json_encode($orcamentoStatus);
        return $orcamentoStatus;
    }

	header('Location: ../configuracao?stab=orcamentoStatus');
}



?>