<?php
// Helio 20022023 ajustado para chamar index.php no action
// Lucas 18022023 criado

include_once '../head.php';
include_once '../database/tipostatus.php';
$tiposstatus = buscaTipoStatus();


?>

<body class="bg-transparent">

<div class="container text-center card shadow" style="margin-top: 10px;">
   <div class="row">
		<div class="col card-header border-1">
			<div class="row">
				<div class="col-sm" style="text-align:left">
					<h3 class="col">Busca Demanda</h3>
				</div>
			</div>
		</div>
  </div> 

  <form action="index.php" method="post" style="margin-top: 20px; padding: 10px; text-align: left">


		<div class="row">
            
			

			<div class="col">
                <select class="form-control" name="idTipoStatus">
                    <?php
                        foreach ($tiposstatus as $tipostatus) {
                    ?>
                        <option value="<?php echo $tipostatus['idTipoStatus'] ?>"><?php echo $tipostatus['nomeTipoStatus'] ?></option>
                    <?php } ?>
                </select>
			</div> 



			<div class="col-12" style="text-align: right; padding-top: 20px">
				<button type="submit" id="botao" class="btn btn-sm btn-success">Buscar</button>
			</div>

			
		</div>

    </form>



	

</div><!-- container -->


</body>
</html>