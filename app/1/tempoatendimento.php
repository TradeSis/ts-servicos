<?php
// Gabriel 26022024 criacao

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "tempoatendimento";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "tempoatendimento_" . date("dmY") . ".log", "a");
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

$demandas = array();

$mes = isset($jsonEntrada["mes"])  ? $jsonEntrada["mes"]  : date('m');
$ano = isset($jsonEntrada["ano"])  ? $jsonEntrada["ano"]  : date('Y');
$dia = date("t", mktime(0,0,0,$mes,'01',$ano)); 
$sqldti = $ano."-".$mes."-"."01";
$mesprox = $mes + 1;
$anoprox = $ano;
if ($mesprox == 13) {
  $mesprox = 1;
  $anoprox = $ano + 1;
}
$sqldtf = $anoprox."-".$mesprox."-"."01";


$sql = "SELECT contratotipos.nomeContrato, contrato.tituloContrato,demanda.idDemanda, 
        demanda.tituloDemanda, demanda.dataFechamento, tipoocorrencia.nomeTipoOcorrencia, 
        usuario.nomeUsuario AS nomeAtendente, tarefa.dataReal, tarefa.horaInicioReal, 
        tarefa.horaFinalReal,TIMEDIFF(tarefa.horaFinalReal, tarefa.horaInicioReal) AS tempo FROM tarefa
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
    $where = " AND ";
}

$sql .= " GROUP BY demanda.idContratoTipo, demanda.idDemanda, tarefa.dataReal, tarefa.horaInicioReal, tarefa.horaFinalReal
          ORDER BY contratotipos.nomeContrato, demanda.idDemanda, tarefa.dataReal, tarefa.horaInicioReal";


//echo "-SQL->".$sql."\n"; 
//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 3) {
        fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
    }
}
//LOG

$rows = 0;
$buscar = mysqli_query($conexao, $sql);
$demandaArray = array();

while ($row = mysqli_fetch_array($buscar, MYSQLI_ASSOC)) {
    $idDemanda = $row['idDemanda'];
    if (!isset($demandaArray[$idDemanda])) {
        $demandaArray[$idDemanda] = array();
    }
    $row['tempo'] = strtotime($row['tempo']) - strtotime('TODAY');
    $demandaArray[$idDemanda][] = $row;
    $rows++;
}

$demandas = array();
$totalTempo = 0;
$totalCobrado = 0;

foreach ($demandaArray as $idDemanda => &$demandasPorId) {
    $cobrado = 0;
    $count = count($demandasPorId);

    for ($i = 0; $i < $count; $i++) {
        $demanda = &$demandasPorId[$i];
        $demanda['tempo'] = gmdate('H:i:s', $demanda['tempo']);
        
        $tempo = strtotime($demanda['tempo']) - strtotime('TODAY');
        $cobrado += $tempo;

        $totalTempo += $tempo;

        if ($i < $count - 1) {  
            $demanda['tempoCobrado'] = gmdate('H:i:s', $cobrado);
            $totalCobrado += $tempo;
        } else {  
            if ($cobrado < 1800) { 
                $tempoRestante = 1800 - $cobrado; 
                $demanda['tempoCobrado'] = gmdate('H:i:s', $tempo + $tempoRestante); 
            } else {
                $demanda['tempoCobrado'] = gmdate('H:i:s', $tempo); 
            }
            $totalCobrado += strtotime($demanda['tempoCobrado']) - strtotime('TODAY');
        }
    }
    unset($demanda); 
}

$demandas = array();
foreach ($demandaArray as $demandasPorId) {
    $demandas = array_merge($demandas, $demandasPorId);
}

$jsonSaida = [
    "demandas" => $demandas,
    "total" => [
        [
            "totalTempo" => gmdate('H:i:s', $totalTempo),
            "totalCobrado" => gmdate('H:i:s', $totalCobrado)
        ]
    ]
];

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG