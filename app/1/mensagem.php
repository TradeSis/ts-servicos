<?php
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$mensagem = array();
$sql = "SELECT mensagem.*, usuario.nomeUsuario, demanda.idDemanda, CASE WHEN mensagem.idUsuario = " . $jsonEntrada["idUsuario"] . " THEN 1 ELSE 0 END AS status FROM mensagem  
        INNER JOIN usuario on mensagem.idUsuario = usuario.idUsuario 
        INNER JOIN demanda on mensagem.idDemanda = demanda.idDemanda ";

//echo "-SQL->".json_encode($sql)."\n";

if (isset($jsonEntrada["idMensagem"])) {
    $sql = $sql . " where mensagem.idMensagem = " . $jsonEntrada["idMensagem"];
} else {
    if (isset($jsonEntrada["idDemanda"])) {
        $sql = $sql . " where mensagem.idDemanda = " . $jsonEntrada["idDemanda"];
    }
}
$sql = $sql . " ORDER BY mensagem.idMensagem ASC";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($mensagem, $row);
    $rows = $rows + 1;
}
if (isset($jsonEntrada["idMensagem"]) && $rows == 1) {
    $mensagem = $mensagem[0];
}
$jsonSaida = $mensagem;