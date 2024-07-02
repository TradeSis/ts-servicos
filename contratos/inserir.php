<?php
// Lucas 13102023 novo padrao
// Lucas 20022023 adicionado o campo de Valor Contrato, linhas: 91 até 94;
// Lucas 10022023 Melhorado estrutura do script
// Lucas 01022023 - Acrescentado campos no form dataPrevisao e dataEntrega, e ajustado layout das colunas do form
// Lucas 01022023 - Retirado placeholder="Titulo do Contrato", linha 40;
// Lucas 01022023 - Ajustado label do form: idCliente, idContratoStatus, Titulo Contrato para Cliente, Status e Titulo;
// Lucas 01022023 - Removido o campo dataFechamento;
// Lucas 01022023 18:22


include '../header.php';
include '../database/contratoStatus.php';
include_once(ROOT . '/cadastros/database/clientes.php');
include '../database/contratotipos.php';
include_once(ROOT . '/cadastros/database/servicos.php');

$contratoStatusTodos = buscaContratoStatus();
$clientes = buscaClientes();
$servicos = buscaServicos();

$urlContratoTipo = $_GET["tipo"];
$contratoTipo = buscaContratoTipos($urlContratoTipo);
?>
<!doctype html>
<html lang="pt-BR">

<head>

    <?php include_once ROOT . "/vendor/head_css.php"; ?>
</head>



<body>

    <div class="container-fluid">

        <div class="row">
            <BR> <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
            <BR> <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Inserir <?php echo $contratoTipo['nomeContrato'] ?></h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
            </div>

            <div class="col-2 text-end">
                <a href="index.php?tipo=<?php echo $contratoTipo['idContratoTipo'] ?>" role="button" class="btn btn-primary"><i class="bi bi-arrow-left-square"></i></i>&#32;Voltar</a>
            </div>
        </div>


        <form action="../database/contratos.php?operacao=inserir" method="post">
            <div class="row mt-4">
                <div class="col-md-12">
                    <label class='form-label ts-label'>Titulo</label>
                    <input type="text" class="form-control ts-input" name="tituloContrato" required>
                    <input type="hidden" class="form-control ts-input" name="idContratoTipo" value="<?php echo $contratoTipo['idContratoTipo'] ?>">
                </div>
            </div>

            <div class="container-fluid p-0 mt-2">
                <div class="col">
                    <span class="tituloEditor">Descrição</span>
                </div>
                <!-- lucas 27022024 - id853 nova chamada editor quill -->
                <div id="ql-toolbarContratoInserir">
                    <?php include ROOT . "/sistema/quilljs//ql-toolbar-min.php"  ?>
                    <input type="file" id="anexarContratoInserir" class="custom-file-upload" name="nomeAnexo" onchange="uploadFileContratoInserir()" style=" display:none">
                    <label for="anexarContratoInserir">
                        <a class="btn p-0 ms-1"><i class="bi bi-paperclip"></i></a>
                    </label>
                </div>
                <div id="ql-editorContratoInserir" style="height:30vh !important">
                </div>
                <textarea style="display: none" id="quill-contratoInserir" name="descricao"></textarea>
            </div>

            <div class="row mt-2">
                <div class="col-md-4 form-group-select">
                    <label class="form-label ts-label">Status</label>
                    <select class="form-select ts-input" name="idContratoStatus">
                        <?php
                        foreach ($contratoStatusTodos as $contratoStatus) {
                        ?>
                            <option value="<?php echo $contratoStatus['idContratoStatus'] ?>"><?php echo $contratoStatus['nomeContratoStatus']  ?></option>
                        <?php  } ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label ts-label">Previsão</label>
                    <input type="date" class="form-control ts-input" name="dataPrevisao">
                </div>

                <div class="col-md-4">
                    <label class="form-label ts-label">Entrega</label>
                    <input type="date" class="form-control ts-input" name="dataEntrega">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-3 ">
                    <label class="form-label ts-label">Cliente</label>
                    <select class="form-select ts-input" name="idCliente">
                        <?php
                        foreach ($clientes as $cliente) { // ABRE o 
                        ?>
                            <option value="<?php echo $cliente['idCliente'] ?>"><?php echo $cliente['nomeCliente'] ?></option>
                        <?php  } ?> <!--FECHA while-->
                    </select>
                </div>
                
                <div class="col-md-3 ">
                    <label class="form-label ts-label">Serviço</label>
                    <select class="form-select ts-input" name="idServico" autocomplete="off">
                        <option value="<?php echo null ?>">
                            <?php echo "Selecione" ?>
                        </option>
                        <?php foreach ($servicos as $servico) { ?>
                            <option <?php if ($servico['idServico'] == $contratoTipo['idServicoPadrao']) echo "selected"; ?> value="<?php echo $servico['idServico'] ?>">
                                <?php echo $servico['nomeServico'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class='form-label ts-label'>Horas</label>
                    <input type="number" class="form-control ts-input" name="horas" autocomplete="off">
                </div>

                <div class="col-md-2">
                    <label class='form-label ts-label'>Valor Hora</label>
                    <input type="number" class="form-control ts-input" name="valorHora" autocomplete="off">
                </div>

                <div class="col-md-2">
                    <label class='form-label ts-label'>Valor Contrato</label>
                    <input type="number" class="form-control ts-input" name="valorContrato" autocomplete="off">
                </div>
            </div>

            <div class="col-md-12 mt-4">
                <div class="text-end mt-4">
                    <button type="submit" class="btn  btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Cadastrar</button>
                </div>
            </div>
        </form>

    </div>

    <!-- LOCAL PARA COLOCAR OS JS -->

    <?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <!-- QUILL editor -->

    <script>
    //lucas 27022024 - id853 nova chamada editor quill
    var quillContratoInserir = new Quill('#ql-editorContratoInserir', {
        modules: {
            toolbar: '#ql-toolbarContratoInserir'
        },
        placeholder: 'Digite o texto...',
        theme: 'snow'
    });

    quillContratoInserir.on('text-change', function() {
        $('#quill-contratoInserir').val(quillContratoInserir.container.firstChild.innerHTML);
    });

    async function uploadFileContratoInserir() {

        let endereco = '/tmp/';
        let formData = new FormData();
        var custombutton = document.getElementById("anexarContratoInserir");
        var arquivo = custombutton.files[0]["name"];

        formData.append("arquivo", custombutton.files[0]);
        formData.append("endereco", endereco);

        destino = endereco + arquivo;

        await fetch('/sistema/quilljs/quill-uploadFile.php', {
            method: "POST",
            body: formData
        });

        const range = this.quillContratoInserir.getSelection(true)

        this.quillContratoInserir.insertText(range.index, arquivo, 'user');
        this.quillContratoInserir.setSelection(range.index, arquivo.length);
        this.quillContratoInserir.theme.tooltip.edit('link', destino);
        this.quillContratoInserir.theme.tooltip.save();

        this.quillContratoInserir.setSelection(range.index + destino.length);

    }
</script>

    <!-- LOCAL PARA COLOCAR OS JS -FIM -->
</body>

</html>