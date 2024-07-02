<?php
//Lucas 13102023 novo padrao
// Gabriel 22092023 id 544 Demandas - Botão Voltar
// Lucas 22032023 ajustado função do botão de limpar
// Lucas 22032023 adicionado busca por barra de pesquisa, funcionado com pressionamento do Enter
// Lucas 15032023 alterado select de idContratoStatus para acionar uma função js, botão "buscar" foi removido, 
//  alterado botão de limpar para usar função onclick="buscar(null)"
// Lucas 15032023 Modifica a tabela ser constrida com Javascript
// Lucas 02032023 Adicionado botão de pesquisa dentro de uma div, linha 273 
// Lucas 02032023 Adicionado height:65px, nos cards, para manter a altura, mesmo sem valor informado dentro dos cards. linhas: 55, 80, 105, 130, 157 e 182 
// Lucas 22022023 Adicionado dois modelos de teste de entrada "busca" de parametros. linhas 187 até 244 [Escolher qual melhor a ser usado]
// Lucas 22022023 Corrigido Responsividade de tamanho da tabela "formato de desktop"
// Lucas 22022023 Modificado formato dos cards
// Lucas 10022023 Corrigido estuta da tabela, coluna ação
// Lucas 10022023 Melhorado estrutura do script - parte da tabela
// Lucas 01022023 - modificação nos cads - Funciona, *melhorar o slq*
// Lucas 01022023 - Removido os campos da tabela;
// Lucas 01022023 - Adicionado campos da tabela ID, dataPrevisao e dataEntrega, alterado nome de contrato para Titulo;
// Lucas 01022023 - Adicionado os campos dataPrevisao e dataEntrega;
// Lucas 31012023 - Alterado "id" para "idContrato", linha 222;
// Lucas 31012023 - Alterado nome dos botões Alterar e Editar para Inserir e alterar;
// Lucas 31012023 20:53


include_once(__DIR__ . '/../header.php');
include_once(__DIR__ . '/../database/contratos.php');
include_once(__DIR__ . '/../database/contratoStatus.php');
include_once(ROOT . '/cadastros/database/clientes.php');
include '../database/contratotipos.php';

$urlContratoTipo = $_GET["tipo"];
$contratoTipo = buscaContratoTipos($urlContratoTipo);

$clientes = buscaClientes();
$contratoStatusTodos = buscaContratoStatus();
$cards = buscaCardsContrato($urlContratoTipo);


$idCliente = null;
$idContratoStatus = null; 
$statusContrato = CONTRATOSTATUS_DESENVOLVIMENTO;
if (isset($_SESSION['filtro_contrato'])) {
    $filtroEntrada = $_SESSION['filtro_contrato'];
    $idCliente = $filtroEntrada['idCliente'];
    $idContratoStatus = $filtroEntrada['idContratoStatus'];
    $statusContrato = $filtroEntrada['statusContrato'];
}
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>

</head>



