<?php
//gabriel 06062024
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";


//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "orcamento_atualizar";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "orcamento_" . date("dmY") . ".log", "a");
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
if (isset($jsonEntrada['idOrcamento'])) {

    $idOrcamento = $jsonEntrada['idOrcamento'];

    $sql_status = "SELECT orcamento.statusOrcamento FROM orcamento WHERE idOrcamento = $idOrcamento";
    $buscar_status = mysqli_query($conexao, $sql_status);
    $row_status = mysqli_fetch_array($buscar_status, MYSQLI_ASSOC);
    $SQLstatusOrcamento = $row_status["statusOrcamento"];
    

    //REPROVAR
    if ($jsonEntrada['acao'] == "reprovar") {

        $enviarTradesis = 0;
        $statusOrcamento = ORCAMENTOSTATUS_REPROVADO;
        $nomeStatusEmail = 'NÃO APROVADO';


        $sql = "UPDATE orcamento SET statusOrcamento=$statusOrcamento, dataAprovacao=CURRENT_TIMESTAMP() WHERE idOrcamento = $idOrcamento";

    }
    //APROVAR
    if ($jsonEntrada['acao'] == "aprovar") {

        $enviarTradesis = 0;
        $statusOrcamento = ORCAMENTOSTATUS_APROVADO;
        $nomeStatusEmail = 'APROVADO';

        $sql = "UPDATE orcamento SET statusOrcamento=$statusOrcamento, dataAprovacao=CURRENT_TIMESTAMP() WHERE idOrcamento = $idOrcamento";

    }
    //PEDIR ORCAMENTO
    if ($jsonEntrada['acao'] == "pedir") {

        $enviarTradesis = 0;
        $statusOrcamento = ORCAMENTOSTATUS_ORCAR;
        $nomeStatusEmail = 'ORÇAMENTO';

        $sql = "UPDATE orcamento SET statusOrcamento=$statusOrcamento WHERE idOrcamento = $idOrcamento";

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

        if ($statusOrcamento !== $SQLstatusOrcamento) {

            $sql_orcamento = "SELECT orcamento.tituloOrcamento,orcamento.horas, orcamento.valorHora, orcamento.valorOrcamento,
                                     orcamento.descricao, cliente.nomeCliente, orcamentostatus.nomeOrcamentoStatus, 
                              solicitante.nomeUsuario AS nomeSolicitante, solicitante.email AS emailSolicitante FROM orcamento				
                              LEFT JOIN cliente on cliente.idCliente = orcamento.idcliente 
                              LEFT JOIN orcamentostatus  on  orcamento.statusOrcamento = orcamentostatus.idOrcamentoStatus
                              LEFT JOIN usuario AS solicitante ON orcamento.idSolicitante = solicitante.idUsuario
                              WHERE idOrcamento = $idOrcamento";
            $buscar_orcamento = mysqli_query($conexao, $sql_orcamento);
            $row_orcamento = mysqli_fetch_array($buscar_orcamento, MYSQLI_ASSOC);
            $tituloOrcamento = $row_orcamento["tituloOrcamento"];
            $descricao = $row_orcamento["descricao"];
            $nomeCliente = $row_orcamento['nomeCliente'];
            $nomeOrcamentoStatus = $row_orcamento['nomeOrcamentoStatus'];
            $nomeSolicitante = $row_orcamento['nomeSolicitante'];
            $emailSolicitante = $row_orcamento['emailSolicitante'];
            $horas = $row_orcamento['horas'];
            $valorHora = $row_orcamento['valorHora'];
            $valorOrcamento = $row_orcamento['valorOrcamento'];

            $sql_itens = "SELECT tituloItemOrcamento FROM orcamentoitens WHERE idOrcamento = $idOrcamento";
            $buscar_itens = mysqli_query($conexao, $sql_itens);

            $titulosItens = [];
            while ($row_itens = mysqli_fetch_array($buscar_itens, MYSQLI_ASSOC)) {
                $titulosItens[] = $row_itens["tituloItemOrcamento"];
            }
            $titulosItensOrcamento = implode('<br>', $titulosItens);

            $tituloEmail = '[' . $nomeCliente . '/' . $nomeOrcamentoStatus . '] Orçamento/ ' . $idOrcamento . ' ' . $tituloOrcamento;
            $corpoEmail = "
                <html>
                <head>
                    <title>$tituloEmail</title>
                </head>
                <body>
                    <p>Solicitante : $nomeSolicitante<br>
                    Status      : $nomeOrcamentoStatus</p>
                    
                    <p>$descricao</p>";

            if ($horas !== null && $valorHora !== null && $valorOrcamento !== null ) {
                $corpoEmail .= "
                        <p>Horas : $horas<br>
                        Valor Horas      : $valorHora<br>
                        Orçamento      : $valorOrcamento</p>
                        <br>";
            }

            if ($titulosItensOrcamento !== "") {
                $corpoEmail .= "
                        <p>Itens : <br>
                        <p>$titulosItensOrcamento</p>
                        <br>";
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
                    'email' => $emailSolicitante,
                    'nome' => $nomeSolicitante
                ),
            );

            $envio = emailEnviar(null, null, $arrayPara, $tituloEmail, $corpoEmail, 1);

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