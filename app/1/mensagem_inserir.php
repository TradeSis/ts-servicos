<?php
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
if (isset($jsonEntrada['idDemanda'])) {
    $idDemanda = $jsonEntrada['idDemanda'];
    $mensagem = $jsonEntrada['mensagem'];
    $idUsuario = $jsonEntrada['idUsuario'];
    $idCliente = $jsonEntrada['idCliente'];
    $nomeAnexo = isset($jsonEntrada['nomeAnexo']) && $jsonEntrada['nomeAnexo'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['nomeAnexo']) . "'" : "NULL";
    $pathAnexo = isset($jsonEntrada['pathAnexo']) && $jsonEntrada['pathAnexo'] !== "" ? "'" . mysqli_real_escape_string($conexao, $jsonEntrada['pathAnexo']) . "'" : "NULL";
    $idAtendente = $jsonEntrada['idAtendente'];


    $sql = "INSERT INTO mensagem(idDemanda, mensagem, idUsuario, dataMensagem,nomeAnexo,pathAnexo) VALUES ($idDemanda,'$mensagem',$idUsuario,CURRENT_TIMESTAMP(),$nomeAnexo,$pathAnexo)";
    $atualizar = mysqli_query($conexao, $sql);


    if ($atualizar) {
        $jsonSaida = array(
            "status" => 200,
            "retorno" => "ok"
        );
    } else {
        $jsonSaida = array(
            "status" => 500,
            "retorno" => "erro no mysql"
        );
    }
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parâmetros"
    );
}