<body>
    <div class="container-fluid">

        <div class="row ">
            <!-- <BR> MENSAGENS/ALERTAS -->
        </div>

        <div class="row row-cols-1 row-cols-md-6 pt-2">
            <!-- BOTOES AUXILIARES -->
            <div class="col">
                <div class="ts-cardColor1 card border-left-success ts-shadowOff ts-cardsTotais p-1">
                <div class="text-xs fw-bold text-info">ORÇAMENTO</div>
                <div class="h5 mb-0  text-gray-800 ml-1">
                    <?php echo "(" . $cards['totalOrcamento'] . ") "; if ("$logado" == "helio") { echo "R$ " . number_format((float)$cards['valorOrcamento'], 2, ',', '');}?>
                </div>
                <button class="ts-cardLink" onClick="clickCard(this.value)" value="<?php echo CONTRATOSTATUS_ORCAMENTO; ?>" id="<?php echo CONTRATOSTATUS_ORCAMENTO; ?>"></button>
                </div>
            </div>

            <div class="col">
                <div class="ts-cardColor2 ts-cardColor-active card border-left-success  ts-cardsTotais p-1">
                <div class="text-xs fw-bold text-success">DESENVOLVIMENTO</div>
                <div class="h5 mb-0  text-gray-800 ml-1">
                    <?php echo "(" . $cards['totalDesenvolvimento'] . ") "; if ("$logado" == "helio") { echo "R$ " . number_format((float)$cards['valorDesenvolvimento'], 2, ',', '');}?>
                </div>
                <button class="ts-cardLink" onClick="clickCard(this.value)" value="<?php echo CONTRATOSTATUS_DESENVOLVIMENTO; ?>" id="<?php echo CONTRATOSTATUS_DESENVOLVIMENTO; ?>"></button>
                </div>
            </div>

            <div class="col">
                <div class="ts-cardColor3 card border-left-success ts-shadowOff ts-cardsTotais p-1">
                <div class="text-xs fw-bold text-success">FATURAMENTO</div>
                <div class="h5 mb-0  text-gray-800 ml-1">
                    <?php echo "(" . $cards['totalFaturamento'] . ") "; if ("$logado" == "helio") { echo "R$ " . number_format((float)$cards['valorFaturamento'], 2, ',', '');}?>
                </div>
                <button class="ts-cardLink" onClick="clickCard(this.value)" value="<?php echo CONTRATOSTATUS_FATURAMENTO; ?>" id="<?php echo CONTRATOSTATUS_FATURAMENTO; ?>"></button>
                </div>
            </div>

            <div class="col">
                <div class="ts-cardColor4 card border-left-success ts-shadowOff ts-cardsTotais p-1">
                <div class="text-xs fw-bold text-warning">RECEBIMENTO</div>
                <div class="h5 mb-0  text-gray-800 ml-1">
                    <?php echo "(" . $cards['totalRecebimento'] . ") "; if ("$logado" == "helio") { echo "R$ " . number_format((float)$cards['valorRecebimento'], 2, ',', '');}?>
                </div>
                <button class="ts-cardLink" onClick="clickCard(this.value)" value="<?php echo CONTRATOSTATUS_RECEBIMENTO; ?>" id="<?php echo CONTRATOSTATUS_RECEBIMENTO; ?>"></button>
                </div>
            </div>

            <div class="col">
                <div class="ts-cardColor5 card border-left-success ts-shadowOff ts-cardsTotais p-1">
                <div class="text-xs fw-bold text-danger pl-4">TOTAL ATIVO</div>
                <div class="h5 mb-0  text-gray-800 ml-1">
                    <?php echo "(" . $cards['totalAtivo'] . ") "; if ("$logado" == "helio") { echo "R$ " . number_format((float)$cards['valorAtivo'], 2, ',', '');}?>
                </div>
                <button class="ts-cardLink" onClick="clickCard(this.value)" value="<?php echo CONTRATOSTATUS_ATIVO; ?>" id="<?php echo CONTRATOSTATUS_ATIVO; ?>"></button>
                </div>
            </div>

            <div class="col">
                <div class="ts-cardColor6 card border-left-success ts-shadowOff ts-cardsTotais p-1">
                <div class="text-xs fw-bold text-danger pl-4">ENCERRADOS</div>
                <div class="h5 mb-0  text-gray-800 ml-1">
                    <?php echo "(" . $cards['totalEncerrados'] . ") "; if ("$logado" == "helio") { echo "R$ " . number_format((float)$cards['valorEncerrados'], 2, ',', '');}?>
                </div>
                <button class="ts-cardLink" onClick="clickCard(this.value)" value="<?php echo CONTRATOSTATUS_ENCERRADOS; ?>" id="<?php echo CONTRATOSTATUS_ENCERRADOS; ?>"></button>
                </div>
            </div>

        </div> <!-- fim- BOTOES AUXILIARES -->

        <div class="row d-flex align-items-center justify-content-center mt-1 pt-1 ">

            <div class="col-2 col-lg-1 order-lg-1">
                <button class="btn btn-outline-secondary ts-btnFiltros" type="button"><i class="bi bi-funnel"></i></button>
            </div>

            <div class="col-4 col-lg-3 order-lg-2">
                <h2 class="ts-tituloPrincipal"><?php echo $contratoTipo['nomeContrato'] ?></h2>
                <span>Filtro Aplicado</span>
            </div>

            <div class="col-6 col-lg-2 order-lg-3">
                <!-- BOTÂO OPCIONAL -->
            </div>

            <div class="col-12 col-lg-6 order-lg-4">
                <div class="input-group">
                    <input type="text" class="form-control ts-input" id="buscaContrato" placeholder="Buscar por id ou titulo">
                    <button class="btn btn-primary rounded" type="button" id="buscar"><i class="bi bi-search"></i></button>
                    <a href="inserir.php?tipo=<?php echo $contratoTipo['idContratoTipo'] ?>" role="button" class="ms-4 btn btn-success ml-4"><i class="bi bi-plus-square"></i>&nbsp Novo</a>
                </div>
            </div>

        </div>

        <!-- MENUFILTROS -->
        <div class="ts-menuFiltros mt-2 px-3">
        <label>Filtrar por:</label>

        <div class="ls-label col-sm-12"> <!-- ABERTO/FECHADO -->
            <form class="d-flex" action="" method="post">
                <select class="form-control" name="statusContrato" id="FiltroStatusContrato" onchange="mudarSelect(this.value)">
                    <option <?php if ($statusContrato == CONTRATOSTATUS_ORCAMENTO) echo "selected"; ?> value="<?php echo CONTRATOSTATUS_ORCAMENTO; ?>">Orçamento</option>
                    <option <?php if ($statusContrato == CONTRATOSTATUS_DESENVOLVIMENTO) echo "selected"; ?> value="<?php echo CONTRATOSTATUS_DESENVOLVIMENTO; ?>">Desenvolvimento</option>
                    <option <?php if ($statusContrato == CONTRATOSTATUS_FATURAMENTO) echo "selected"; ?> value="<?php echo CONTRATOSTATUS_FATURAMENTO; ?>">Faturamento</option>
                    <option <?php if ($statusContrato == CONTRATOSTATUS_RECEBIMENTO) echo "selected"; ?> value="<?php echo CONTRATOSTATUS_RECEBIMENTO; ?>">Recebimento</option>
                    <option <?php if ($statusContrato == CONTRATOSTATUS_ATIVO) echo "selected"; ?> value="<?php echo CONTRATOSTATUS_ATIVO; ?>">Ativo</option>
                    <option <?php if ($statusContrato == CONTRATOSTATUS_ENCERRADOS) echo "selected"; ?> value="<?php echo CONTRATOSTATUS_ENCERRADOS; ?>">Encerrados</option>
                </select>
            </form>
        </div>

        <div class="col-sm text-end mt-2">
            <a onClick="limpar()" role=" button" class="btn btn-sm bg-info text-white">Limpar</a>
        </div>
        </div>

        <div class="table mt-2 ts-divTabela70 ts-tableFiltros">
            <table class="table table-sm table-hover">
                <thead class="ts-headertabelafixo">
                    <tr class="ts-headerTabelaLinhaCima">
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Titulo</th>
                        <th>Status</th>
                        <th>Previsão</th>
                        <th>Entrega</th>
                        <th>Atualização</th>
                        <th>Fechamento</th>
                        <th>Horas</th>
                        <th>hora</th>
                        <th>Contrato</th>
                        <th colspan="2">Ação</th>
                    </tr>

                    <tr class="ts-headerTabelaLinhaBaixo">
                        <th></th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idCliente" id="FiltroClientes">
                                    <option value="<?php echo null ?>"><?php echo "Selecione" ?></option>
                                    <?php
                                    foreach ($clientes as $cliente) {
                                    ?>
                                        <option <?php
                                                if ($cliente['idCliente'] == $idCliente) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $cliente['idCliente'] ?>"><?php echo $cliente['nomeCliente'] ?></option>
                                    <?php } ?>
                                </select>
                            </form>
                        </th>
                        <th></th>
                        <th>
                            <form action="" method="post">
                                <select class="form-select ts-input ts-selectFiltrosHeaderTabela" name="idContratoStatus" id="FiltroContratoStatus">
                                    <option value="<?php echo null ?>"><?php echo "Status"  ?></option>
                                    <?php

                                    foreach ($contratoStatusTodos as $contratoStatus) {
                                    ?>
                                        <option <?php
                                                if ($contratoStatus['idContratoStatus'] == $idContratoStatus) {
                                                    echo "selected";
                                                }
                                                ?> value="<?php echo $contratoStatus['idContratoStatus'] ?>"><?php echo $contratoStatus['nomeContratoStatus']  ?></option>
                                    <?php  } ?>
                                </select>

                            </form>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody id='dados' class="fonteCorpo">

                </tbody>
            </table>
        </div>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <!-- script para menu de filtros -->
    <script src="<?php echo URLROOT ?>/sistema/js/filtroTabela.js"></script>
    <!-- Cards funcionado como botões -->
    <script src="../js/contrato_cards.js"></script>

    <script>
        var urlContratoTipo = '<?php echo $urlContratoTipo ?>';
        buscar($("#FiltroClientes").val(), $("#FiltroContratoStatus").val(), $("#buscaContrato").val(), $("#FiltroStatusContrato").val());

        function limpar() {
            buscar(null, null, null, null);
        }

        function clickCard(statusContrato) {
            buscar($("#FiltroClientes").val(), $("#FiltroContratoStatus").val(), $("#buscaContrato").val(),statusContrato)
            }

        function buscar(idCliente, idContratoStatus, buscaContrato, statusContrato) {

            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: '<?php echo URLROOT ?>/services/database/contratos.php?operacao=filtrar',
                beforeSend: function() {
                    $("#dados").html("Carregando...");
                },
                data: {
                    idCliente: idCliente,
                    idContratoStatus: idContratoStatus,
                    buscaContrato: buscaContrato,
                    urlContratoTipo: urlContratoTipo,
                    statusContrato: statusContrato
                },


                success: function(msg) {
                    //alert("segundo alert: " + msg);
                    var json = JSON.parse(msg);
                    //alert("terceiro alert: " + JSON.stringify(json));
                    /* alert(JSON.stringify(msg)); */
                    /* $("#dados").html(msg); */
                    console.log(msg);

                    var linha = "";
                    // Loop over each object
                    for (var $i = 0; $i < json.length; $i++) {
                        var object = json[$i];
                        //dataAtualização
                        if (object.dataAtualizacao == "0000-00-00 00:00:00") {
                            var dataAtualizacaoFormatada = "<p>---</p>";
                        } else {
                            var dataAtualizacao = new Date(object.dataAtualizacao);
                            dataAtualizacaoFormatada = dataAtualizacao.toLocaleDateString("pt-BR") + " " + dataAtualizacao.toLocaleTimeString("pt-BR");
                        }

                        //dataFechamento
                        if (object.dataFechamento == null) {
                            var dataFechamentoFormatada = "<p>---</p>";
                        } else {
                            var dataFechamento = new Date(object.dataFechamento);
                            dataFechamentoFormatada = dataFechamento.toLocaleDateString("pt-BR") + " " + dataFechamento.toLocaleTimeString("pt-BR");
                        }

                        //dataPrevisao
                        if (object.dataPrevisao == "0000-00-00") {
                            var dataPrevisaoFormatada = "<p>---</p>";
                        } else {
                            var dataPrevisao = new Date(object.dataPrevisao);
                            dataPrevisaoFormatada = (`${dataPrevisao.getUTCDate().toString().padStart(2, '0')}/${(dataPrevisao.getUTCMonth()+1).toString().padStart(2, '0')}/${dataPrevisao.getUTCFullYear()}`);
                        }

                        //dataEntrega
                        if (object.dataEntrega == "0000-00-00") {
                            var dataEntregaFormatada = "<p>---</p>";
                        } else {
                            var dataEntrega = new Date(object.dataEntrega);
                            dataEntregaFormatada = (`${dataEntrega.getUTCDate().toString().padStart(2, '0')}/${(dataEntrega.getUTCMonth()+1).toString().padStart(2, '0')}/${dataEntrega.getUTCFullYear()}`);
                        }


                        // alert("quarto alert: " + JSON.stringify(object))
                        /*  alert(object); */
                        linha = linha + "<tr>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + object.idContrato + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + object.nomeCliente + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + object.tituloContrato + "</td>";
                        linha = linha + "<td class='" + object.nomeContratoStatus + "' data-status='Finalizado' >" + object.nomeContratoStatus + " </td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + dataPrevisaoFormatada + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + dataEntregaFormatada + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + dataAtualizacaoFormatada + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + dataFechamentoFormatada + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + object.horas + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + object.valorHora + "</td>";
                        linha = linha + "<td class='ts-click' data-idContrato='" + object.idContrato + "'>" + object.valorContrato + "</td>";
                        
                        linha += "<td>"; 
                        linha += "<div class='btn-group dropstart'><button type='button' class='btn' data-toggle='tooltip' data-placement='left' title='Opções' data-bs-toggle='dropdown' " +
                        " aria-expanded='false' style='box-shadow:none'><i class='bi bi-three-dots-vertical'></i></button><ul class='dropdown-menu'>"

                        linha += "<li class='ms-1 me-1 mt-1'><a class='btn btn-warning btn-sm w-100 text-start' href='visualizar.php?idContrato=" + object.idContrato + 
                        "' role='button' id='visualizarContratoButton'><i class='bi bi-pencil-square'></i> Alterar</a></li>";

                        linha += "</tr>";
                        linha +="</ul></div>"
                        linha += "</td>";
                        
                        
                        linha = linha + "</tr>";
                    }

                    //alert(linha);
                    $("#dados").html(linha);


                },
                error: function(e) {
                    alert('Erro: ' + JSON.stringify(e));

                    return null;
                }
            });

        }

        $("#FiltroClientes").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroContratoStatus").val(), $("#buscaContrato").val(), $("#FiltroStatusContrato").val());
        })

        $("#FiltroContratoStatus").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroContratoStatus").val(), $("#buscaContrato").val(), $("#FiltroStatusContrato").val());
        })

        $("#buscar").click(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroContratoStatus").val(), $("#buscaContrato").val(), $("#FiltroStatusContrato").val());
        })

        $("#FiltroStatusContrato").change(function() {
            buscar($("#FiltroClientes").val(), $("#FiltroContratoStatus").val(), $("#buscaContrato").val(), $("#FiltroStatusContrato").val());
        })

        //Gabriel 22092023 id544 trocado setcookie por httpRequest enviado para gravar origem em session//ajax
        $(document).on('click', '#visualizarContratoButton', function() {
            var urlContratoTipo = '?tipo=<?php echo $urlContratoTipo ?>';
            var currentPath = window.location.pathname + urlContratoTipo;
            $.ajax({
                type: 'POST',
                url: '../database/demanda.php?operacao=origem',
                data: {
                    origem: currentPath
                },
                success: function(response) {
                    console.log('Session variable set successfully.');
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                }
            });
        });

        document.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                buscar($("#FiltroClientes").val(), $("#FiltroContratoStatus").val(), $("#buscaContrato").val(), $("#FiltroStatusContrato").val());
            }
        });

        $(document).on('click', '.ts-click', function() {
        	window.location.href='visualizar.php?idContrato=' + $(this).attr('data-idContrato');
    	});

    </script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>