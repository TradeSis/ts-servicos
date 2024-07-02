<?php
// helio 01022023 alterado para include_once
// helio 26012023 16:16

include_once '../head.php';
?>


<div class="container" style="width: 400px">
    <center>
        <div id='certo' style="width: 200px; height: 200px"></div>
        <h3>Atualizado com sucesso</h3>
            <a href="../demandas/index.php" class="btn btn-sm btn-warning" style="color:#fff">Voltar</a>
        </div>
    </center>

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