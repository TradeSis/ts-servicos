<?php
// Lucas 20112023 - ID 965 - Melhorias Tarefas
// Lucas 08112023 - id965 Melhorias Tarefas
// lucas id654 - Melhorias Tarefas
//Gabriel 11102023 ID 596 mudanças em agenda e tarefas
// helio 12072023 - ajustes de horas
//gabriel 07022023 16:25
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

/* 
Exemplo de entrada
{
    "idEmpresa": "1",
    "idTarefa": "1442",
    "descricao": "texto",
    "idAtendente": "14",
    "idCliente": "1",
    "tituloTarefa": "teste",
    "idTipoOcorrencia": "11",
    "dataReal": "2023-11-20",
    "horaInicioReal": "10:00",
    "horaFinalReal": "11:00",
    "Previsto": "2023-11-19",
    "horaInicioPrevisto": "08:00",
    "horaFinalPrevisto": "09:00"
} */

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "tarefas_alterar";
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


$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);

if (isset($jsonEntrada['idTarefa'])) {
    // Lucas 20112023 - ID 965 - modifcado teste de "null" do jsonEntrada, removido partes do codigo não usada
    $idTarefa = $jsonEntrada['idTarefa'];
    $tituloTarefa = isset($jsonEntrada['tituloTarefa']) && $jsonEntrada['tituloTarefa'] !== "null"    ? "'" . $jsonEntrada['tituloTarefa'] . "'" : "null"; 
    $idTipoOcorrencia  = isset($jsonEntrada['idTipoOcorrencia'])  && $jsonEntrada['idTipoOcorrencia'] !== ""        ?   $jsonEntrada['idTipoOcorrencia']    : "null";   
    $idAtendente  = isset($jsonEntrada['idAtendente'])  && $jsonEntrada['idAtendente'] !== ""        ?   $jsonEntrada['idAtendente']    : "null";
    $idCliente  = isset($jsonEntrada['idCliente'])  && $jsonEntrada['idCliente'] !== ""        ?   $jsonEntrada['idCliente']    : "null";          
    $descricao = isset($jsonEntrada['descricao']) && $jsonEntrada['descricao'] !== "null"    ? "'" . $jsonEntrada['descricao'] . "'" : "null";                
 
    $Previsto  = isset($jsonEntrada['Previsto'])  && $jsonEntrada['Previsto'] !== "" && $jsonEntrada['Previsto'] !== "null" ? "'" . $jsonEntrada['Previsto']. "'"  : "null";
    $horaInicioPrevisto  = isset($jsonEntrada['horaInicioPrevisto'])  && $jsonEntrada['horaInicioPrevisto'] !== "" && $jsonEntrada['horaInicioPrevisto'] !== "null" ? "'" . $jsonEntrada['horaInicioPrevisto']. "'"  : "null";
    $horaFinalPrevisto  = isset($jsonEntrada['horaFinalPrevisto'])  && $jsonEntrada['horaFinalPrevisto'] !== "" && $jsonEntrada['horaFinalPrevisto'] !== "null" ? "'" . $jsonEntrada['horaFinalPrevisto'] ."'"  : "null";
    $dataOrdem = $Previsto;
    $horaInicioOrdem = $horaInicioPrevisto;
    
    //Verifica se a tarefa tem dataReal
    $sql_consulta = "SELECT * FROM tarefa WHERE idTarefa = $idTarefa";
    $buscar_consulta = mysqli_query($conexao, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);

    $dataReal = isset($row_consulta["dataReal"]) && $row_consulta["dataReal"] !== "null" ? "'" . $row_consulta["dataReal"]. "'"  : "null";
    $horaInicioReal = isset($row_consulta["horaInicioReal"]) && $row_consulta["horaInicioReal"] !== "null" ? "'" . $row_consulta["horaInicioReal"]. "'"  : "null";
            
    if($dataReal != "null"){
        $dataOrdem = $dataReal;
        $horaInicioOrdem = $horaInicioReal;
    }



    $sql = "UPDATE tarefa SET tituloTarefa = $tituloTarefa, idAtendente = $idAtendente, idTipoOcorrencia = $idTipoOcorrencia, Previsto = $Previsto, horaInicioPrevisto = $horaInicioPrevisto, horaFinalPrevisto = $horaFinalPrevisto, descricao = $descricao, 
    dataOrdem = $dataOrdem, horaInicioOrdem = $horaInicioOrdem ";

    //Verifica se a tarefa tem Demanda
    $sql_consulta = "SELECT * FROM tarefa WHERE idTarefa = $idTarefa";
    $buscar_consulta = mysqli_query($conexao, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
    $idDemanda = $row_consulta["idDemanda"];
    if($idDemanda === null){
        $idDemanda = "null";
    }

    if ($idDemanda !== "null") {
        // busca dados idCliente/Demanda
        $sql_consulta = "SELECT * FROM demanda WHERE idDemanda = $idDemanda";
        $buscar_consulta = mysqli_query($conexao, $sql_consulta);
        $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
        $idCliente = $row_consulta["idCliente"];
       
    }

    $sql = $sql . ", `idDemanda`=$idDemanda, `idCliente`=$idCliente";

    $sql = $sql . " WHERE `idTarefa` = $idTarefa";


    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 2) {
            fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
        }
    }
    //LOG

    //TRY-CATCH
    try {

        $atualizar = mysqli_query($conexao, $sql);
        if (!$atualizar)
            throw new Exception(mysqli_error($conexao));

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