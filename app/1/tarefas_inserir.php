<?php
 //Lucas 10112023 ID 965 - Melhorias Tarefas
// lucas id654 - Melhorias Tarefas
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

/* 
Exemplo de entrada
{
    "idEmpresa": "1",
    "tituloTarefa": "teste",
    "idCliente": "10",
    "idDemanda": "661",
    "idAtendente": "14",
    "idTipoOcorrencia": "8",
    "Previsto": "2023-11-17",
    "horaInicioPrevisto": "13:33",
    "horaFinalPrevisto": "14:33",
    "acao": "start"
} 
*/

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "tarefas_inserir";
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
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
    }
}
//LOG

$statusAgendado = array(
    TIPOSTATUS_FILA,
    TIPOSTATUS_RESPONDIDO
);
$statusStart = array(
    TIPOSTATUS_FILA,
    TIPOSTATUS_PAUSADO,
    TIPOSTATUS_RETORNO,
    TIPOSTATUS_RESPONDIDO,
    TIPOSTATUS_AGENDADO
);

date_default_timezone_set('America/Sao_Paulo');
$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);

if (isset($jsonEntrada['idEmpresa'])) {

    $tituloTarefa = isset($jsonEntrada['tituloTarefa']) && $jsonEntrada['tituloTarefa'] !== "null"    ? "'" . $jsonEntrada['tituloTarefa'] . "'" : "null";
    $idDemanda  = isset($jsonEntrada['idDemanda'])  && $jsonEntrada['idDemanda'] !== ""        ?   $jsonEntrada['idDemanda']    : "null";
    $idCliente  = isset($jsonEntrada['idCliente'])  && $jsonEntrada['idCliente'] !== ""        ?   $jsonEntrada['idCliente']    : "null";
    $Previsto  = isset($jsonEntrada['Previsto'])  && $jsonEntrada['Previsto'] !== "" && $jsonEntrada['Previsto'] !== "null" ? "'". $jsonEntrada['Previsto']."'"  : "null";
    $horaInicioPrevisto  = isset($jsonEntrada['horaInicioPrevisto'])  && $jsonEntrada['horaInicioPrevisto'] !== "" && $jsonEntrada['horaInicioPrevisto'] !== "null" ? "'". $jsonEntrada['horaInicioPrevisto']."'"  : "null";
    $horaFinalPrevisto  = isset($jsonEntrada['horaFinalPrevisto'])  && $jsonEntrada['horaFinalPrevisto'] !== "" && $jsonEntrada['horaFinalPrevisto'] !== "null" ? "'". $jsonEntrada['horaFinalPrevisto']."'"  : "null";
     
    $idTipoOcorrencia = $jsonEntrada['idTipoOcorrencia'];
    $idAtendente = $jsonEntrada['idAtendente'];
    $acao = $jsonEntrada['acao'];

    $dataOrdem = $Previsto;
    $horaInicioOrdem = $horaInicioPrevisto;

    $idTipoStatus = TIPOSTATUS_FILA;
    if ($acao == 'start') {
        $idTipoStatus = TIPOSTATUS_FAZENDO;
        $dataReal = "'" . date('Y-m-d') . "'";
        $horaInicioReal = "'" . date('H:i:00') . "'";  
        $dataOrdem = $dataReal;
        $horaInicioOrdem = $horaInicioReal;
    } 
   
    $sql =       "INSERT INTO tarefa(tituloTarefa, idCliente, idDemanda, idAtendente, idTipoOcorrencia, Previsto, horaInicioPrevisto, horaFinalPrevisto, dataOrdem,horaInicioOrdem " ;
    $sqlvalue = " VALUES ($tituloTarefa, $idCliente, $idDemanda, $idAtendente, $idTipoOcorrencia, $Previsto, $horaInicioPrevisto, $horaFinalPrevisto, $dataOrdem, $horaInicioOrdem ";

    if ($acao == 'start') {
        $sql .= " , horaInicioReal, dataReal ";
        $sqlvalue .= " , $horaInicioReal, $dataReal ";
    } 

    $sqlvalue .= " ) ";
    $sql .= " ) " . $sqlvalue;

    if ($idDemanda !== "null") {
        //Busca dados Demanda
        $sql_consulta1 = "SELECT demanda.idTipoStatus FROM demanda WHERE idDemanda = $idDemanda";
        $buscar_consulta1 = mysqli_query($conexao, $sql_consulta1);
        $row_consulta1 = mysqli_fetch_array($buscar_consulta1, MYSQLI_ASSOC);
        $tipoStatusDemanda = $row_consulta1["idTipoStatus"]; 

        //Busca dados Tipostatus    
        $sql_consulta = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
        $buscar_consulta = mysqli_query($conexao, $sql_consulta);
        $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
        $posicao = $row_consulta["mudaPosicaoPara"];
        $statusDemanda = $row_consulta["mudaStatusPara"] ;
        $dataInicio = "'". date('Y/m/d') . "'";
        if ($LOG_NIVEL >= 2) {
            fwrite($arquivo, $identificacao . "-Previsto->" . $jsonEntrada['Previsto'] . " tipoStatusDemanda=" . $tipoStatusDemanda . " statusTarefa=" . json_encode($statusStart) . "\n");
        }
        if (($acao == 'start') && in_array($tipoStatusDemanda, $statusStart, true)) {
            $idTipoStatus = TIPOSTATUS_FAZENDO;
            $sql3 = "UPDATE demanda SET posicao=$posicao, idTipoStatus=$idTipoStatus, dataAtualizacaoAtendente=CURRENT_TIMESTAMP(), dataInicio = $dataInicio, statusDemanda=$statusDemanda WHERE idDemanda = $idDemanda";
        } else {
            if ($jsonEntrada['Previsto'] != "" && in_array($tipoStatusDemanda, $statusAgendado, true)) {
                $idTipoStatus = TIPOSTATUS_AGENDADO;
                $sql3 = "UPDATE demanda SET posicao=$posicao, idTipoStatus=$idTipoStatus, dataAtualizacaoAtendente=CURRENT_TIMESTAMP(), statusDemanda=$statusDemanda, dataPrevisaoInicio=$Previsto WHERE idDemanda = $idDemanda";
            } 
        }

    }
    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 2) {
            fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
            if(isset($sql3)){
                fwrite($arquivo, $identificacao . "-SQL3->" . $sql3 . "\n");
            }
            
        }
    }
    //LOG

    //TRY-CATCH
    try {

        $atualizar = mysqli_query($conexao, $sql);
        if (!$atualizar)
            throw new Exception(mysqli_error($conexao));
        if(isset($sql3)){
            $atualizar3 = mysqli_query($conexao, $sql3);
            if (!$atualizar3)
            throw new Exception(mysqli_error($conexao));
        }

        $jsonSaida = array(
            "status" => 200,
            "retorno" => "ok"
        );

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


} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parametros"
    );

}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG



?>