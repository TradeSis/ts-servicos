<?php
// Lucas 13102023 novo padrao
// Lucas 20022023 alterado o type="text" para "number", linhas: 89, 95, 101
// Lucas 20022023 retirado disabled na linha 104
// Lucas 22022023 - ajustado buscaContratos parametros
// Lucas 1002023 Melhorado estrutura do script
// Lucas 31012023 - Alterado alguns campos do form: label"contrato" para "Titulo"
// Lucas 31012023 - Alterado "id" para "idContrato", linhas 13, 16, 26 e 52
// Lucas 31012023 20:55

include_once '../header.php';
include_once '../database/contratoStatus.php';
include_once(ROOT . '/cadastros/database/clientes.php');
include_once '../database/tarefas.php';
include_once(ROOT . '/cadastros/database/usuario.php');

$contratoStatusTodos = buscaContratoStatus();
$idCliente = $contrato["idCliente"];
$cliente = buscaClientes($idCliente);
$usuario = buscaUsuarios(null, $_SESSION['idLogin']);
?>
<!doctype html>
<html lang="pt-BR">

<head>

	<?php include_once ROOT . "/vendor/head_css.php"; ?>
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>

<body>

	<div class="container-fluid">

		<form action="../database/contratos.php?operacao=alterar" method="post">
			<div class="row mt-2">
				<div class="col-md-1">
					<label class='form-label ts-label'>ID</label>
					<input type="text" class="form-control ts-input" name="idContrato" value="<?php echo $contrato['idContrato'] ?>" disabled>
				</div>
				<div class="col-md-8">
					<label class='form-label ts-label'>Titulo</label>
					<input type="text" class="form-control ts-input" name="tituloContrato" value="<?php echo $contrato['tituloContrato'] ?>">
					<input type="hidden" class="form-control ts-input" name="idContrato" value="<?php echo $contrato['idContrato'] ?>">
					<input type="hidden" class="form-control ts-input" name="idContratoTipo" value="<?php echo $contrato['idContratoTipo'] ?>">
				</div>

				<div class="col-md-3">
					<label class="form-label ts-label">Cliente</label>
					<select class="form-select ts-input" name="idCliente" autocomplete="off" disabled>
						<option value="<?php echo $contrato['idCliente'] ?>"><?php echo $contrato['nomeCliente'] ?>
						</option>
						<option value="<?php echo $cliente['idCliente'] ?>"><?php echo $cliente['nomeCliente'] ?>
						</option>
					</select>
				</div>
			</div>


			<div class="container-fluid p-0 mt-3">
				<div class="col">
					<span class="tituloEditor">Descrição</span>
				</div>
				<div class="quill-textarea" style="height:300px!important"><?php echo $contrato['descricao'] ?></div>
				<textarea style="display: none" id="detail" name="descricao"><?php echo $contrato['descricao'] ?></textarea>
			</div>
			<div class="row mt-4">
				<div class="col-md-3">
					<label class="form-label ts-label">Status</label>
					<select class="form-select ts-input" name="idContratoStatus" autocomplete="off">
						<option value="<?php echo $contrato['idContratoStatus'] ?>"><?php echo $contrato['nomeContratoStatus'] ?></option>
						<?php
						foreach ($contratoStatusTodos as $contratoStatus) {
						?>
							<option value="<?php echo $contratoStatus['idContratoStatus'] ?>"><?php echo $contratoStatus['nomeContratoStatus'] ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="col-md-3">
					<label class="form-label ts-label">Abertura</label>
					<input type="text" class="form-control ts-input" name="dataAbertura" value="<?php echo date('d/m/Y H:i', strtotime($contrato['dataAbertura'])) ?>" disabled>
				</div>


				<div class="col-md-3">
					<label class="form-label ts-label">Previsao</label>
					<input type="date" class="form-control ts-input" name="dataPrevisao" value="<?php echo $contrato['dataPrevisao'] ?>">
				</div>

				<div class="col-md-3">
					<label class="form-label ts-label">Entrega</label>
					<input type="date" class="form-control ts-input" name="dataEntrega" value="<?php echo $contrato['dataEntrega'] ?>">
				</div>
			</div>


			<div class="row mt-4">
				<div class="col-md-3">
					<label class="form-label ts-label">Fechamento</label>
					<?php if ($contrato['dataFechamento'] == null) { ?>
						<input type="text" class="form-control ts-input" name="dataFechamento" value="<?php echo $contrato['dataFechamento'] = '00/00/0000 00:00' ?>" disabled>
					<?php } else { ?>
						<input type="text" class="form-control ts-input" name="dataFechamento" value="<?php echo date('d/m/Y H:i', strtotime($contrato['dataFechamento'])) ?>" disabled>
					<?php } ?>
				</div>

				<div class="col-md-3">
					<label class="form-label ts-label">Horas</label>
					<input type="number" class="form-control ts-input" name="horas" value="<?php echo $contrato['horas'] ?>">

				</div>

				<div class="col-md-3">
					<label class="form-label ts-label">Valor Hora</label>
					<input type="number" class="form-control ts-input" name="valorHora" value="<?php echo $contrato['valorHora'] ?>">
				</div>

				<div class="col-md-3">
					<label class="form-label ts-label">Valor Contrato</label>
					<input type="number" class="form-control ts-input" name="valorContrato" value="<?php echo $contrato['valorContrato'] ?>">
				</div>

			</div>
			<div class="row">
				<div class="text-end mt-4">
					<button type="submit" id="botao" class="btn btn-success"><i class="bi bi-sd-card-fill"></i>&#32;Salvar</button>
				</div>
			</div>
		</form>

	</div>

	<!-- LOCAL PARA COLOCAR OS JS -->

	<?php include_once ROOT . "/vendor/footer_js.php"; ?>
    <!-- QUILL editor -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	<script>
		var quill = new Quill('.quill-textarea', {
			theme: 'snow',
			modules: {
				toolbar: [
					['bold', 'italic', 'underline', 'strike'],
					['blockquote'],
					[{
						'list': 'ordered'
					}, {
						'list': 'bullet'
					}],
					[{
						'indent': '-1'
					}, {
						'indent': '+1'
					}],
					[{
						'direction': 'rtl'
					}],
					[{
						'size': ['small', false, 'large', 'huge']
					}],
					[{
						'header': [1, 2, 3, 4, 5, 6, false]
					}],
					['link', 'image', 'video', 'formula'],
					[{
						'color': []
					}, {
						'background': []
					}],
					[{
						'font': []
					}],
					[{
						'align': []
					}],
				]
			}
		});

		quill.on('text-change', function(delta, oldDelta, source) {
			$('#detail').val(quill.container.firstChild.innerHTML);
		});
	</script>

	<!-- LOCAL PARA COLOCAR OS JS -FIM -->

</body>

</html>