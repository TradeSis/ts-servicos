<?php
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";
/*
Exemplo de entrada :
{"idEmpresa":"1","idTarefa":"1360","acao":"start"}
{"idEmpresa":"1","idTarefa":"1363","acao":"stop"}
{"idEmpresa":"1","idTarefa":"1364","acao":"realizado"}
*/

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "tarefas_realizado";
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

function buscaHorasRealizado($conexao, $idDemanda, $tempoCobradoDigitado)
{
    $totalHorasReal = "null";

    if($tempoCobradoDigitado == "0"){
        $sql_consultaHoras = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(subquery.horasReal))) AS totalHorasReal
        FROM (SELECT TIMEDIFF(tarefa.horaFinalReal, tarefa.horaInicioReal) AS horasReal FROM tarefa
        where tarefa.idDemanda = $idDemanda) AS subquery";
        $buscar_consultaHoras = mysqli_query($conexao, $sql_consultaHoras);
        $row_consultaHoras = mysqli_fetch_array($buscar_consultaHoras, MYSQLI_ASSOC);
        $totalHorasReal  = $row_consultaHoras["totalHorasReal"];
    }
    return $totalHorasReal;
}

date_default_timezone_set('America/Sao_Paulo');
$idEmpresa = null;
if (isset($jsonEntrada["idEmpresa"])) {
    $idEmpresa = $jsonEntrada["idEmpresa"];
}
$conexao = conectaMysql($idEmpresa);


