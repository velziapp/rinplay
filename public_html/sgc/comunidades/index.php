<?php
	include("../sys-php/connection.php");
	include("../sys-php/funcoes.php");
	include("../sys-php/session.php");
	
	$acao = $_GET['acao'];
	$codigo = $_GET['codigo'];
	$status = $_GET['status'];
	$tbl = $_GET['tabela'];
	$t = $_GET['TipoBusca'];
	$chave = $_GET['cChave'];
	$campo = $_GET['cCampo'];
	$pagina = $_GET['pagina'];
	
	$cx = conecta();
	
	// Tabela do Banco
	$TBL   = "rp_comunidades"; 
	
	if($acao == "excluir"){		
		mysqli_query( $cx, "DELETE FROM " . $TBL . " WHERE codigo =". $codigo);	
		mysqli_query( $cx, "DELETE FROM rp_comunidades_membros WHERE codigo_comunidade =". $codigo);	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" src="../sys-js/func.js"></script>
<script language="javascript">

function AbreForm(vfnURL, vfnLargura, vfnAltura){
	var vT;
	var vL;
	var vNome;
	var vVetor;
	vT = (window.screen.height/2) - (vfnAltura/2);
	vT = (vT-40);
	vL = (window.screen.width/2) - (vfnLargura/2);	
	window.open(vfnURL, '_blank', 'width='+ vfnLargura +'px, height='+ vfnAltura +'px, left='+ vL +'px, top='+vT+'px' );
}

function confirma(codigo, acao){
	apagar = confirm("Deseja realmente deletar esse registro ?")
	if ( apagar )
		window.location.href="index.php?codigo="+codigo+"&acao="+acao; 
}

</script>
<title></title>
<link href="../sys-css/config.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- Rótulo da página -->
<div class="orelhas">
<form name="frmAddEdit" id="frmAddEdit" action="index.php" method="GET">
	<div class="orelha" style="width:200px;">
		<div style="float:left; height:7px; width:100px;"><img src="../sys-img/dot.gif"></div><div class="divisor"></div>
		&nbsp;&nbsp;
		<b>Usuários </b>
	</div>
    <div class="orelha-botao">
		<input type="button" name="btnNovo" class="botao-orelha" value="Cadastrar categoria" onclick="AbreForm('form.php', 450, 150);">
	</div>
	<div class="orelha-busca" style="width:500px;">
		<div style="float:left; height:5px; width:200px;"><img src="../sys-img/dot.gif"></div><div class="divisor"></div>
		<div style="float:left;">
			&nbsp;&nbsp;
			<b>Buscar conte&uacute;do  com o texto </b> <input type="text" class="txtCampos" name="cChave" style="width:100px;"> no campo
			<select name="cCampo" class="txtCampos" style="width:100px;">
            	<option value="nome">Comunidade</option>                
            </select>
			&nbsp;
            <input type="hidden" name="TipoBusca" value="palavra">
			<input type="submit" value="Buscar" class="txtBotao" onclick="buscar('palavra');" style="width:40px;">
		</div>
	</div>
</form>
</div>
<div class="divisor"></div>

<!-- Conteúdo -->
<form name="frmBlock" id="frmBlock" action="index.php" method="GET">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="bgBarraTitulos" width="5%">#</td>		
		<td class="bgBarraTitulos" width="45%">Comunidade</td>        
        <td class="bgBarraTitulos" width="45%">Categoria</td>        
        <td class="bgBarraTitulos" width="5%">Excluir</td>		
    </tr>
	<?
	// Definindo o total de registros em uma página
	$num_por_pagina = 30;

	// Verificando se foi passado alguma página
	if (!$pagina){
	$pagina = 1;
	}
	
	//definindo o primeiro registro de cada página
	$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
	
	// Montando a SQL de consulta
	$SQL = "SELECT C.*, CA.descricao FROM ".$TBL." C JOIN rp_comunidades_ctg CA ON C.codigo_categoria = CA.codigo"; 
	
	
	switch( $t ){
		case "palavra":
		$SQL .= " WHERE " . $campo . " LIKE '%". LimpaEntrada( $chave ) ."%' ORDER BY C.codigo ASC LIMIT " . $primeiro_registro .", " . $num_por_pagina;
		$consulta = "SELECT COUNT(*) FROM ".$TBL." WHERE " . $campo . " LIKE '%". LimpaEntrada( $chave ) ."%' ORDER BY C.codigo ASC";
		break;
		
		case "":
		$SQL .= " ORDER BY C.codigo ASC LIMIT " . $primeiro_registro .", " . $num_por_pagina;
		$consulta = "SELECT COUNT(*) FROM ".$TBL;
		break;
		
	}

		// obtendo os dados que serão exibidos na página principal
		$rs = mysqli_query( $cx, $SQL);
		while($rst = mysqli_fetch_assoc($rs)){
			// Definindo a CSS Class que a linha utilizará			
			$CSS = "columnItem";
			?>
			<tr>
				<td class="<?=$CSS;?>" align="center" width="5%"><?php echo $rst['codigo'];?></td>
				<td class="<?=$CSS;?>" align="center" width="45%" style="text-align:left; padding-left:5px;"><?php echo utf8_decode($rst['nome']);?></td>
                <td class="<?=$CSS;?>" align="center" width="45%" style="text-align:left; padding-left:5px;"><?php echo utf8_decode($rst['descricao']);?></td>
                <td class="<?=$CSS;?>" align="center" width="5%"><a href="javascript: confirma(<?php echo $rst['codigo'];?>, 'excluir');"><img src="../sys-img/bt_excluir.gif" width="13" height="15" border="0"></a></td>	
            </tr>
			<?php
		}
		((mysqli_free_result($rs) || (is_object($rs) && (get_class($rs) == "mysqli_result"))) ? true : false);
		?>        
		<tr>
			<td colspan="10" class="columnPaginacao">
            &nbsp;
			<?php 
				// Pegando o total de registros e criando painel de navegação
				list($total_registros) = mysqli_fetch_array(mysqli_query( $cx, $consulta));
				$total_paginas = $total_registros/$num_por_pagina;
				$prev = $pagina - 1;
				$next = $pagina + 1;
				
				//Habilita o link anterior apenas se a página for maior que 1
				if ($pagina > 1){
				$prev_link = "<a href='index.php?pagina=".$prev."&cCampo=".$campo."&cChave=".$chave."&TipoBusca=".$t."'>Anterior</a>";
				}
				else{
				$prev_link = "Anterior";
				}
				
				//Habilita o link Próxima apenas se a página for menor que o total de páginas
				if($total_paginas > $pagina){
				$next_link = "<a href='index.php?pagina=".$next."&cCampo=".$campo."&cChave=".$chave."&TipoBusca=".$t."'>Pr&oacute;xima";
				}
				else{
				$next_link = "Pr&oacute;xima";
				}
				
				//Imprime o número de links de acordo com o numero total de artigos
				$total_paginas = ceil($total_paginas);
				$painel = "";
				for ($x=1; $x<=$total_paginas; $x++) {
					if ($x==$pagina) {
					$painel .= " [".$x."] ";
					}
					else{
					$painel .= "<a href='index.php?pagina=".$x."&cCampo=".$campo."&cChave=".$chave."&TipoBusca=".$t."'>[".$x."]</a>";
					}
				}
			
				// exibir painel na tela
				echo "$prev_link | $painel | $next_link";
			?>
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="pagina" id="pagina" value="<?=$pagina;?>" />
	<input type="hidden" name="cCampo" id="cCampo" value="<?=$cCampo;?>" />
	<input type="hidden" name="cChave" id="cChave" value="<?=$cChave;?>" />
    <input type="hidden" name="TipoBusca" id="TipoBusca" value="<?=$t;?>" />
    <input type="hidden" name="acao" id="acao" value="" />
</form>

<? if($t == "palavra") echo "<center><input type='button' value='Voltar' class='txtBotao' onclick='javascript:history.back();'></center>";?>
<div class="grid-paginacao">
</div>
</body>
</html>
