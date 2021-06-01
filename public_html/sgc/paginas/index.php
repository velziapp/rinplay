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
	$TBL   = "rp_tutoriais"; 
	
	if($acao == "excluir"){		
		mysqli_query( $cx, "DELETE FROM " . $TBL . " WHERE codigo =". $codigo);	
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
                <b>P&aacute;ginas </b>
            </div>
        </form>
    </div>
    <div class="divisor"></div>

    <!-- Conteúdo -->
    <form name="frmBlock" id="frmBlock" action="index.php" method="GET">

        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="50" class="bgBarraTitulos">Alterar</td>		
                <td align="left" class="bgBarraTitulos">Título</td>		
            </tr>
            <tr>
                <td align="center"><a href="javascript: AbreForm('form_sobre.php', 650, 400);"><img src="../sys-img/bt_alterar.gif" width="13" height="15" border="0"></a></td>	
                <td align="left" class="texto_form">Sobre</td>
            </tr>
            <tr>
                <td align="center"><a href="javascript: AbreForm('form_anuncio.php', 650, 400);"><img src="../sys-img/bt_alterar.gif" width="13" height="15" border="0"></a></td>	
                <td align="left" class="texto_form">Anúncio</td>
            </tr>
            <tr>
                <td align="center"><a href="javascript: AbreForm('form_tutorial.php', 650, 400);"><img src="../sys-img/bt_alterar.gif" width="13" height="15" border="0"></a></td>	
                <td align="left" class="texto_form">Tutorial</td>
            </tr>
            <tr>
                <td align="center"><a href="javascript: AbreForm('form_privacidade.php', 650, 400);"><img src="../sys-img/bt_alterar.gif" width="13" height="15" border="0"></a></td>	
                <td align="left" class="texto_form">Privacidade</td>
            </tr>
            <tr>
                <td align="center"><a href="javascript: AbreForm('form_termos.php', 650, 400);"><img src="../sys-img/bt_alterar.gif" width="13" height="15" border="0"></a></td>	
                <td align="left" class="texto_form">Termos</td>
            </tr>
            <tr>
                <td align="center"><a href="javascript: AbreForm('form_ajuda.php', 650, 400);"><img src="../sys-img/bt_alterar.gif" width="13" height="15" border="0"></a></td>	
                <td align="left" class="texto_form">Ajuda</td>
            </tr>
        </table>
	
        <input type="hidden" name="pagina" id="pagina" value="<?=$pagina;?>" />
        <input type="hidden" name="cCampo" id="cCampo" value="<?=$cCampo;?>" />
        <input type="hidden" name="cChave" id="cChave" value="<?=$cChave;?>" />
        <input type="hidden" name="TipoBusca" id="TipoBusca" value="<?=$t;?>" />
        <input type="hidden" name="acao" id="acao" value="" />
    </form>

</div>
</body>
</html>
