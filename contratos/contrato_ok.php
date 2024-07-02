<?php
include '../head.php';
?>


<div class="container" style="width: 400px">
	<center>
		<div id='certo' style="width: 200px; height: 200px"></div>
		<h3>Atualizado com sucesso</h3>
		<a href="../contratos/index.php" class="btn btn-sm btn-warning" style="color:#fff">Voltar</a>
	</center>
</div>


</div>

<script type="text/javascript">
	var svgContainer = document.getElementById('certo');
	var animItem = bodymovin.loadAnimation({
		wrapper: svgContainer,
		animType: 'svg',
		loop: true,
		autoplay: true,

		path: '/vendor/animacoes/certo.json'
	});
</script>