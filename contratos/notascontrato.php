  <div class="container-fluid">

        <div class="row">
            <!-- MENSAGENS/ALERTAS -->
        </div>
        <div class="row">
             <!-- BOTOES AUXILIARES -->
        </div>
        <div class="row align-items-center"> <!-- LINHA SUPERIOR A TABLE -->
            <div class="col-3 text-start">
                <!-- TITULO -->
                <h2 class="ts-tituloPrincipal">Notas</h2>
            </div>
            <div class="col-7">
                <!-- FILTROS -->
                
            </div>

            <div class="col-2 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#inserirModalNotas"><i class="bi bi-plus-square"></i>&nbsp Novo</button>
            </div>
        </div>

            <div class="table mt-2 ts-divTabela">
                <table class="table table-hover table-sm align-middle">
                    <thead class="ts-headertabelafixo">
                        <tr>
                            <th>Nota</th>
                            <th>Tomador</th>
                            <th>Competencia</th>
                            <th>Emissao</th>
                            <th>Serie</th>
                            <th>Número</th>
                            <th>serieDPS</th>
                            <th>numeroDPS</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th colspan="2">Ação</th>
                        </tr>
                    </thead>

                    <tbody id='dados' class="fonteCorpo">

                    </tbody>
                </table>
            </div>
       
    </div>

  <!-- LOCAL PARA COLOCAR OS JS -->

  <?php include_once ROOT. "/vendor/footer_js.php";?>

    <script>
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: '<?php echo URLROOT ?>/notas/database/notasservico.php?operacao=buscarnotascontrato',
            beforeSend: function () {
                $("#dados").html("Carregando...");
            },
            data: {
                idContrato: <?php echo $idContrato ?>
            },
            success: function (msg) {
                
                var json = JSON.parse(msg);
                var linha = "";
                for (var $i = 0; $i < json.length; $i++) {
                    var object = json[$i];
                    
                    function formatDate(dateString) {
                        if (dateString !== null && !isNaN(new Date(dateString))) {
                            var date = new Date(dateString);
                            var day = date.getUTCDate().toString().padStart(2, '0');
                            var month = (date.getUTCMonth() + 1).toString().padStart(2, '0');
                            var year = date.getUTCFullYear().toString().padStart(4, '0');
                            return day + "/" + month + "/" + year;
                        }
                        return "";
                    }
                    
                    var dataCompetenciaFormatada = formatDate(object.dataCompetencia);
                    var dataEmissaoFormatada = formatDate(object.dataEmissao);
                    
                    if (object.statusNota == 0) {
                        var novoStatusNota = "Aberto";
                    }
                    if (object.statusNota == 1) {
                        var novoStatusNota = "Processando";
                    }
                    if (object.statusNota == 2) {
                        var novoStatusNota = "Autorizada";
                    }
                    if (object.statusNota == 3) {
                        var novoStatusNota = "Negada";
                    }
                    if (object.statusNota == 4) {
                        var novoStatusNota = "Cancelada";
                    }
                    
                    linha += "<tr>";
                    linha += "<td>" + object.idNotaServico + "</td>";
                    linha += "<td>" + object.nomePessoaTomador + "</td>";
                    linha += "<td>" + dataCompetenciaFormatada + "</td>";
                    linha += "<td>" + dataEmissaoFormatada + "</td>";
                    linha += "<td>" + object.serieNota + "</td>";
                    linha += "<td>" + object.numeroNota + "</td>";
                    linha += "<td>" + object.serieDPS + "</td>";
                    linha += "<td>" + object.numeroDPS + "</td>";
                    linha += "<td>" + object.valorNota + "</td>";
                    linha += "<td>" + novoStatusNota + "</td>";
                    linha += "<td>";
                    if (object.statusNota == 0 || object.statusNota == 3) {
                        linha += "<button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#alterarModalNotas' data-idNotaServico='" + object.idNotaServico + "'><i class='bi bi-pencil-square'></i></button>";
                    }
                    if (object.statusNota == 1) {
                        linha += "<button type='button' class='btn btn-success btn-sm' id='consulta' data-idNotaServico='" + object.idNotaServico + "' title='Atualizar'><i class='bi bi-arrow-clockwise'></i></button>";
                    }
                    if (object.statusNota == 2) {
                        linha += "<button type='button' class='btn btn-primary btn-sm' id='xml' data-idProvedor='" + object.idProvedor + "' title='Visualizar XML'><i class='bi bi-filetype-xml'></i></button>";
                        linha += "<button type='button' class='btn btn-info btn-sm' id='pdf' data-idProvedor='" + object.idProvedor + "' title='Visualizar PDF'><i class='bi bi-filetype-pdf'></i></button>";
                    }
                    linha += "</td>";                    linha += "</tr>";
                }
                $("#dados").html(linha);
            }
        });
        
       

        $(document).on('click', 'button[data-bs-target="#alterarModalNotas"]', function() {
            var idNotaServico = $(this).attr("data-idNotaServico");
            //alert(idNotaServico)
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?php echo URLROOT ?>/notas/database/notasservico.php?operacao=buscar',
                data: {
                    idNotaServico: idNotaServico
                },
                success: function(data) {
                    condicaoalterar.root.innerHTML = data.condicao;
                    descricaoServicoalterar.root.innerHTML = data.descricaoServico;
                    $('#idNotaServico').val(data.idNotaServico);
                    $('#idPessoaTomador').val(data.idPessoaTomador);
                    $('#dataCompetencia').val(data.dataCompetencia);
                    $('#valorNota').val(data.valorNota);
                    $('#codMunicipio').val(data.codMunicipio);
                    $('#alterarModal').modal('show');
                }
            });
        });

        $('.btnAbre').click(function() {
            $('.menuFiltros').toggleClass('mostra');
            $('.diviFrame').toggleClass('mostra');
        });


      

        var inserirModal = document.getElementById("inserirModal");

        var inserirBtn = document.querySelector("button[data-bs-target='#inserirModal']");

        inserirBtn.onclick = function() {
            inserirModal.style.display = "block";
        };

        window.onclick = function(event) {
            if (event.target == inserirModal) {
                inserirModal.style.display = "none";
            }
        };
        
    </script>

    <script>
        $(document).ready(function() {
            $("#inserirFormNotaContrato").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "<?php echo URLROOT?>/notas/database/notasservico.php?operacao=inserir_notascontrato",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            });

          $("#alterarFormNotaContrato").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "<?php echo URLROOT?>/notas/database/notasservico.php?operacao=alterar",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: refreshPage,
                });
            }); 
            
         function refreshPage(tab, idContrato) {
            window.location.reload();
            var url = window.location.href.split('?')[0];
            var newUrl = url + '?id=' + tab + '&&idContrato=' + idContrato;
            window.location.href = newUrl;
            
        } 
            
        });
    </script>

<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>