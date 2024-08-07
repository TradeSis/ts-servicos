<?php
//lucas 22092023 ID 358 Demandas/Comentarios 
//gabriel 220323
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

/* 
Exemplo de entrada :
{"idEmpresa":"1","idDemanda":"749","idUsuario":"14","idCliente":"1","comentario":"<p>texto<\/p>","idAtendente":null,"acao":"entregar"}
{"idEmpresa":"1","idDemanda":"749","idUsuario":"14","idCliente":"1","comentario":"<p>texto<\/p>","idAtendente":null,"acao":"retornar"}
{"idEmpresa":"1","idDemanda":"748","idUsuario":"14","idCliente":"1","comentario":"<p>texto<\/p>","idAtendente":null,"acao":"validar"} 
{"idEmpresa":"1","idDemanda":"749","idUsuario":"14","idCliente":"1","comentario":"<p>texto<\/p>","idAtendente":"10","acao":"solicitar"}
*/

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "demanda_atualizar";
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
if (isset($jsonEntrada['idDemanda'])) {

    $idDemanda = $jsonEntrada['idDemanda'];
    $idAtendenteEncaminhar = $jsonEntrada['idAtendente']; // Encaminhar  
    $comentario = $jsonEntrada['comentario'];


    //Busca data de fechamento atual
    $sql_consulta = "SELECT demanda.tituloDemanda, demanda.idAtendente, demanda.dataFechamento,
                            cliente.nomeCliente, servicos.nomeServico, contrato.tituloContrato, 
                            atendente.nomeUsuario AS nomeAtendente, atendente.email AS emailAtendente,
                            solicitante.nomeUsuario AS nomeSolicitante FROM demanda
                        LEFT JOIN cliente ON demanda.idCliente = cliente.idCliente
                        LEFT JOIN contrato ON demanda.idContrato = contrato.idContrato
                        LEFT JOIN servicos ON demanda.idServico = servicos.idServico
                        LEFT JOIN usuario AS atendente ON demanda.idAtendente = atendente.idUsuario
                        LEFT JOIN usuario AS solicitante ON demanda.idSolicitante = solicitante.idUsuario
                        WHERE idDemanda = $idDemanda";
    $buscar_consulta = mysqli_query($conexao, $sql_consulta);
    $row_consulta = mysqli_fetch_array($buscar_consulta, MYSQLI_ASSOC);
    $dataFechamento = isset($row_consulta["dataFechamento"])  && $row_consulta["dataFechamento"] !== "" && $row_consulta["dataFechamento"] !== "null" ? "'". $row_consulta["dataFechamento"]."'"  : "null";
    $idAtendente = $row_consulta["idAtendente"];
    $tituloDemanda = $row_consulta["tituloDemanda"];
    $tituloContrato = $row_consulta["tituloContrato"];
    $nomeCliente = $row_consulta['nomeCliente'];
    $nomeServico = $row_consulta['nomeServico'];
    $nomeSolicitante = $row_consulta["nomeSolicitante"];
    $nomeUsuario = $row_consulta["nomeAtendente"];
    $email = $row_consulta["emailAtendente"];
    $dataEmail= date('H:i d/m/Y');
    if ($idAtendenteEncaminhar !== null && $idAtendente !== $idAtendenteEncaminhar) { 
        $idAtendente = $idAtendenteEncaminhar; 
        //Busca dados de usuario
        $sql_consultaUsuario = "SELECT * FROM usuario WHERE idUsuario = $idAtendente";
        $buscar_consultaUsuario = mysqli_query($conexao, $sql_consultaUsuario);
        $row_consultaUsuario = mysqli_fetch_array($buscar_consultaUsuario, MYSQLI_ASSOC);
        $nomeUsuario = $row_consultaUsuario["nomeUsuario"];
        $email = $row_consultaUsuario["email"] ;
        }


    //REALIZADO
    if ($jsonEntrada['acao'] == "entregar") { 

        $enviarTradesis = 1;
        $enviaEmail = 1;
        $idTipoStatus = TIPOSTATUS_REALIZADO;
        $nomeStatusEmail = 'Entregue';

        //Busca dados tipostatus    
        $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
        $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
        $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
        $posicao = $row_consultaStatus["mudaPosicaoPara"];
        $statusDemanda = $row_consultaStatus["mudaStatusPara"];

        //lucas 22092023 ID 358 modificado o teste para gravar a data quando for tipo encerrado
        if (($statusDemanda == 3) && ($dataFechamento === 'null')) { //se status for do tipo encerrar
                $dataFechamento = 'CURRENT_TIMESTAMP ()'; //grava a data de fechamento      
        } 

        $sql = "UPDATE demanda SET posicao = $posicao, idTipoStatus = $idTipoStatus, dataFechamento = $dataFechamento, dataAtualizacaoAtendente = CURRENT_TIMESTAMP(), 
        statusDemanda = $statusDemanda  WHERE demanda.idDemanda = $idDemanda ";

    }


    //RETORNAR
    if ($jsonEntrada['acao'] == "retornar") {

        $enviarTradesis = 0;
        $enviaEmail = 1;
        
        $idTipoStatus = TIPOSTATUS_RETORNO;
        $nomeStatusEmail = 'Reaberto';

        //Busca dados tipostatus     
        $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
        $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
        $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
        $posicao = $row_consultaStatus["mudaPosicaoPara"];
        $statusDemanda = $row_consultaStatus["mudaStatusPara"];

        $sql = "UPDATE demanda SET posicao=$posicao, idTipoStatus=$idTipoStatus, dataFechamento=NULL, statusDemanda=$statusDemanda, dataAtualizacaoCliente=CURRENT_TIMESTAMP(), QtdRetornos=QtdRetornos+1 WHERE idDemanda = $idDemanda;";

    }

    //RESPONDER
    if ($jsonEntrada['acao'] == "responder") {

        $enviarTradesis = 0;
        $enviaEmail = 1;
        
        $idTipoStatus = TIPOSTATUS_RESPONDIDO;
        $nomeStatusEmail = 'Respondido';

        //Busca dados tipostatus     
        $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
        $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
        $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
        $posicao = $row_consultaStatus["mudaPosicaoPara"];
        $statusDemanda = $row_consultaStatus["mudaStatusPara"];

        $sql = "UPDATE demanda SET dataFechamento=NULL, posicao=$posicao, idTipoStatus=$idTipoStatus, statusDemanda=$statusDemanda, dataAtualizacaoCliente=CURRENT_TIMESTAMP() WHERE idDemanda = $idDemanda;";

    }

    //DEVOLVER
    if ($jsonEntrada['acao'] == "devolver") {
        
        $enviarTradesis = 0;
        $enviaEmail = 1;

        $idTipoStatus = TIPOSTATUS_AGUARDANDOSOLICITANTE;
        $nomeStatusEmail = 'Devolvido';

        //Busca dados tipostatus     
        $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
        $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
        $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
        $posicao = $row_consultaStatus["mudaPosicaoPara"];
        $statusDemanda = $row_consultaStatus["mudaStatusPara"];
        // helio 12062024 
        if (($statusDemanda == 3) && ($dataFechamento === 'null')) { //se status for do tipo encerrar
            $dataFechamento = 'CURRENT_TIMESTAMP ()'; //grava a data de fechamento      
        } 
        $sql = "UPDATE demanda SET dataFechamento = $dataFechamento, posicao=$posicao, idTipoStatus=$idTipoStatus, statusDemanda=$statusDemanda, dataAtualizacaoAtendente=CURRENT_TIMESTAMP() WHERE idDemanda = $idDemanda;";

    }


    //VALIDAR
    if ($jsonEntrada['acao'] == "validar") {
        
        $enviarTradesis = 1;
        $enviaEmail = 1;

        $idTipoStatus = TIPOSTATUS_VALIDADO;
        $nomeStatusEmail = 'Encerrado';

        //Busca dados tipostatus    
        $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
        $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
        $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
        $posicao = $row_consultaStatus["mudaPosicaoPara"];
        $statusDemanda = $row_consultaStatus["mudaStatusPara"];

        if ($dataFechamento === 'null') {
            $dataFechamento = 'CURRENT_TIMESTAMP ()';
        } 
        $sql = "UPDATE demanda SET posicao=$posicao, idTipoStatus=$idTipoStatus, dataAtualizacaoCliente=CURRENT_TIMESTAMP(),dataFechamento = $dataFechamento, statusDemanda=$statusDemanda WHERE idDemanda = $idDemanda";

    }

    //RETORNAR
    if ($jsonEntrada['acao'] == "encaminhar") {

        $nomeStatusEmail = 'Encaminhamento';  
        $enviarTradesis = 0;
        $enviaEmail = 1;

        //lucas 22092023 ID 358 Adicionado idAtendente
        $sql = "UPDATE demanda SET idAtendente=$idAtendenteEncaminhar , dataAtualizacaoAtendente=CURRENT_TIMESTAMP() WHERE idDemanda = $idDemanda";
        
    }

    //SUBDEMANDA
    if ($jsonEntrada['acao'] == "subdemanda") {

        $idLogin = $jsonEntrada['idLogin'];

        //busca dados solicitante    
        $sql_solicitante = "SELECT idUsuario FROM usuario WHERE idLogin = $idLogin";
        $buscar_solicitante = mysqli_query($conexao, $sql_solicitante);
        $row_solicitante = mysqli_fetch_array($buscar_solicitante, MYSQLI_ASSOC);
        $idSolicitante = $row_solicitante['idUsuario']; 
        
        $idTipoStatus = TIPOSTATUS_FILA;

        //Busca dados tipostatus    
        $sql_consultaStatus = "SELECT * FROM tipostatus WHERE idTipoStatus = $idTipoStatus";
        $buscar_consultaStatus = mysqli_query($conexao, $sql_consultaStatus);
        $row_consultaStatus = mysqli_fetch_array($buscar_consultaStatus, MYSQLI_ASSOC);
        $posicao = $row_consultaStatus["mudaPosicaoPara"];
        $statusDemanda = $row_consultaStatus["mudaStatusPara"];

        //busca dados demanda    
        $sql2 = "SELECT * FROM demanda WHERE idDemanda = $idDemanda";
        $buscar2 = mysqli_query($conexao, $sql2);
        $row = mysqli_fetch_array($buscar2, MYSQLI_ASSOC);

        $prioridade = isset($row['prioridade'])  && $row['prioridade'] !== "" && $row['prioridade'] !== "null" ? "'". $row['prioridade']."'"  : "null";
        $tituloDemanda = isset($row['tituloDemanda'])  && $row['tituloDemanda'] !== "" && $row['tituloDemanda'] !== "null" ? "'(". $idDemanda .") ". $row['tituloDemanda']."'"  : "null";
        $descricao = isset($row['descricao'])  && $row['descricao'] !== "" && $row['descricao'] !== "null" ? "'". $row['descricao']."'"  : "null";
        $dataAbertura = isset($row['dataAbertura'])  && $row['dataAbertura'] !== "" && $row['dataAbertura'] !== "null" ? "'". $row['dataAbertura']."'"  : "null";
        $idCliente = isset($row['idCliente'])  && $row['idCliente'] !== "" && $row['idCliente'] !== "null" ? "'". $row['idCliente']."'"  : "null";
        $idServico = isset($row['idServico'])  && $row['idServico'] !== "" && $row['idServico'] !== "null" ? "'". $row['idServico']."'"  : "null";
        $idContrato = isset($row['idContrato'])  && $row['idContrato'] !== "" && $row['idContrato'] !== "null" ? "'". $row['idContrato']."'"  : "null";
        $idContratoTipo = isset($row['idContratoTipo'])  && $row['idContratoTipo'] !== "" && $row['idContratoTipo'] !== "null" ? "'". $row['idContratoTipo']."'"  : "null";
        $idAtendente = isset($row['idAtendente'])  && $row['idAtendente'] !== "" && $row['idAtendente'] !== "null" ? "'". $row['idAtendente']."'"  : "null";
        $horasPrevisao = isset($row['horasPrevisao'])  && $row['horasPrevisao'] !== "" && $row['horasPrevisao'] !== "null" ? "'". $row['horasPrevisao']."'"  : "null";
        $dataPrevisaoEntrega = isset($row['dataPrevisaoEntrega'])  && $row['dataPrevisaoEntrega'] !== "" && $row['dataPrevisaoEntrega'] !== "null" ? "'". $row['dataPrevisaoEntrega']."'"  : "null";
        $dataPrevisaoInicio = isset($row['dataPrevisaoInicio'])  && $row['dataPrevisaoInicio'] !== "" && $row['dataPrevisaoInicio'] !== "null" ? "'". $row['dataPrevisaoInicio']."'"  : "null";
        $tempoCobrado = isset($row['tempoCobrado'])  && $row['tempoCobrado'] !== "" && $row['tempoCobrado'] !== "null" ? "'". $row['tempoCobrado']."'"  : "null";
        $tempoCobradoDigitado = isset($row['tempoCobradoDigitado'])  && $row['tempoCobradoDigitado'] !== "" && $row['tempoCobradoDigitado'] !== "null" ? "'". $row['tempoCobradoDigitado']."'"  : "null";

        $sql = "INSERT INTO demanda (prioridade, tituloDemanda, descricao, dataAbertura, idTipoStatus, posicao, statusDemanda, idCliente, idSolicitante, idServico, idContrato, 
        idContratoTipo, horasPrevisao, idAtendente, dataPrevisaoEntrega, dataPrevisaoInicio, tempoCobrado, tempoCobradoDigitado, idDemandaSuperior)
        VALUES ($prioridade, $tituloDemanda, $descricao, $dataAbertura, $idTipoStatus, $posicao, $statusDemanda, $idCliente, $idSolicitante, $idServico, $idContrato, 
        $idContratoTipo, $horasPrevisao, $idAtendente, $dataPrevisaoEntrega, $dataPrevisaoInicio, $tempoCobrado, $tempoCobradoDigitado, $idDemanda)";
    
    }

    //LOG
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 3) {
            fwrite($arquivo, $identificacao . "-SQL->" . $sql . "\n");
        }
    }

    
    //LOG

    //TRY-CATCH
    try {

        $atualizar = mysqli_query($conexao, $sql);
        if (!$atualizar)
            throw new Exception(mysqli_error($conexao));

        if ($enviaEmail == 1 /*&& $comentario == null*/) {
            //Envio de Email
            $tituloEmail = '[' . $nomeCliente . '/' . $nomeStatusEmail . '] ' . $tituloContrato . '/ ' . $idDemanda .' ' . $tituloDemanda;
            $corpoEmail = "
            <html>
            <head>
                <title>$tituloEmail</title>
            </head>
            <body>
                <p>Solicitante : $nomeSolicitante<br>
                   Servico     : $nomeServico<br>
                </p>";
            
                
            
            if ($nomeStatusEmail == "Encaminhamento") {
                $corpoEmail .= "
                <p>Encaminhado para: $nomeUsuario</p>";
            } else {
                $corpoEmail .= "
                <p>Status atualizado para: $nomeStatusEmail</p>";
            }

            $corpoEmail .= "
                <p><a href='https://meucontrole.pro/servicos/'>https://meucontrole.pro/servicos/</a></p>
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
            $envio = emailEnviar(null,null,$arrayPara,$tituloEmail,$corpoEmail,$idEmpresa,$enviarTradesis);
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