<?php
//Lucas 28112023 id706 Melhorias Demandas 2
//lucas 26092023 ID 576 Demanda/BOTÕES de SITUACOES 
// Lucas 19052023 adicionado if para filtro de tamanho
// Lucas 22032023 adicionado if para filtro de tituloDemanda
// Lucas 21032023 ajustado estrutura dentro do else, para os novos filtros.
// Lucas 17022023 adicionado condição else para idTipoStatus
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG 
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_select";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "services_select_" . date("dmY") . ".log", "a");
    }
  }

}
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL == 1) {
    fwrite($arquivo, $identificacao . "\n");
  }
  if ($LOG_NIVEL >= 4) {
    fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
  }
}
//LOG


$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
  $idEmpresa = $jsonEntrada["idEmpresa"];
}

$conexao = conectaMysql($idEmpresa);
$demanda = array();


$sql = "SELECT demanda.*, contratotipos.*, cliente.nomeCliente, tipostatus.nomeTipoStatus, contrato.tituloContrato, servicos.nomeServico, atendente.nomeUsuario AS nomeAtendente, solicitante.nomeUsuario AS nomeSolicitante 
, '' AS dataPrevisaoInicioFormatada, '' AS dataPrevisaoEntregaFormatada, '' AS dataAberturaFormatada,'' AS horaAberturaFormatada, '' AS 	dataFechamentoFormatada, '' AS horaFechamentoFormatada
, '' AS atrasada FROM demanda
        LEFT JOIN cliente ON demanda.idCliente = cliente.idCliente
        LEFT JOIN usuario AS atendente ON demanda.idAtendente = atendente.idUsuario
        LEFT JOIN usuario AS solicitante ON demanda.idSolicitante = solicitante.idUsuario
        LEFT JOIN contrato ON demanda.idContrato = contrato.idContrato
        LEFT JOIN servicos ON demanda.idServico = servicos.idServico
        LEFT JOIN tipostatus ON demanda.idTipoStatus = tipostatus.idTipoStatus
        LEFT JOIN contratotipos  on  demanda.idContratoTipo = contratotipos.idContratoTipo ";
$where = " where ";
if (isset($jsonEntrada["idDemanda"]) && $jsonEntrada["idDemanda"] !== "") {
  $sql = $sql . $where . " demanda.idDemanda = " . $jsonEntrada["idDemanda"];
  $where = " and ";
}

if (isset($jsonEntrada["idCliente"])) {
  $sql = $sql . $where . " demanda.idCliente = " . $jsonEntrada["idCliente"];
  $where = " and ";
}

if (isset($jsonEntrada["idSolicitante"])) {
  $sql = $sql . $where . " demanda.idSolicitante = " . $jsonEntrada["idSolicitante"];
  $where = " and ";
}

if (isset($jsonEntrada["idTipoStatus"])) {
  $sql = $sql . $where . " demanda.idTipoStatus = " . $jsonEntrada["idTipoStatus"];
  $where = " and ";
}

//Lucas 28112023 id706 - removido idTipoOcorrencia e adicionado idServico
if (isset($jsonEntrada["idServico"])) {
  $sql = $sql . $where . " demanda.idServico = " . $jsonEntrada["idServico"];
  $where = " and ";
}

if (isset($jsonEntrada["idAtendente"])) {
  $sql = $sql . $where . " demanda.idAtendente = " . $jsonEntrada["idAtendente"];
  $where = " and ";
}

if (isset($jsonEntrada["statusDemanda"])) {
  $sql = $sql . $where . " demanda.statusDemanda = " . $jsonEntrada["statusDemanda"];
  $where = " and ";
}

if (isset($jsonEntrada["buscaDemanda"])) {
  $sql = $sql . $where . " demanda.idDemanda= " . "'" . $jsonEntrada["buscaDemanda"] . "'" . " or demanda.tituloDemanda like " . "'%" . $jsonEntrada["buscaDemanda"] . "%'";
  $where = " and ";
}

if (isset($jsonEntrada["idContrato"])) {
  $sql = $sql . $where . " demanda.idContrato = " . "'" . $jsonEntrada["idContrato"] . "'";
  $where = " and ";
}

if (isset($jsonEntrada["idContratoTipo"])) {
  $sql = $sql . $where . " contratotipos.idContratoTipo = " . "'" . $jsonEntrada["idContratoTipo"] . "'";
  $where = " and ";
}

