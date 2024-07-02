<?php
// Helio 20022023 ajustado para chamar index.php no action
// Lucas 15022023 criado

include_once '../head.php';
include_once '../database/contratoStatus.php';
$contratoStatusTodos = buscaContratoStatus();


?>

<body class="bg-transparent">

<div class="container text-center card shadow" style="margin-top: 10px;">
   <div class="row">
		<div class="col card-header border-1">
			<div class="row">
				<div class="col-sm" style="text-align:left">
					<h3 class="col">Busca Contrato</h3>
				</div>
			</div>
		</div>
  </div> 

  <form action="index.php" method="post" style="margin-top: 20px; padding: 10px; text-align: left">


		<div class="row">
            
			

			<div class="col">
                <select class="form-control" name="idContratoStatus">
                    <?php
                        foreach ($contratoStatusTodos as $contratoStatus) {
                    ?>
                        <option value="<?php echo $contratoStatus['idContratoStatus'] ?>"><?php echo $contratoStatus['nomeContratoStatus']  ?></option>
                    <?php  } ?>
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