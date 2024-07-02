<?php
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}
$conexao = conectaMysql($idEmpresa);
if (isset($jsonEntrada['INidUsuario'])) {
    $INidUsuario = $jsonEntrada['INidUsuario'];
    $OUTidUsuario = isset($jsonEntrada['OUTidUsuario']) && $jsonEntrada['OUTidUsuario'] !== "" ? mysqli_real_escape_string($conexao, $jsonEntrada['OUTidUsuario']) : "NULL";
    $chat = $jsonEntrada['chat'];

    $sql = "INSERT INTO chat(INidUsuario,OUTidUsuario,chat,dataMensagem) VALUES ($INidUsuario,$OUTidUsuario,'$chat',CURRENT_TIMESTAMP())";
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