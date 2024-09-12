<?php
// Lucas 07022023 criacao

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demandachecklist";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "servicos_" . date("dmY") . ".log", "a");
    }
  }
}
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG

$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
  $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$demandachecklist = array();


$sql = "SELECT * FROM demandachecklist ";

$where = " where ";
if (isset($jsonEntrada["idChecklist"])) {
  $sql = $sql . $where . " demandachecklist.idChecklist = " . $jsonEntrada["idChecklist"];
  $where = " and ";
}

if (isset($jsonEntrada["idDemanda"])) {
  $sql = $sql . $where . " demandachecklist.idDemanda = " . $jsonEntrada["idDemanda"];
  $where = " and ";
}
$sql = $sql . " ORDER BY ordem";

//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 3) {
    fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
  }
}
//LOG

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($demandachecklist, $row);
  $rows = $rows + 1;
}

if (isset($jsonEntrada["idChecklist"]) && isset($jsonEntrada["idDemanda"]) && $rows == 1) {
  $demandachecklist = $demandachecklist[0];
}
$jsonSaida = $demandachecklist;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 2) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG
