<?php 
	ob_start();
	session_start();
	
	if(($_SESSION['logado'] == "") or (!isset($_SESSION['logado']))):
		header("Location: index.php");
	endif;

	include("includes/config.php"); 
	include("includes/connection.php"); 
	include("includes/funcoes.php"); 
	$cx = conecta();
	
	$palavra = LimpaEntrada($_REQUEST['palavra']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
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
        	
            <h3 class="titulo-sessao"><a href="comunidade.php" class="txt_vermelho">Comunidades</a> &raquo; Pesquisar Comunidades</h3>
            
            <div id="comunidade-form">
                <form method="post" action="" name="fbuscacomu" id="fbuscacomu">
                    Buscar comunidade 
                    <input type="text" name="palavra" value="<?php echo ($palavra != "" ? $palavra : ""); ?>" size="50" />
                    <input type="submit" value="Ok" />
                </form>
			</div>
            
            <table width="100%" border="0">
            <?php
				$s = "SELECT nome, codigo, foto FROM rp_comunidades WHERE nome like '%".$palavra."%' ORDER BY nome";
				$r = mysql_query($s, $cx);
				while($ln = mysql_fetch_assoc($r)){
					$qtdMembros = mysql_result(mysql_query("SELECT COUNT(codigo) FROM rp_comunidades_membros WHERE codigo_comunidade = ".$ln['codigo'],$cx),0,0);
					echo "<tr><td rowspan='2' width='1' valign='top'>";
					echo "<a href='comunidade-perfil.php?codigo_comunidade=".$ln['codigo']."'><img src='sgc/uploads/comunidades/".$ln['foto']."' width='60' ></a>";
					echo "</td>";
					echo "<td valign='top'>";
					echo "<h4>
	   					  	<strong><span id='t'>".$ln['nome']." (".$qtdMembros." ".($qtdMembros == 1 ? "membro" : "membros").")</span></strong>
						  </h4>";
					echo "</td></tr>";
					echo "<tr><td valign='top'>".$ln['descricao']."</td></tr>";
					echo "<tr><td height='10' colspan='2'></td></tr>";
				}
			?>
            </table>
                    
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