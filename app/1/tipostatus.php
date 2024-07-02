<?php
//gabriel 06022023 16:52
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";
/*
{
		"mudaStatusPara" : 1,
		"idTipoStatus" : null
}
*/

//LOG
$LOG_CAMINHO=defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL=defineNivelLog();
    $identificacao=date("dmYHis")."-PID".getmypid()."-"."tipostatus";
    if(isset($LOG_NIVEL)) {
        if ($LOG_NIVEL>=1) {
            $arquivo = fopen(defineCaminhoLog()."servicos_".date("dmY").".log","a");
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
$tipostatus = array();



$sql = "SELECT * FROM tipostatus ";
if (isset($jsonEntrada["idTipoStatus"])) {
  $sql = $sql . " where tipostatus.idTipoStatus = " . $jsonEntrada["idTipoStatus"];
} else {
  $where = " where ";
  if (isset($jsonEntrada["statusInicial"])) {
    $sql = $sql . $where . " tipostatus.statusInicial = " . $jsonEntrada["statusInicial"];
  }
}

  //LOG
  if(isset($LOG_NIVEL)) {
    if ($LOG_NIVEL>=3) {
        fwrite($arquivo,$identificacao."-SQL->".$sql."\n");
    }
}
//LOG
//echo "-SQL->".json_encode($sql)."\n";
$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($tipostatus, $row);
  $rows = $rows + 1;
}
//echo "-ARRAY->".json_encode($tipostatus)."\n";

if (isset($jsonEntrada["idTipoStatus"]) && $rows==1) {
  $tipostatus = $tipostatus[0];
}

$jsonSaida = $tipostatus;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if(isset($LOG_NIVEL)) {
  if ($LOG_NIVEL>=2) {
      fwrite($arquivo,$identificacao."-SAIDA->".json_encode($jsonSaida)."\n\n");
  }
}
//LOG

?>