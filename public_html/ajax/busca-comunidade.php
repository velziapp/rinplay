<?php
ob_start();
session_start();

if(($_SESSION['logado'] == "") or (!isset($_SESSION['logado']))):
	die("Você não tem permissão para acessar esta página!");
endif;

include("../includes/config.php"); 
include("../includes/connection.php"); 
include("../includes/funcoes.php"); 
$cx = conecta();

if($_REQUEST['acao'] == 'pag'):
	$palavra = LimpaEntrada($_REQUEST['palavra']);
	
	// PAGINAÇÃO
	$quantidade		= 10;
	$pagina			= (isset($_REQUEST['pagina'])) ? (int)$_REQUEST['pagina'] : 1;
	$inicio			= ($quantidade * $pagina) - $quantidade;
	$totalLink		= 5;
	
	// TOTAL DE MENSAGENS
	$sql = "SELECT COUNT(*) AS total FROM rp_comunidades WHERE nome LIKE '%".$palavra."%'";
	$lnTotal = mysqli_fetch_assoc(mysqli_query( $cx, $sql));
	$totalRegistros = $lnTotal['total'];
	
	// CONTINUA PAGINAÇÃO
	$totalPagina	= ceil($totalRegistros / $quantidade);
	
	// BUSCA NOS USUÁRIOS
	$s = "SELECT * FROM rp_comunidades WHERE nome LIKE '%".$palavra."%'"; 
	$s.= " ORDER BY codigo DESC LIMIT $inicio, $quantidade";
	$r = mysqli_query( $cx, $s);
	if(mysqli_num_rows($r) > 0){
		while($ln = mysqli_fetch_assoc($r)){
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
		GeraPaginacaoDemandaParam($pagina, $totalPagina, 'ver mais...', '&acao=pag&palavra='.$palavra);
	}
endif;
?>