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
	
	$acao = LimpaEntrada($_REQUEST['acao']);

	switch($acao){
		case "criarComunidade":
			$nome 			  = LimpaEntrada($_REQUEST['nome']);
			$codigo_categoria = LimpaEntrada($_REQUEST['codigo_categoria']);
			$descricao		  = LimpaEntrada($_REQUEST['descricao']);
			
			// UPLOAD FOTO //
			$pasta = "sgc/uploads/comunidades/"; // PASTA ONDE FICARA OS UPLOADS
			$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
			$img = $_FILES['foto'];
			$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
			$name = $img['name']; // NOME DO ARQUIVO ENVIADO
			$type = $img['type']; // TIPO DO ARQUIVO
			
			if(in_array($type, $permitido)){
				// GERA NOME UNICO PARA IMPEDIR DE SUBSTITUIR IMAGEM
				if(($type == 'image/jpg') or ($type == 'image/jpeg') or ($type == 'image/pjpeg')):
					$foto = 'comunidade-'.md5(uniqid(rand(), true)).'.jpg'; 
				endif;
				if($type == 'image/gif'):
					$foto = 'comunidade-'.md5(uniqid(rand(), true)).'.gif'; 
				endif;
				if($type == 'image/png'):
					$foto = 'comunidade-'.md5(uniqid(rand(), true)).'.png'; 
				endif;
				
				uploadImg($tmp, $foto, $type, 200, $pasta, 'S'); // FAZ O UPLOAD
		
				$SQL  = "INSERT INTO rp_comunidades (nome, descricao, codigo_categoria, foto, codigo_user) VALUES ('".$nome."','".$descricao."',".$codigo_categoria.",'".$foto."',".$_SESSION['logado'].")";
				mysql_query($SQL, $cx);
				
				$idnew = mysql_insert_id();
				$sql = "INSERT INTO rp_comunidades_membros (codigo_comunidade, codigo_user) VALUES ('". $idnew ."', '". $_SESSION['logado'] ."')";
				mysql_query($sql, $cx);
			}
			header("Location: comunidade.php");
		break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript">
	function exibeCadastro(){
		document.getElementById("botao").style.display = 'none';
		document.getElementById("cadastro").style.display = 'inline';
	}
	
	function ocultaCadastro(){
		document.getElementById("botao").style.display = 'inline';
		document.getElementById("cadastro").style.display = 'none';
	}
	
	function envia(){
	   document.fcomu.submit();
	} 
	$(document).ready(function(){
		$("#fcomu").validate({
		  rules: {
			 nome: {required: true},
			 codigo_categoria: {required: true},
			 foto: {required: true},
			 descricao: {required: true}
		  }
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
        	
            <h3 class="titulo-sessao">Comunidades</h3>
			
            <div id="comunidade-form">
            <form method="post" action="comunidade.php" enctype="multipart/form-data" name="fcomu" id="fcomu">

                <div id="botao">
                    <input type="button" onclick="exibeCadastro();" value="Criar Comunidade" />
                    <input type="button" onclick="document.location.href='minhas-comunidades.php';" value="Minhas Comunidades" />
                    <input type="button" onclick="document.location.href='busca-comunidades.php';" value="Pesquisar Comunidade" />
                </div>

           		<div id="cadastro" style="display:none;">
                	<table width="100%" border="0"> 
                        <tr>
                            <td width="1">Nome:</td>
                            <td><input type="text" name="nome" id="nome" size="70" /></td>
                        </tr>
                        <tr>
                            <td>Categoria:</td>
                            <td>                                    
                                <?php comboPorTabela2( "codigo_categoria", "rp_comunidades_ctg", "codigo", "descricao", $ln['codigo_categoria'], "", "", true, "" );?>
                            </td>
                        </tr>
                        <tr>                                                                    
                            <td>Foto:</td>
                            <td><input type="file" name="foto" id="foto" size="50" /></td>
                        </tr>
                        <tr>
                            <td>Descrição:</td>
                            <td><textarea name="descricao" id="descricao" cols="70" ></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" name="acao" value="criarComunidade">
                                <input type="submit" value="Salvar">
                                <input type="button" onclick="ocultaCadastro();" value="Cancelar">
                            </td>
                        </tr>                                                                    
                	</table>                        
        		</div>
                
			</form>
            </div>
            <table width="100%" border="0">
            <?php
				$s = "SELECT C.nome, C.codigo, C.foto, C.descricao FROM rp_comunidades C JOIN rp_comunidades_membros M ON C.codigo = M.codigo_comunidade WHERE M.codigo_user = ".$_SESSION['logado'] . " GROUP BY C.codigo";
				$r = mysql_query($s, $cx);
				while($ln = mysql_fetch_assoc($r)){
					$qtdMembros = mysql_result(mysql_query("SELECT COUNT(codigo) FROM rp_comunidades_membros WHERE codigo_comunidade = ".$ln['codigo'],$cx),0,0);
					echo "<tr><td rowspan='2' width='80' align='center' valign='top'>";
					echo "<a href='comunidade-perfil.php?codigo_comunidade=".$ln['codigo']."'><img src='sgc/uploads/comunidades/".$ln['foto']."' width='60' align='left'></a>";
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