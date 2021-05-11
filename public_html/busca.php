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
	
	// RECEBENDO A PALAVRA CHAVE
	$palavra = LimpaEntrada($_REQUEST['palavra']);
	
	// PAGINAÇÃO
	$quantidade		= 10;
	$pagina			= (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
	$inicio			= ($quantidade * $pagina) - $quantidade;
	$totalLink		= 5;
	
	// TOTAL DE MENSAGENS
	$sql = "SELECT COUNT(*) AS total FROM rp_cadastros WHERE nome LIKE '%".$palavra."%'";
	$lnTotal = mysql_fetch_assoc(mysql_query($sql, $cx));
	$totalRegistros = $lnTotal['total'];
	
	// CONTINUA PAGINAÇÃO
	$totalPagina	= ceil($totalRegistros / $quantidade);
	
	// TOTAL DE MENSAGENS
	$sql = "SELECT COUNT(*) AS total FROM rp_comunidades WHERE nome LIKE '%".$palavra."%'";
	$lnTotalComu = mysql_fetch_assoc(mysql_query($sql, $cx));
	$totalRegistrosComu = $lnTotalComu['total'];
	
	// CONTINUA PAGINAÇÃO
	$totalPaginaComu	= ceil($totalRegistrosComu / $quantidade);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js" ></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript">
function abreComentario( codigo ){
	document.getElementById("comente"+codigo).style.display = 'inline';
}
$(document).ready(function(){
	$("#tabs").tabs();
	
	// paginacao dos usuários
	$("#tab-usuario .pag").live('click', function(){
		$("#tab-usuario .pag-full").html("<img src='img/ajax-loader.gif' />");
		var pagina = $(this).attr('rel');
		$.ajax({
			type:"GET",
			url:"ajax/busca-usuario.php",
			data:""+pagina,
			success: function(valor){
				$("#tab-usuario .pag-full").fadeOut();
				$("#tab-usuario").append(valor);
				return false;	
			}
		})
	});
	// paginacao das comunidades
	$("#tab-comunidade .pag").live('click', function(){
		$("#tab-comunidade .pag-full").html("<img src='img/ajax-loader.gif' />");
		var pagina = $(this).attr('rel');
		$.ajax({
			type:"GET",
			url:"ajax/busca-comunidade.php",
			data:""+pagina,
			success: function(valor){
				$("#tab-comunidade .pag-full").fadeOut();
				$("#tab-comunidade").append(valor);
				return false;	
			}
		})
	});
});
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
        	
            <h3 class="titulo-sessao">Resultado da busca</h3>

            <div id="tabs">
            
                <ul>
                    <li><a href="#tab-usuario">Usuários</a></li>
                    <li><a href="#tab-comunidade">Comunidades</a></li>
                </ul>
                
                <div id="tab-usuario">
                <?php
                // BUSCA NOS USUÁRIOS
                $s = "SELECT * FROM rp_cadastros WHERE nome LIKE '%".$palavra."%'"; 
                $s.= " ORDER BY codigo DESC LIMIT $inicio, $quantidade";
                $r = mysql_query($s, $cx);
                if(mysql_num_rows($r) > 0){
                    while($ln = mysql_fetch_assoc($r)){
                        if($ln['foto'] == ""):
                            $fotoP = "semfoto.gif";
                        else:
                            $fotoP = $ln['foto'];	 
                        endif;
                        ?>
                            <table class="tb-comentario" width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="1" valign="top">
                                        <a href="perfil.php?codigo_user=<?php echo $ln['codigo']; ?>">
                                            <img src="sgc/uploads/fotos/<?php echo $fotoP; ?>" height="50" width="50">
                                        </a>
                                    </td>
                                    <td valign="top">
                                        <strong><?php echo $ln['nome']; ?></strong>
                                        <br />
                                        <?php echo htmlentities(exibeAnel($ln['codigo_anel'])); ?>
                                    </td>
                                </tr>
                            </table>
                    <?php
                    }
					GeraPaginacaoDemandaParam($pagina, $totalPagina, 'ver mais...', '&acao=pag&palavra='.$palavra);
                }
                ?>
                </div>
                
                <div id="tab-comunidade">
                <?php
                // BUSCA NOS COMUNIDADE
                $s = "SELECT * FROM rp_comunidades WHERE nome LIKE '%".$palavra."%' "; 
                $s.= " ORDER BY codigo DESC LIMIT $inicio, $quantidade";
                $r = mysql_query($s, $cx);
                if(mysql_num_rows($r) > 0){
                    while($ln = mysql_fetch_assoc($r)){
                        if($ln['foto'] == ""):
                            $fotoP = "semfoto.gif";
                        else:
                            $fotoP = $ln['foto'];	 
                        endif;
                        ?>
                            <table class="tb-comentario" width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="1" valign="top">
                                        <a href="comunidade-perfil.php?codigo_comunidade=<?php echo $ln['codigo']; ?>">
                                            <img src="sgc/uploads/comunidades/<?php echo $fotoP; ?>" height="50" width="50">
                                        </a>
                                    </td>
                                    <td valign="top">
                                        <strong><?php echo $ln['nome']; ?></strong>
                                    </td>
                                </tr>
                            </table>
                    <?php
                    }
					GeraPaginacaoDemandaParam($pagina, $totalPaginaComu, 'ver mais...', '&acao=pag&palavra='.$palavra);
                }
                ?>
                </div>
        	</div>
            
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