if (isset($jsonEntrada['idTarefa'])) {
    $idTarefa = $jsonEntrada['idTarefa'];
    $dataReal = "'" . date('Y-m-d') . "'";
    $horaInicioReal = "'" . date('H:i:00') . "'";
    $horaFinalReal = "'" . date('H:i:00') . "'";
    
    $comentario = $jsonEntrada['comentario'];

    //Busca dados de Tarefa    
    $sql_consultaTarefa = "SELECT * FROM tarefa WHERE idTarefa = $idTarefa";
    $buscar_consultaTarefa = mysqli_query($conexao, $sql_consultaTarefa);
    $row_consultaTarefa = mysqli_fetch_array($buscar_consultaTarefa, MYSQLI_ASSOC);
    $idDemanda = isset($row_consultaTarefa["idDemanda"])  && $row_consultaTarefa["idDemanda"] !== ""  ? "'". $row_consultaTarefa["idDemanda"]."'"  : "null";


    $statusStart = array(
        TIPOSTATUS_FILA,
        TIPOSTATUS_PAUSADO,
        TIPOSTATUS_RETORNO,
        TIPOSTATUS_RESPONDIDO,
        TIPOSTATUS_AGENDADO
    );

    $tempoCobradoDigitado = "null";

    if ($idDemanda !== "null") {
        //Se tiver demanda, vai ser atribuido novo valor para variavel $tipoStatusDemanda
        $sql_consulta ="SELECT demanda.idTipoStatus, demanda.tempoCobradoDigitado, demanda.tituloDemanda,
                                cliente.nomeCliente, servicos.nomeServico, contrato.tituloContrato,
                                atendente.nomeUsuario AS nomeAtendente, atendente.email AS emailAtendente,
                                solicitante.nomeUsuario AS nomeSolicitante, solicitante.email AS emailSolicitante FROM demanda
                    LEFT JOIN cliente ON demanda.idCliente = cliente.idCliente
                    LEFT JOIN contrato ON demanda.idContrato = contrato.idContrato
                    LEFT JOIN servicos ON demanda.idServico = servicos.idServico
                    LEFT JOIN usuario AS atendente ON demanda.idAtendente = atendente.idUsuario
                    LEFT JOIN usuario AS solicitante ON demanda.idSolicitante = solicitante.idUsuario
                    WHERE idDemanda = $idDemanda";
        $buscar_consulta = mysqli_query($conexao, $sql_consulta);
        $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
        $tipoStatusDemanda = $row_consulta["idTipoStatus"];
        $tempoCobradoDigitado = $row_consulta["tempoCobradoDigitado"];
        $tituloDemanda = $row_consulta["tituloDemanda"];
        $tituloContrato = $row_consulta["tituloContrato"];
        $nomeCliente = $row_consulta['nomeCliente'];
        $nomeServico = $row_consulta['nomeServico'];
        $nomeUsuario = $row_consulta["nomeAtendente"];
        $email = $row_consulta["emailAtendente"];
        $nomeSolicitante = $row_consulta["nomeSolicitante"];
        $emailSolicitante = $row_consulta["emailSolicitante"];
        $dataEmail= date('H:i d/m/Y');

    }

    //ação : REALIZADO
    if ($jsonEntrada['acao'] == "realizado") {
        
        $enviaEmail = 1;

        $sql = "UPDATE tarefa SET dataReal = $dataReal, horaInicioReal = $horaInicioReal , horaFinalReal = $horaFinalReal  WHERE idTarefa = $idTarefa";

        if ($idDemanda !== "null") {
            $idTipoStatus = TIPOSTATUS_PAUSADO;
            $nomeStatusEmail = 'PAUSADO';
            //Busca dados Tipostatus    
            $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
            $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
            $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
            $posicao = $row_consultaStatus["mudaPosicaoPara"];
            $statusDemanda = $row_consultaStatus["mudaStatusPara"];

            $sql_update_demanda = "UPDATE demanda SET posicao=$posicao, idTipoStatus=$idTipoStatus, dataAtualizacaoAtendente=CURRENT_TIMESTAMP(), statusDemanda=$statusDemanda WHERE idDemanda = $idDemanda";
        }
    }


    //ação : START
    if ($jsonEntrada['acao'] == "start") {
        $enviaEmail = 0;
        // lucas id654 - Adicionado dataOrdem e horaInicioReal
        $dataOrdem = $dataReal;
        $horaInicioOrdem = $horaInicioReal;

        $sql = "UPDATE tarefa SET horaInicioReal = $horaInicioReal, dataReal = $dataReal , dataOrdem = $dataOrdem, horaInicioOrdem = $horaInicioOrdem  WHERE idTarefa = $idTarefa";

        if ($idDemanda !== "null") {

            $idTipoStatus = TIPOSTATUS_FAZENDO;
            $nomeStatusEmail = 'FAZENDO';
            //Busca dados Tipostatus    
            $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
            $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
            $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
            $posicao = $row_consultaStatus["mudaPosicaoPara"];
            $statusDemanda = $row_consultaStatus["mudaStatusPara"];
            $dataInicio = "'". date('Y/m/d') . "'";
            $sql_update_demanda = "UPDATE demanda SET dataAtualizacaoAtendente=CURRENT_TIMESTAMP(), dataInicio = $dataInicio ";
                if (in_array($tipoStatusDemanda, $statusStart)) {
                    $sql_update_demanda = $sql_update_demanda . ", posicao=$posicao, idTipoStatus=$idTipoStatus, statusDemanda=$statusDemanda ";
                }
            $sql_update_demanda = $sql_update_demanda . "  WHERE idDemanda = $idDemanda";
        }
    }

    //ação : STOP
    if ($jsonEntrada['acao'] == "stop") {

        $enviaEmail = 0;

        $sql = "UPDATE tarefa SET horaFinalReal = $horaFinalReal  WHERE idTarefa = $idTarefa"; 
        //Vai executar o UPDATE primerio, para depois atualizar o $totalHorasReal
        $atualizar = mysqli_query($conexao, $sql);

        if ($idDemanda !== "null") {
           
            $idTipoStatus = TIPOSTATUS_PAUSADO;
            $nomeStatusEmail = 'Pausado';
            //Busca dados Tipostatus    
            $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
            $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
            $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
            $posicao = $row_consultaStatus["mudaPosicaoPara"];
            $statusDemanda = $row_consultaStatus["mudaStatusPara"];

            $sql_update_demanda = "UPDATE demanda SET dataAtualizacaoAtendente=CURRENT_TIMESTAMP() ";
                if ($tempoCobradoDigitado == "0") {
                    $totalHorasReal = buscaHorasRealizado($conexao, $idDemanda, $tempoCobradoDigitado);
                    $tempoCobrado = $totalHorasReal;
                    //echo $totalHorasReal;
                    if (strtotime($totalHorasReal) < strtotime('00:30:00')) {
                        $tempoCobrado = '00:30:00';
                    }
                    
                    $tempoCobrado = "'" . $tempoCobrado . "'";
                    $sql_update_demanda = $sql_update_demanda . ",tempoCobrado = $tempoCobrado ";
                }

                if ($tipoStatusDemanda == TIPOSTATUS_FAZENDO) {
                    $sql_update_demanda = $sql_update_demanda . ",idTipoStatus=$idTipoStatus, dataAtualizacaoAtendente=CURRENT_TIMESTAMP(), statusDemanda=$statusDemanda ";
                }
            $sql_update_demanda = $sql_update_demanda . "  WHERE idDemanda = $idDemanda";
        }
    }


    //ação : ENTREGUE
    if ($jsonEntrada['acao'] == "entregue") {

        $enviaEmail = 1;

        $sql = "UPDATE tarefa SET horaFinalReal = $horaFinalReal  WHERE idTarefa = $idTarefa";
        $atualizar = mysqli_query($conexao, $sql);

        if ($idDemanda !== "null") {
            $idTipoStatus = TIPOSTATUS_REALIZADO;
            $nomeStatusEmail = 'Entregue';
            //Busca dados Tipostatus    
            $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
            $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
            $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
            $posicao = $row_consultaStatus["mudaPosicaoPara"];
            $statusDemanda = $row_consultaStatus["mudaStatusPara"];

            $sql_update_demanda = "UPDATE demanda SET posicao=$posicao, idTipoStatus=$idTipoStatus, dataAtualizacaoAtendente=CURRENT_TIMESTAMP(), dataFechamento = CURRENT_TIMESTAMP(), statusDemanda=$statusDemanda ";
            if ($tempoCobradoDigitado == "0") {
                $totalHorasReal = buscaHorasRealizado($conexao, $idDemanda, $tempoCobradoDigitado);
                $tempoCobrado = $totalHorasReal;
                if (strtotime($totalHorasReal) < strtotime('00:30:00')) {
                    $tempoCobrado = '00:30:00';
                }
                
                $tempoCobrado = "'" . $tempoCobrado . "'";
                $sql_update_demanda = $sql_update_demanda . ",tempoCobrado = $tempoCobrado ";
            }
            $sql_update_demanda = $sql_update_demanda . " WHERE idDemanda = $idDemanda";
        }
        
    }

    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 3) {
            fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
            if (isset($sql_update_demanda)) {
                fwrite($arquivo, $identificacao . "-SQL_UPDATE_DEMANDA->" . $sql_update_demanda . "\n");
            }
        }
    }
    //LOG

    //TRY-CATCH
    try {

        $atualizar = mysqli_query($conexao, $sql);
        if (!$atualizar)
            throw new Exception(mysqli_error($conexao));
        if (isset($sql_update_demanda)) {
            $atualizar2 = mysqli_query($conexao, $sql_update_demanda);
            if (!$atualizar2)
                throw new Exception(mysqli_error($conexao));
        }
        if ($enviaEmail == 1 && $comentario == null) {
            //Envio de Email
            $tituloEmail = '[' . $nomeCliente . '/' . $nomeStatusEmail . '] ' . $tituloContrato . '/ ' . $idDemanda .' ' . $tituloDemanda;
            $corpoEmail = "
            <html>
            <head>
                <title>$tituloEmail</title>
            </head>
            <body>
                <p>Solicitante: $nomeSolicitante<br>
                    Servico: $nomeServico</p>
            
                <p>Status atualizado para: $nomeStatusEmail</p>
            
                <p><a href='https://meucontrole.pro/services/'>https://meucontrole.pro/services/</a></p>
            </body>
            </html>";

            $arrayPara = array(

                array(
                    'email' => 'tradesis@tradesis.com.br',
                    'nome' => 'TradeSis'
                ),
                array(
                'email' => $email,
                'nome' => $nomeUsuario 
                ),
            );
            //gabriel 03062024 id 999 adicionado $idEmpresa
            $envio = emailEnviar(null,null,$arrayPara,$tituloEmail,$corpoEmail,$idEmpresa,1);
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
