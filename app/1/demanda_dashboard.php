<?php
//Lucas 05062024 
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG 
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_dashboard";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "servicos_dashboard_" . date("dmY") . ".log", "a");
    }
  }
}

if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 3) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG

$mes = isset($jsonEntrada["mes"])  ? $jsonEntrada["mes"]  : date('m');
$ano = isset($jsonEntrada["ano"])  ? $jsonEntrada["ano"]  : date('Y');
$dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
$sqldti = $ano."-".$mes."-"."01";
$mesprox = $mes + 1;
$anoprox = $ano;
if ($mesprox == 13) {
  $mesprox = 1;
  $anoprox = $ano + 1;
}
$sqldtf = $anoprox."-".$mesprox."-"."01";

$idEmpresa = 1;
if (isset($jsonEntrada["idEmpresa"])) {
  $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$demanda = array();


// BACKLOG
$sql_backlog = "SELECT COUNT(demanda.idDemanda) AS Backlog FROM demanda ";

$where = " where ";
if (isset($jsonEntrada["idContratoTipo"])) {
  $sql_backlog = $sql_backlog . $where . " demanda.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
  $where = " and ";
}

if (isset($jsonEntrada["idCliente"])) {
  $sql_backlog = $sql_backlog . $where . " demanda.idCliente = " . $jsonEntrada["idCliente"];
  $where = " and ";
}

$sql_backlog = $sql_backlog . $where . " demanda.dataAbertura < '$sqldti' AND (demanda.dataFechamento IS NULL OR  demanda.dataFechamento >= '$sqldti');";

fwrite($arquivo, $identificacao . "-sql_backlog->" . $sql_backlog . "\n");

$buscar = mysqli_query($conexao, $sql_backlog);
$row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
$backlog = $row["Backlog"];


// ABERTAS NO MES
$sql_abertasnomes = "SELECT COUNT(demanda.idDemanda) AS AbertasNoMes FROM demanda ";

$where = " where ";
if (isset($jsonEntrada["idContratoTipo"])) {
  $sql_abertasnomes = $sql_abertasnomes . $where . " demanda.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
  $where = " and ";
}

if (isset($jsonEntrada["idCliente"])) {
  $sql_abertasnomes = $sql_abertasnomes . $where . " demanda.idCliente = " . $jsonEntrada["idCliente"];
  $where = " and ";
}

$sql_abertasnomes = $sql_abertasnomes . $where . " demanda.dataAbertura >= '$sqldti' AND demanda.dataAbertura < '$sqldtf';";
fwrite($arquivo, $identificacao . "-sql_abertasnomes->" . $sql_abertasnomes . "\n");

$buscar = mysqli_query($conexao, $sql_abertasnomes);
$row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
$abertasnomes = $row["AbertasNoMes"];


// FECHADAS DO MES
$sql_fechadasdomes = "SELECT COUNT(demanda.idDemanda) AS FechadasDoMes FROM demanda ";

$where = " where ";
if (isset($jsonEntrada["idContratoTipo"])) {
  $sql_fechadasdomes = $sql_fechadasdomes . $where . " demanda.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
  $where = " and ";
}

if (isset($jsonEntrada["idCliente"])) {
  $sql_fechadasdomes = $sql_fechadasdomes . $where . " demanda.idCliente = " . $jsonEntrada["idCliente"];
  $where = " and ";
}

$sql_fechadasdomes = $sql_fechadasdomes . $where . " demanda.dataAbertura >= '$sqldti' AND demanda.dataAbertura < '$sqldtf' AND demanda.dataFechamento >= '$sqldti' AND demanda.dataFechamento < '$sqldtf';";
fwrite($arquivo, $identificacao . "-sql_fechadasdomes->" . $sql_fechadasdomes . "\n");

$buscar = mysqli_query($conexao, $sql_fechadasdomes);
$row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
$fechadasdomes = $row["FechadasDoMes"];


// FECHADAS NO MES
$sql_fechadasnomes = "SELECT COUNT(demanda.idDemanda) AS FechadasNoMes FROM demanda ";

$where = " where ";
if (isset($jsonEntrada["idContratoTipo"])) {
  $sql_fechadasnomes = $sql_fechadasnomes . $where . " demanda.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
  $where = " and ";
}

if (isset($jsonEntrada["idCliente"])) {
  $sql_fechadasnomes = $sql_fechadasnomes . $where . " demanda.idCliente = " . $jsonEntrada["idCliente"];
  $where = " and ";
}

$sql_fechadasnomes = $sql_fechadasnomes . $where . "  demanda.dataFechamento >= '$sqldti' AND demanda.dataFechamento < '$sqldtf';";
fwrite($arquivo, $identificacao . "-sql_fechadasnomes->" . $sql_fechadasnomes . "\n");

$buscar = mysqli_query($conexao, $sql_fechadasnomes);
$row = mysqli_fetch_array($buscar, MYSQLI_ASSOC);
$fechadasnomes = $row["FechadasNoMes"];


//GRAFICO 
$totaisTabela = array();

$sql_grafico = " SELECT demanda.idTipoStatus, tipostatus.nomeTipoStatus, count(*) as Total 
FROM demanda, tipostatus 
where demanda.idTipoStatus = tipostatus.idTipoStatus and 
    (tipostatus.mudaStatusPara > 0 or (tipostatus.mudaStatusPara = 0 and demanda.dataFechamento >= '$sqldti' AND demanda.dataFechamento < '$sqldtf')) ";
$where = " and ";
if (isset($jsonEntrada["idContratoTipo"])) {
  $sql_grafico = $sql_grafico . $where . " demanda.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
  $where = " and ";
}

if (isset($jsonEntrada["idCliente"])) {
  $sql_grafico = $sql_grafico . $where . " demanda.idCliente = " . $jsonEntrada["idCliente"];
  $where = " and ";
}

$sql_grafico = $sql_grafico . $where . "  ((demanda.dataFechamento >= '$sqldti' AND demanda.dataFechamento < '$sqldtf') or " .
    " demanda.dataAbertura < '$sqldtf' AND (demanda.dataFechamento IS NULL OR  demanda.dataFechamento >= '$sqldtf')) ";

$sql_grafico = $sql_grafico . " group by demanda.idTipoStatus, tipostatus.nomeTipoStatus
order by Total" ;

$total = 0;
$buscar = mysqli_query($conexao, $sql_grafico);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($totaisTabela, $row);
  $total = $total + $row["Total"];
}
/*
array_push($totaisTabela, array("idTipoStatus" => "0",
                                "nomeTipoStatus" => "Total",
                                "Total" => $total ));
*/


//TRY-CATCH
try {

  $totais =
      array(
        'backlog' =>  0,
        'abertasnomes' =>  0,
        'fechadasdomes' =>  0,
        'saldodomes' => 0,
        'fechadasnomes' =>  0,
        'saldo' => 0,
        'totaisTabela' => 0
      );

  $totais["backlog"] = $backlog;
  $totais["abertasnomes"] = $abertasnomes;
  $totais["fechadasdomes"] = $fechadasdomes;
  $totais["saldodomes"] = "". $abertasnomes -$fechadasdomes ."";
  $totais["fechadasnomes"] = $fechadasnomes;
  $totais["saldo"] = "". $backlog + $abertasnomes - $fechadasnomes ."";
  $totais["totaisTabela"] = $totaisTabela;

  //echo json_encode($totais)."\n";

  $jsonSaida = $totais;
} catch (Exception $e) {
  $jsonSaida = array(
    "status" => 500,
    "retorno" => $e->getMessage()
  );
  if ($LOG_NIVEL >= 1) {
    fwrite($arquivo, $identificacao . "-ERRO->" . $e->getMessage() . "\n");
  }
} finally {
  // ACAO EM CASO DE ERRO (CATCH), que mesmo assim precise
}
//TRY-CATCH



//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 3) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG