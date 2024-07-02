<?php
// lucas id654 - Melhorias Tarefas
//gabriel 28022023 16:33 alterado para LEFT JOIN no usuario
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


//LOG 
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
  $LOG_NIVEL = defineNivelLog();
  $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "tarefas_select";
  if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 1) {
      $arquivo = fopen(defineCaminhoLog() . "services_" . date("dmY") . ".log", "a");
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
$tarefa = array();
$sql = "SELECT tarefa.*, usuario.nomeUsuario, cliente.nomeCliente, demanda.tituloDemanda,demanda.idContrato,contrato.tituloContrato,demanda.idContratoTipo, demanda.idTipoStatus,contratotipos.nomeContrato,
        contratotipos.nomeDemanda, tipoocorrencia.nomeTipoOcorrencia,
        TIMEDIFF(tarefa.horaFinalReal, tarefa.horaInicioReal) AS horasReal, 
        TIMEDIFF(CURRENT_TIME(), tarefa.horaInicioReal ) AS horasRealCorrente, 
        TIMEDIFF(tarefa.horaFinalPrevisto, tarefa.horaInicioPrevisto) AS horasPrevisto,
        DATEDIFF(Previsto,CURRENT_DATE() ) as DIAS, 
        timestampdiff(SECOND,CURRENT_TIME(),horaInicioPrevisto) as SEGUNDOS,
        'NAO' as Atrasado,
        DATEDIFF(dataReal,CURRENT_DATE() ) as DIASREAL
        FROM tarefa
        LEFT JOIN usuario ON tarefa.idAtendente = usuario.idUsuario 
        LEFT JOIN demanda ON tarefa.idDemanda = demanda.idDemanda 
        LEFT JOIN tipoocorrencia ON tarefa.idTipoOcorrencia = tipoocorrencia.idTipoOcorrencia
        LEFT JOIN cliente ON tarefa.idCliente = cliente.idCliente
        LEFT JOIN contratotipos on demanda.idContratoTipo = contratotipos.idContratoTipo
        LEFT JOIN contrato on demanda.idContrato = contrato.idContrato";
$where = " where ";
if (isset($jsonEntrada["idTarefa"])) {
  $sql = $sql . $where . " tarefa.idTarefa = " . $jsonEntrada["idTarefa"];
  $where = " and ";
}

if (isset($jsonEntrada["idDemanda"]) && $jsonEntrada["idDemanda"] !== "") {
  $sql = $sql . $where . " tarefa.idDemanda = " . $jsonEntrada["idDemanda"];
  $where = " and ";
}

if (isset($jsonEntrada["idCliente"])) {
  $sql = $sql . $where . " tarefa.idCliente = " . $jsonEntrada["idCliente"];
  $where = " and ";
}

if (isset($jsonEntrada["idTipoOcorrencia"])) {
  $sql = $sql . $where . " tarefa.idTipoOcorrencia = " . $jsonEntrada["idTipoOcorrencia"];
  $where = " and ";
}

if (isset($jsonEntrada["idAtendente"])) {
  $sql = $sql . $where . " tarefa.idAtendente = " . $jsonEntrada["idAtendente"];
  $where = " and ";
}

//Lucas 07112023 id965 - removido condições de filtro periodo

if (isset($jsonEntrada["statusTarefa"])) {
  if ($jsonEntrada["statusTarefa"] == 1) {
    $sql = $sql . $where . " tarefa.horaFinalReal IS NULL";
    $where = " and ";
  }
  if ($jsonEntrada["statusTarefa"] == 0) {
    $sql = $sql . $where . " tarefa.horaFinalReal IS NOT NULL";
    $where = " and ";
  }
}


if (isset($jsonEntrada["tituloTarefa"])) {
  //gabriel 16102023 ajustado buscar tarefa por titulo
  $sql = $sql . $where . " tarefa.idTarefa= " . "'" . $jsonEntrada["tituloTarefa"] . "'" 
                       . " or tarefa.tituloTarefa like " . "'%" . $jsonEntrada["tituloTarefa"] . "%'"
                       . " or (demanda.tituloDemanda is not null and demanda.tituloDemanda like " . "'%" . $jsonEntrada["tituloTarefa"] . "%')";
  $where = " and ";
}


//Lucas 07112023 id965 - removido condições de filtro periodo substituido por novo campo dataOrdem
if (isset($jsonEntrada["PeriodoInicio"])) {
  $sql .= $where . " tarefa.dataOrdem >= '" . $jsonEntrada["PeriodoInicio"] . "'";
  $where = " and ";
}
if (isset($jsonEntrada["PeriodoFim"])) {
  $sql .= $where . " tarefa.dataOrdem <= '" . $jsonEntrada["PeriodoFim"] . "'";
  $where = " and ";
}


// lucas id654 - Removido filtro RealOrdem e substituido filtro PrevistoOrdem por dataOrdem
$order = " ORDER BY ";
if (isset($jsonEntrada["dataOrdem"])) {
  if ($jsonEntrada["dataOrdem"] == 1) {
    $sql .= $order . " `tarefa`.`dataOrdem` DESC, `tarefa`.`horaInicioOrdem`  DESC ";
    $order = ",";
  }
  if ($jsonEntrada["dataOrdem"] == 0) {
    $sql .= $order . " `tarefa`.`dataOrdem` ASC, `tarefa`.`horaInicioOrdem` ASC ";
    $order = ",";
  }
}

$sql .= $order . " idTarefa DESC ";

//echo "-SQL->".json_encode($sql)."\n";
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
    
    if (!isset($row["horasPrevisto"])) {
      $row["horasPrevisto"] = "00:00:00";
    }
    if (!isset($row["horasReal"])) {
      $row["horasReal"] = "00:00:00";
    }
    if (!isset($row["horasRealCorrente"])) {
      $row["horasRealCorrente"]= "00:00:00";
    }
    if ($row["DIAS"] < 0) {
        $row["Atrasado"] = "SIM";
    }
    if ($row["DIAS"] == 0) {
        if ($row["SEGUNDOS"] < 0) {
          $row["Atrasado"] = "SIM";
        }
    } 
    if ($row["DIASREAL"] == 0) {
      if ($row["horasReal"] == "00:00:00") {
        $row["horasReal"] = $row["horasRealCorrente"] ;
      }
  } 
  
   unset($row["DIAS"]);
   unset($row["SEGUNDOS"]);
   unset($row["horasRealCorrente"] );
   unset($row["DIASREAL"]);

    array_push($tarefa, $row);
    $rows = $rows + 1;
  }
  if (isset($jsonEntrada["idTarefa"]) && $rows == 1) {
    $tarefa = $tarefa[0];
  }
  $jsonSaida = $tarefa;

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



?>