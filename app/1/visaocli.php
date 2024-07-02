<?php
//Lucas 20122023 - inciado
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";
//LOG
$LOG_CAMINHO=defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL=defineNivelLog();
    $identificacao=date("dmYHis")."-PID".getmypid()."-"."visaocli";
    if(isset($LOG_NIVEL)) {
        if ($LOG_NIVEL>=1) {
            $arquivo = fopen(defineCaminhoLog()."services_".date("dmY").".log","a");
        }
    }
    
}
if(isset($LOG_NIVEL)) {
    if ($LOG_NIVEL==1) {
        fwrite($arquivo,$identificacao."\n");
    }
    if ($LOG_NIVEL>=2) {
        fwrite($arquivo,$identificacao."-ENTRADA->".json_encode($jsonEntrada)."\n");
    }
}
//LOG

$idEmpresa = null;
	if (isset($jsonEntrada["idEmpresa"])) {
    	$idEmpresa = $jsonEntrada["idEmpresa"];
	}

$conexao = conectaMysql($idEmpresa);
$demanda = array();

$idTipoStatus = $jsonEntrada['idTipoStatus'];
$idUsuario = $jsonEntrada['idUsuario'];

$sql_consulta = "SELECT * FROM usuario WHERE idUsuario = " . $idUsuario ." ";
$buscar_consulta = mysqli_query($conexao, $sql_consulta);
$row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
$idCliente = $row_consulta['idCliente'];

//echo "-ID CLIENTE->".json_encode($idCliente)."\n";

$sql = "SELECT * from demanda WHERE ";
    if($idCliente != null){
        $sql = $sql . " idCliente= ". $idCliente. " AND ";
    }
$sql = $sql . " idTipoStatus= ". $idTipoStatus. " ";

//echo "-SQL->".json_encode($sql)."\n";
  //LOG
  if(isset($LOG_NIVEL)) {
    if ($LOG_NIVEL>=3) {
        fwrite($arquivo,$identificacao."-SQL->".$sql."\n");
    }
}
//LOG

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($demanda, $row);
  $rows = $rows + 1;
}
//echo "-ARRAY->".json_encode($demanda)."\n";

if (isset($jsonEntrada["idDemanda"]) && $rows==1) {
  $demanda = $demanda[0];
}

$jsonSaida = $demanda;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if(isset($LOG_NIVEL)) {
  if ($LOG_NIVEL>=2) {
      fwrite($arquivo,$identificacao."-SAIDA->".json_encode($jsonSaida)."\n\n");
  }
}
//LOG

?>