if(isset($jsonEntrada['idUsuario'])){
  $idUsuario = $jsonEntrada['idUsuario'];
  if ($idUsuario != null) { 
    $sql_consulta = "SELECT * FROM usuario WHERE idUsuario = " . $idUsuario ." ";
    $buscar_consulta = mysqli_query($conexao, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
    $idCliente = $row_consulta['idCliente'];
    if($idCliente != null){
      $sql = $sql . $where . " demanda.idCliente = ". $idCliente . " ";
    }
  }
}

$order = " order by ordem, prioridade, idDemanda";

  if(isset($jsonEntrada['idTipoStatus'])||isset($jsonEntrada['statusDemanda'])) {
    if(isset($jsonEntrada['idTipoStatus'])) {
        if ($jsonEntrada['idTipoStatus'] == TIPOSTATUS_REALIZADO) {
          $order = " order by ordem, dataFechamento Desc, idDemanda";
        }
    }
   if(isset($jsonEntrada['statusDemanda'])) {
       if ($jsonEntrada['statusDemanda'] == 3) {
          $order = " order by ordem, dataFechamento Desc, idDemanda";
        }
      }
  }

$sql = $sql . $order;



//echo "-SQL->" . json_encode($sql) . "\n";
$rows = 0;

//LOG
if (isset($LOG_NIVEL)) {
  if ($LOG_NIVEL >= 5) {
    fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
  }
}
//LOG


//TRY-CATCH
try {

  $buscar = mysqli_query($conexao, $sql);
  if (!$buscar)
    throw new Exception(mysqli_error($conexao));
  
  while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    array_push($demanda, $row);
    $today = date("Y-m-d");

    $dataAberturaFormatada = null;
    $horaAberturaFormatada = null;
    if(isset($demanda[$rows]["dataAbertura"])){
      $dataAberturaFormatada = date('d/m/Y', strtotime($demanda[$rows]["dataAbertura"]));
      $horaAberturaFormatada = date('H:i', strtotime($demanda[$rows]["dataAbertura"]));
    }

    $dataFechamentoFormatada = null;
    $horaFechamentoFormatada = null;
    if(isset($demanda[$rows]["dataFechamento"])){
      $dataFechamentoFormatada = date('d/m/Y', strtotime($demanda[$rows]["dataFechamento"]));
      $horaFechamentoFormatada = date('H:i', strtotime($demanda[$rows]["dataFechamento"]));
    }

    $dataPrevisaoInicioFormatada = null;
    if(isset($demanda[$rows]["dataPrevisaoInicio"])){
      $dataPrevisaoInicioFormatada = date('d/m/Y', strtotime($demanda[$rows]["dataPrevisaoInicio"]));
    }

    $dataPrevisaoEntregaFormatada = null;
    if(isset($demanda[$rows]["dataPrevisaoEntrega"])){
      $dataPrevisaoEntregaFormatada = date('d/m/Y', strtotime($demanda[$rows]["dataPrevisaoEntrega"]));
    }
    
    $dataPrevisaoEntregaComparacao = date("Y-m-d",strtotime($demanda[$rows]["dataPrevisaoEntrega"])); 
    
    if($dataPrevisaoEntregaComparacao < $today){
      $atrasada = true;
    }else{
      $atrasada = false;
    }

    $demanda[$rows]["dataAberturaFormatada"] = $dataAberturaFormatada;
    $demanda[$rows]["horaAberturaFormatada"] = $horaAberturaFormatada;
    $demanda[$rows]["dataFechamentoFormatada"] = $dataFechamentoFormatada;
    $demanda[$rows]["horaFechamentoFormatada"] = $horaFechamentoFormatada;
    $demanda[$rows]["dataPrevisaoInicioFormatada"] = $dataPrevisaoInicioFormatada;
    $demanda[$rows]["dataPrevisaoEntregaFormatada"] = $dataPrevisaoEntregaFormatada;
    $demanda[$rows]["atrasada"] = $atrasada;

    $rows = $rows + 1;
  }
  if (isset($jsonEntrada["idDemanda"]) && $rows == 1) {
    $demanda = $demanda[0];
  }
  $jsonSaida = $demanda;

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
  if ($LOG_NIVEL >= 4) {
    fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
  }
}
//LOG