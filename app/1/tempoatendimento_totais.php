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


$sql = "SELECT demanda.idDemanda, tipoocorrencia.nomeTipoOcorrencia, tarefa.idTipoOcorrencia, 
        TIMEDIFF(tarefa.horaFinalReal, tarefa.horaInicioReal) AS tempo FROM tarefa
        INNER JOIN demanda ON tarefa.idDemanda = demanda.idDemanda
        INNER JOIN tipoocorrencia ON tarefa.idTipoOcorrencia = tipoocorrencia.idTipoOcorrencia
        WHERE   tarefa.dataReal >= '" . $sqldti . "'
                AND tarefa.dataReal < '" . $sqldtf . "'
                AND tarefa.horaFinalReal IS NOT NULL";

$where = " AND ";
if (isset($jsonEntrada["idContratoTipo"])) {
    $sql .= $where . " demanda.idContratoTipo = '" . $jsonEntrada["idContratoTipo"] . "'";
    $where = " AND ";
} 
if (isset($jsonEntrada["idCliente"])) {
    $sql .= $where . " tarefa.idCliente = " . $jsonEntrada["idCliente"];
    $where = " AND ";
}

$sql .= " GROUP BY demanda.idDemanda, tarefa.idTipoOcorrencia, tipoocorrencia.nomeTipoOcorrencia
          ORDER BY demanda.idDemanda, tarefa.idTipoOcorrencia";


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
        
        $tempo = $demanda['tempo'];
        $horas = floor($tempo / 3600);
        $minutos = floor(($tempo - ($horas*3600)) / 60);
        $segundos = $tempo % 60;
        $demanda['tempo'] = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
        
        $cobrado += $tempo;

        $totalTempo += $tempo;

        if ($i < $count - 1) {  
            $demanda['tempoCobrado'] = sprintf('%02d:%02d:%02d', floor($tempo / 3600), floor(($tempo % 3600) / 60), $tempo % 60);
        } else {  
            if ($cobrado < 1800) { 
                $tempoRestante = 1800 - $cobrado; 
                $totalCobradoSegundos = $tempo + $tempoRestante;
                $demanda['tempoCobrado'] = sprintf('%02d:%02d:%02d', floor($totalCobradoSegundos / 3600), floor(($totalCobradoSegundos % 3600) / 60), $totalCobradoSegundos % 60);
            } else {
                $demanda['tempoCobrado'] = sprintf('%02d:%02d:%02d', floor($tempo / 3600), floor(($tempo % 3600) / 60), $tempo % 60);
            }
        }
        $totalCobrado += strtotime($demanda['tempoCobrado']) - strtotime('TODAY');
    }
    unset($demanda); 
}

foreach ($demandaArray as $demandasPorIdDemanda) {
    $demandas = array_merge($demandas, $demandasPorIdDemanda);
}


$sums = [];

foreach ($demandas as $demanda) {
    $idTipoOcorrencia = $demanda['idTipoOcorrencia'];
    $tempoCobrado = $demanda['tempoCobrado'];

    list($h, $m, $s) = explode(':', $tempoCobrado);
    $seconds = $h * 3600 + $m * 60 + $s;

    $sums[$idTipoOcorrencia] = isset($sums[$idTipoOcorrencia]) ? $sums[$idTipoOcorrencia] + $seconds : $seconds;
}

$result = [];
foreach ($sums as $idTipoOcorrencia => $totalSeconds) {
    $horasOcorrencia = floor($totalSeconds / 3600);
    $minutosOcorrencia = floor(($totalSeconds % 3600) / 60);
    $segundosOcorrencia = $totalSeconds % 60;
    
    $totalTime = sprintf("%02d:%02d:%02d", $horasOcorrencia, $minutosOcorrencia, $segundosOcorrencia);
    
    $nomeTipoOcorrencia = '';
    foreach ($demandas as $demanda) {
        if ($demanda['idTipoOcorrencia'] == $idTipoOcorrencia) {
            $nomeTipoOcorrencia = $demanda['nomeTipoOcorrencia'];
            break;
        }
    }

    $result[] = [
        "idTipoOcorrencia" => $idTipoOcorrencia,
        "nomeTipoOcorrencia" => $nomeTipoOcorrencia,
        "Total" => $totalTime
    ];
}

$jsonSaida = $result;

//echo "-SAIDA->".json_encode($jsonSaida)."\n";

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG