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
	
	$codigo_admin = $_GET['codigo_admin'];
	$codigo_modulo = $_GET['codigo_modulo'];
	
	$cx = conecta();
	
	// Tabela do Banco
	$TBL   = "rp_admin"; 
	
	if($acao == "incluir"){		
		$sql = "INSERT INTO rp_admin_permissoes( codigo_admin, codigo_modulo ) VALUES('" . $codigo_admin . "','" . $codigo_modulo . "')";
		mysql_query($sql, $cx);		
	}
	
	if($acao == "excluir"){		
		$sql = "DELETE FROM rp_admin_permissoes WHERE codigo_admin = '" . $codigo_admin . "' AND codigo_modulo = '" . $codigo_modulo . "'";
		mysql_query($sql, $cx);		
	}
	
	if ($acao == "excluir_admin"){	
		$sql = "DELETE FROM ".$TBL." WHERE codigo = '" . $codigo . "'";
		mysql_query($sql, $cx);
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

var chkItens = "";

function checkAll( bol ){
	var it = chkItens.split("|");
	var o = null;
	
	for( x = 0; x <= it.length; x++ ){
		o = document.getElementById( "chk_" + it[x] );
		if( bol ){
			o.checked = true;
		} else{
			o.checked = false;
		}
	}
	o = null;
	it = null;
}

function block( status ){
	var f = null;
	var o = null;
	
	f = document.getElementById( "frmBlock" );
	o = f.acao;
	
	if( status == 1 ) o.value = "bloquear";
	if( status == 0 ) o.value = "liberar";
	
	f.submit();
	
	o = null;
	f = null;
}

function preparaAlteracao( cod, txt ){
	var o = null;
	
	o = document.getElementById( "cCodigoItem" );
	o.value = cod;

	o = document.getElementById( "rotAddEdit" );
	o.innerHTML = "Alterar categoria";
	
	o = document.getElementById( "btnCancela" );
	o.disabled = false;
	
	o = document.getElementById( "frmAddEdit" );
	o.acao.value = "alterar";

	o = document.getElementById( "cNomeCategoria" );
	o.value = txt;
	o.focus();

	o = null;
}

function cancelaAlteracao(){
	var o = null;
	
	o = document.getElementById( "cNomeCategoria" );
	o.value = "";

	o = document.getElementById( "cCodigoItem" );
	o.value = 0;

	o = document.getElementById( "rotAddEdit" );
	o.innerHTML = "Inserir categoria";
	
	o = document.getElementById( "btnCancela" );
	o.disabled = true;

	o = document.getElementById( "frmAddEdit" );
	o.acao.value = "incluir";

	o = null;
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
		<b>Administradores </b>
	</div>
    <div class="orelha-botao">
		<input type="button" name="btnNovo" class="botao-orelha" value="Novo administrador" onclick="AbreForm('form.php', 520, 180);">
	</div>
	<div class="orelha-busca" style="width:700px;">
		<div style="float:left; height:5px; width:200px;"><img src="../sys-img/dot.gif"></div><div class="divisor"></div>
		<div style="float:left;">
			&nbsp;&nbsp;
			<b><div id="rotAddEdit" style="width:100px; margin-top:2px; margin-left:10px; float:left;">Inserir permissão</div></b> 
            <?php comboPorTabela( "codigo_admin", "rp_admin", "codigo", "nome", "", "txtCampos", "", true, "" );?>
            <?php comboPorTabela( "codigo_modulo", "rp_modulos_admin", "codigo", "modulo", "", "txtCampos", "", true, "" );?>            
			<input type="submit" value="OK" class="txtBotao" style="width:40px;">            
            <input type="hidden" name="cCodigoItem" id="cCodigoItem" value="0">
            <input type="hidden" name="acao" id="acao" value="incluir">
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
		<td class="bgBarraTitulos" width="20%">Administrador</td>		
        <td class="bgBarraTitulos" width="70%">Permissões</td>
        <td class="bgBarraTitulos" width="5%">Excluir</td>		
	</tr>
	<?php
	// Definindo o total de registros em uma página
	$num_por_pagina = 30;

	// Verificando se foi passado alguma página
	if (!$pagina){
	$pagina = 1;
	}
	
	//definindo o primeiro registro de cada página
	$primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;
	
	// Montando a SQL de consulta
	$SQL = "SELECT codigo, nome FROM ".$TBL; 
	
	
	switch( $t ){
		case "palavra":
		$SQL .= " WHERE " . $campo . " LIKE '%". LimpaEntrada( $chave ) ."%' ORDER BY .codigo ASC LIMIT " . $primeiro_registro .", " . $num_por_pagina;
		$consulta = "SELECT COUNT(*) FROM ".$TBL." WHERE " . $campo . " LIKE '%". LimpaEntrada( $chave ) ."%' ORDER BY codigo ASC";
		break;
		
		case "":
		$SQL .= " ORDER BY codigo ASC LIMIT " . $primeiro_registro .", " . $num_por_pagina;
		$consulta = "SELECT COUNT(*) FROM ".$TBL;
		break;
		
	}

		// obtendo os dados que serão exibidos na página principal
		$rs = mysql_query($SQL, $cx);
		while($rst = mysql_fetch_array($rs)){
			// Definindo a CSS Class que a linha utilizará			
			$CSS = "columnItem";
			?>
			<tr>
				<td class="<?php echo $CSS;?>" align="center" width="5%"><?=$rst[0];?></td>
				<td class="<?php echo $CSS;?>" align="center" width="20%"><?=$rst[1];?></td>
                <td class="<?php echo $CSS;?>" align="left" width="70%">
                	<?php
                    	$sql = "SELECT P.codigo_modulo, M.modulo FROM rp_admin_permissoes P";
						$sql.= " JOIN rp_modulos_admin M"; 
						$sql.= " ON M.codigo = P.codigo_modulo";
						$sql.= " WHERE P.codigo_admin = $rst[0]";
						$result = mysql_query($sql, $cx);
						while($ln = mysql_fetch_array($result)){
							echo $ln[1]." <a href='index.php?acao=excluir&codigo_admin=$rst[0]&codigo_modulo=$ln[0]'><img src='../sys-img/bt_excluir.gif' width='10' height='10' border='0'></a>&nbsp;&nbsp;";
						}
					?>
                </td>	
                <?php if ($rst[0] != $_SESSION['Id']){ ?>
                <td class="<?php echo $CSS;?>" align="center" width="5%"><a href="index.php?codigo=<?php echo $rst[0];?>&acao=excluir_admin"><img src="../sys-img/bt_excluir.gif" width="13" height="15" border="0"></a></td>	
                <?php } else{ echo "<td class='".$CSS."' align='center' width='70%'>#</td>";}?>
			</tr>
			<?php
		}
		mysql_free_result($rs);
		?>        
		<tr>
			<td colspan="10" class="columnPaginacao">
            &nbsp;
			<?php 
				// Pegando o total de registros e criando painel de navegação
				list($total_registros) = mysql_fetch_array(mysql_query($consulta, $cx));
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
	
	<input type="hidden" name="pagina" id="pagina" value="<?php echo $pagina;?>" />
	<input type="hidden" name="cCampo" id="cCampo" value="<?php echo $cCampo;?>" />
	<input type="hidden" name="cChave" id="cChave" value="<?php echo $cChave;?>" />
    <input type="hidden" name="TipoBusca" id="TipoBusca" value="<?php echo $t;?>" />
    <input type="hidden" name="acao" id="acao" value="" />
</form>

<?php if($t == "palavra") echo "<center><input type='button' value='Voltar' class='txtBotao' onclick='javascript:history.back();'></center>";?>
<div class="grid-paginacao">
</div>
</body>
</html>
