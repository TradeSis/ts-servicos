<?php
//Gabriel 26092023 ID 575 Demandas/Comentarios - Layout de chat
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";



$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$chat = array();
$sql = "SELECT chat.*, INidUsuario.nomeUsuario AS nomeINusuario, OUTidUsuario.nomeUsuario AS nomeOUTusuario, CASE WHEN chat.INidUsuario = " . $jsonEntrada["INidUsuario"] . " THEN 1 ELSE 0 END AS status FROM chat  
        INNER JOIN usuario AS INidUsuario on chat.INidUsuario = INidUsuario.idUsuario 
        LEFT JOIN usuario AS OUTidUsuario on chat.OUTidUsuario = OUTidUsuario.idUsuario";
$where = " where ";
if (isset($jsonEntrada["INidUsuario"]) && isset($jsonEntrada["OUTidUsuario"])) {
   $sql = $sql . $where . "(chat.INidUsuario = " . $jsonEntrada["INidUsuario"] . " AND chat.OUTidUsuario = " . $jsonEntrada["OUTidUsuario"] . ")
   OR (chat.INidUsuario = " . $jsonEntrada["OUTidUsuario"] . " AND chat.OUTidUsuario = " . $jsonEntrada["INidUsuario"] . ")";
   $where = " and ";
}
$sql = $sql . " ORDER BY chat.chatID ASC";
//echo "-SQL->".json_encode($sql)."\n";

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($chat, $row);
    $rows = $rows + 1;
}
if (isset($jsonEntrada["chatID"]) && $rows == 1) {
    $chat = $chat[0];
}
$jsonSaida = $chat;