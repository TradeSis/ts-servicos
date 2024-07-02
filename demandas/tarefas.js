// Modal stop
$(document).on('click', 'button[data-bs-target="#stopmodal"]', function () {
  var idTarefa = $(this).attr("data-id");
  var idDemanda = $(this).attr("data-demanda");
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: '../database/tarefas.php?operacao=buscar',
    data: {
      idTarefa: idTarefa
    },
    success: function (data) {

      $('#stopmodal_idTarefa').val(data.idTarefa);
      $('#stopmodal_idDemanda').val(data.idDemanda);
      $('#stopmodal_idCliente').val(data.idCliente);
      $('#stopmodal').modal('show');
    },
    error: function (msg) {
      alert(JSON.stringify(msg));
    }
  });
});


// Botão Clonar
$(document).on('click', 'button.clonarButton', function () {
  var idTarefa = $(this).data("idtarefa"); 
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: '../database/tarefas.php?operacao=buscar',
    data: {
      idTarefa: idTarefa
    },
    success: function (data) {
      $('#clonartitulo').val(data.tituloTarefa);
      $('#clonaridCliente').val(data.idCliente);
      $('#clonaridDemanda').val(data.idDemanda);
      $('#clonaridAtendente').val(data.idAtendente);
      $('#clonaridTipoOcorrencia').val(data.idTipoOcorrencia);
      $('#clonartipoStatusDemanda').val(data.idTipoStatus);
      $('#clonardescricao').val(data.descricao);

      //alert(data.tituloTarefa)
      $('#inserirModal').modal('show');
    }
  });
});
