<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript">
function abreComentario( codigo ){
	document.getElementById("comente"+codigo).style.display = 'inline';
}
function fechaComentario( codigo ){
	document.getElementById("comente"+codigo).style.display = 'none';
	document.getElementById("comentario").value = '';
}
</script>
</head>
<body>

<div id="geral">
	
    <!-- Topo -->
	<?php include_once("includes/topo.php"); ?>
	
    <!-- conteudo [begin] -->
    <div id="conteudo">
    	
        <!-- lateral esquerda -->
		<?php include_once("includes/lateral-esquerda.php"); ?>
        
		<!-- centro [begin] -->
        <div id="centro">
        	
                    
        </div>
        <!-- centro [end] -->
		
        <!-- lateral direita [begin] -->
        <?php include_once("includes/lateral-direita.php"); ?>
        <!-- lateral direita [end] -->

        <div class="quebra"></div>
        
    </div>
    <!-- conteudo [end] -->
    
    <!-- rodape -->
	<?php include_once("includes/rodape.php"); ?>

</div>

</body>
</html>