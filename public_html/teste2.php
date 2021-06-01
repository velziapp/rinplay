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
        	
            <h3 class="titulo-sessao">Tutoriais</h3>
			
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <?php
			// BUSCA 
			$s = "SELECT * FROM rp_tutoriais ORDER BY data DESC";
			$r = mysqli_query( $cx, $s);
			$consulta = "SELECT COUNT(*) FROM rp_tutoriais"; 	
			if(mysqli_num_rows($r) > 0):
				while($ln = mysqli_fetch_assoc($r)){
					if($ln['arquivo'] == ""):
						$link = "ver-tutorial.php?codigo=".$ln['codigo'];
						$target = "_self";
					else:
						$link = "sgc/uploads/".$ln['arquivo'];
						$target = "_blank"; 
					endif;
					?>
                    <tr>
                        <td class="linha-rodape-td">
                            <img src="img/ico.materias.gif" border="0" align="texttop" alt=""/>
                            <a target="<?php echo $target; ?>" href="<?php echo $link; ?>"> <?php echo $ln['titulo']; ?></a> - <?php echo $ln['data']; ?>
                        </td>                                        
                    </tr>
					<?php        
					}
			endif;
			?>
            </table>
                    
        </div>
        <!-- centro [end] -->
		
      
        <div class="quebra"></div>
        
    </div>
    <!-- conteudo [end] -->
    
    <!-- rodape -->
	<?php include_once("includes/rodape.php"); ?>

</div>

</body>
</html>