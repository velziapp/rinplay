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
	if($acao == "alterar"){
		$resposta	= "";
		$nome	  	= LimpaEntrada($_POST['nome']);
		$sobrenome 	= LimpaEntrada($_POST['sobrenome']);
		$nascimento = LimpaEntrada($_POST['nascimento']);
		//$telefone 	= LimpaEntrada($_POST['telefone']);
		$senha      = LimpaEntrada($_POST['senha']);
		$anel		= LimpaEntrada($_POST['anel']);
		$newSenha	= LimpaEntrada($_POST['newSenha']);
		$news		= LimpaEntrada($_POST['news']);
				
		// CONCATENANDO NOME E SOBRENOME
		//$nome = $nome." ".$sobrenome;
		
		if(strlen($newSenha) > 0):
			$senha = $newSenha;
		endif;			

		// UPLOAD FOTO //
		$pasta = "sgc/uploads/fotos/"; // PASTA ONDE FICARA OS UPLOADS
		$permitido = (array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/gif', 'image/png')); // TIPOS PERMITIDO PARA UPLOAD	
		$img = $_FILES['foto'];
		$tmp = $img['tmp_name']; // CAMINHO DA IMAGEM
		$name = $img['name']; // NOME DO ARQUIVO ENVIADO
		$type = $img['type']; // TIPO DO ARQUIVO
			
		if(empty($name)){	
			$UPDATE = "UPDATE rp_cadastros SET nome = '".$nome . " " . $sobrenome ."', nascimento = '".$nascimento."', senha = '".$senha."', news = '".$news."', codigo_anel = ".$anel." WHERE codigo = ".$_SESSION['logado'];
			mysqli_query( $cx, $UPDATE);
			header("location: dados.php");
			//$msg = "Cadastro efetuado com sucesso! Você já pode efetuar o login.";
		}
		else{	
			if(in_array($type, $permitido)){
				// GERA NOME UNICO PARA IMPEDIR DE SUBSTITUIR IMAGEM
				if(($type == 'image/jpg') or ($type == 'image/jpeg') or ($type == 'image/pjpeg')):
					$foto = 'foto-'.md5(uniqid(rand(), true)).'.jpg'; 
				endif;
				if($type == 'image/gif'):
					$foto = 'foto-'.md5(uniqid(rand(), true)).'.gif'; 
				endif;
				if($type == 'image/png' || $type == 'image/x-png'):
					$foto = 'foto-'.md5(uniqid(rand(), true)).'.png'; 
				endif;
					
				uploadImg($tmp, $foto, $type, 400, $pasta, 'S'); // FAZ O UPLOAD
		
				$UPDATE = "UPDATE rp_cadastros SET nome = '".$nome. " " . $sobrenome ."', nascimento = '".$nascimento."', senha = '".$senha."', codigo_anel = ".$anel.", foto ='".$foto."' WHERE codigo = ".$_SESSION['logado'];
				mysqli_query( $cx, $UPDATE);
				header("location: dados.php");
				//$msg = "Cadastro efetuado com sucesso! Você já pode efetuar o login.";
			}
		}
	}
	// BUSCANDO OS DADOS DO USUÁRIO LOGADO
	$SQL = "SELECT * FROM rp_cadastros WHERE codigo = ".$_SESSION['logado'];
	$rs  = mysqli_query( $cx, $SQL);
	$dados = mysqli_fetch_assoc($rs);
	$nome = explode(' ', $dados['nome']);
	for($i = 1; $i < (count($nome)); $i++){
		$sobrenome .= $nome[$i] . " ";
	}
	$sobrenome = trim($sobrenome);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?></title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput-1.1.4.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#nascimento").mask("99/99/9999");				
		$("#telefone").mask("(99) 9999-9999");				
		
		// VALIDA
		$("#frm").validate({
			 rules: {
				 email: {required: true, email: true},         
				 nome: {required: true},         
				 anel :{required: true},
				 nascimento: {required: true}                 
				 
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
        	
            <h3 class="titulo-sessao">Editar Perfil</h3>
            
            <form method="POST" action="dados.php" name="frm" id="frm" enctype="multipart/form-data">
                <table width="100%" border="0">
                    <tr>
                        <td width="100">Foto:</td><td><input type="file" name="foto" id="foto" size="45" /></td>
                    </tr>
                    <tr>
                        <td>Nome:</td><td><input type="text" name="nome" id="nome" size="70" value="<?php echo $nome[0]; ?>" maxlength="250" /></td>
                    </tr> 
                    <tr>
                      <td>Sobrenome:</td>
                      <td><input type="text" name="sobrenome" id="sobrenome" size="70" value="<?php echo $sobrenome; ?>" maxlength="100" /></td>
                    </tr>
                    <tr>
                        <td>E-mail:</td><td> <input type="text" name="email" id="email" size="70" value="<?php echo $dados['email']; ?>" readonly="readonly" maxlength="250" /></td>
                    </tr>
                    <tr>                                    
                        <td>Nascimento:</td><td> <input type="text" name="nascimento" size="70" id="nascimento" value="<?php echo $dados['nascimento']; ?>" maxlength="10" /></td>
                    </tr>
                    <!--<tr>                                    
                        <td>Telefone:</td><td> <input type="text" name="telefone" id="telefone" value="<?php echo $dados['telefone']; ?>" maxlength="14" /></td>
                    </tr>-->                                    
                    <tr>
                        <td>Nova senha:</td><td> <input type="password" size="70" name="newSenha" id="newSenha" maxlength="50" /></td>
                    </tr>
                    <tr>                                    
                        <td>Sexo: </td>
                        <td>
                            <input type="text" name="sexo" id="sexo" size="70" value="<?php echo $dados['sexo']; ?>" maxlength="250" readonly="readonly" />
                        </td>
                    </tr>                                   
                    <tr>                                    
                        <td>Anel: </td>
                        <td>
                            <?php comboPorTabela2( "anel", "rp_aneis", "codigo", "titulo", $dados['codigo_anel'], "", "", true, "" );?>
                        </td>
                    </tr>                                                                            
                    <tr>
                      <td></td>
                      <td><input type="checkbox" name="news" id="news" value="S" <?php if($dados['news'] == 'S'): ?>checked="checked"<?php endif; ?> /> Desejo receber novidades do Rinplay em meu e-mail.</td>
                    </tr>
                    <tr>
                    	<td></td>
                        <td>
                            <input type="hidden" name="acao" value="alterar">
                            <input type="hidden" name="senha" value="<?php echo $dados['senha']; ?>">
                            <input type="submit" value="Salvar" />
                        </td>
                    </tr>                                    
                </table>                                
            </form>
            
            
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