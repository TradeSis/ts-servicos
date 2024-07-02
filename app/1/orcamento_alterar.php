<?php
// Gabriel 26022024 criacao
//echo "-ENTRADA->".json_encode($jsonEntrada)."\n";

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "orcamento_alterar";
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
    $tituloOrcamento = isset($jsonEntrada['tituloOrcamento']) && $jsonEntrada['tituloOrcamento'] !== "" ? "'" . $jsonEntrada['tituloOrcamento'] . "'" : "NULL";
    $descricao = isset($jsonEntrada['descricao']) && $jsonEntrada['descricao'] !== "" ? "'" . $jsonEntrada['descricao'] . "'" : "NULL";
    $idCliente = isset($jsonEntrada['idCliente']) && $jsonEntrada['idCliente'] !== "" ? "'" . $jsonEntrada['idCliente'] . "'" : "NULL";
    $statusOrcamento = isset($jsonEntrada['statusOrcamento']) && $jsonEntrada['statusOrcamento'] !== "" ? "'" . $jsonEntrada['statusOrcamento'] . "'" : "NULL";
    $horas = isset($jsonEntrada['horas']) && $jsonEntrada['horas'] !== "" ? $jsonEntrada['horas'] : "NULL";
    $valorHora = isset($jsonEntrada['valorHora']) && $jsonEntrada['valorHora'] !== "" ? $jsonEntrada['valorHora'] : "NULL";
    $valorOrcamento = isset($jsonEntrada['valorOrcamento']) && $jsonEntrada['valorOrcamento'] !== "" ? $jsonEntrada['valorOrcamento'] : "NULL";
    $idServico = isset($jsonEntrada['idServico']) && $jsonEntrada['idServico'] !== "" ? $jsonEntrada['idServico'] : "NULL";
    

    if (($horas == 0) && ($valorHora == 0.00)) {
        $valorOrcamento = 0;
    } else {
        if (($horas !== "NULL") && ($valorOrcamento !== "NULL")) {
            $valorHora = $valorOrcamento / $horas;
        } else {
            if (($horas !== "NULL") && ($valorHora !== "NULL")) {
                $valorOrcamento = $horas * $valorHora;
            }
        }
    }


    $sql_status = "SELECT orcamento.statusOrcamento FROM orcamento WHERE idOrcamento = $idOrcamento";
    $buscar_status = mysqli_query($conexao, $sql_status);
    $row_status = mysqli_fetch_array($buscar_status, MYSQLI_ASSOC);
    $SQLstatusOrcamento = $row_status["statusOrcamento"];
    


    $sql = "UPDATE orcamento SET tituloOrcamento=$tituloOrcamento, descricao=$descricao, idCliente=$idCliente,
                horas=$horas, valorHora=$valorHora, valorOrcamento=$valorOrcamento, statusOrcamento=$statusOrcamento, idServico=$idServico WHERE idOrcamento = $idOrcamento";




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
        
            if($jsonEntrada['statusOrcamento'] !== $SQLstatusOrcamento) {

                $sql_orcamento = "SELECT orcamento.tituloOrcamento, orcamento.descricao, cliente.nomeCliente, orcamentostatus.nomeOrcamentoStatus, 
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

                $sql_itens = "SELECT tituloItemOrcamento FROM orcamentoitens WHERE idOrcamento = $idOrcamento";
                $buscar_itens = mysqli_query($conexao, $sql_itens);

                $titulosItens = [];
                while ($row_itens = mysqli_fetch_array($buscar_itens, MYSQLI_ASSOC)) {
                    $titulosItens[] = $row_itens["tituloItemOrcamento"];
                }
                $titulosItensOrcamento = implode('<br>', $titulosItens);

                $tituloEmail = '[' . $nomeCliente . '/Status] Orçamento/ ' . $idOrcamento .' ' . $tituloOrcamento;
                $corpoEmail = "
                <html>
                <head>
                    <title>$tituloEmail</title>
                </head>
                <body>
                    <p>Solicitante : $nomeSolicitante<br>
                    Status      : $nomeOrcamentoStatus</p>
                    
                    <p>$descricao</p>";
                    
                    if ($titulosItensOrcamento !== "") {
                        $corpoEmail .= "
                        <p>Itens : <br>
                        <p>$titulosItensOrcamento</p>";
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
    
                $envio = emailEnviar(null,null,$arrayPara,$tituloEmail,$corpoEmail,1);
             
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
