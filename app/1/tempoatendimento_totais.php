<?php
//Gabriel 20250205

//LOG 
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "tarefa_dashboard";
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

//GRAFICO 
$totaisTabela = array();

$sql = " SELECT tarefa.idTipoOcorrencia, tipoocorrencia.nomeTipoOcorrencia, count(*) as Total 
         FROM tarefa
         INNER JOIN demanda ON tarefa.idDemanda = demanda.idDemanda
         INNER JOIN contrato ON demanda.idContrato = contrato.idContrato
         INNER JOIN contratotipos ON demanda.idContratoTipo = contratotipos.idContratoTipo
         INNER JOIN tipoocorrencia ON tarefa.idTipoOcorrencia = tipoocorrencia.idTipoOcorrencia
         INNER JOIN usuario ON tarefa.idAtendente = usuario.idUsuario
         WHERE   tarefa.dataReal >= '" . $sqldti . "'
                 AND tarefa.dataReal < '" . $sqldtf . "'
                 AND tarefa.horaFinalReal IS NOT NULL";
$where = " AND ";
if (isset($jsonEntrada["idContratoTipo"])) {
    $sql .= $where . " demanda.idContratoTipo = '" . $jsonEntrada["idContratoTipo"] . "'";
    $where = " AND ";
} 
if (isset($jsonEntrada["idTipoOcorrencia"])) {
    $sql .= $where . " tarefa.idTipoOcorrencia = '" . $jsonEntrada["idTipoOcorrencia"] . "'";
    $where = " AND ";
} 
if (isset($jsonEntrada["idAtendente"])) {
    $sql .= $where . " tarefa.idAtendente = '" . $jsonEntrada["idAtendente"] . "'";
    $where = " AND ";
} 
if (isset($jsonEntrada["idCliente"])) {
    $sql .= $where . " tarefa.idCliente = " . $jsonEntrada["idCliente"];
}

$sql = $sql . " group by tarefa.idTipoOcorrencia, tipoocorrencia.nomeTipoOcorrencia
order by Total" ;

$total = 0;
$buscar = mysqli_query($conexao, $sql);
while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
  array_push($totaisTabela, $row);
  $total = $total + $row["Total"];
}

//TRY-CATCH
try {
  $jsonSaida = $totaisTabela;
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