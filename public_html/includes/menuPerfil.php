<?php
	// FOTO E NOME DO PERFIL VISITADO
	$fotoUser = mysql_result(mysql_query("SELECT foto FROM rp_cadastros WHERE codigo = ".$codigo_user, $cx),0,0);
	$nomeUser = mysql_result(mysql_query("SELECT nome FROM rp_cadastros WHERE codigo = ".$codigo_user, $cx),0,0);
	
	$NomeUser = explode(" ", $nomeUser);
	
	$amigo = mysql_result(mysql_query("SELECT codigo FROM rp_amigos WHERE codigo_amigo = ".$codigo_user." AND codigo_user = ".$_SESSION['logado'], $cx),0,0);
	if($amigo == 0)
		$add = true;
?>
<div class="blocoMenu">
    <div id="div">
        <a href="verPerfil.php?codigo_user=<?php echo $codigo_user; ?>"><img src="sgc/uploads/fotos/<?php echo $fotoUser;?>" align="left" id="imgPerfil" height="50" width="50"></a>
        <strong><?php echo $NomeUser[0]." ".$NomeUser[1]; ?></strong><br />
        <a href="dados.php" style="display:<?php echo ($codigo_user == $_SESSION['logado'] ? "" : "none");?>;"><img src="imgs/ico_perfil.png" border="0" id="icoperfil"></a>
    </div>    
    <img src="imgs/ico_menu.png" id="icoMenu">
    <div class="alinhaMenu">
    	<?php 
			if($add && $_SESSION['logado'] != $codigo_user){	
       			echo "<img src='imgs/usuario_adicionar.gif' align='absmiddle'> <a href='verPerfil?codigo_user=".$codigo_user."&acao=add'>Adicionar</a><br />";
			}
			if(!$add){	
       			echo "<img src='imgs/lixeira.png' align='absmiddle'> <a href='verPerfil?codigo_user=".$codigo_user."&acao=del'>Remover</a><br />";
			}
		?>	
    	<!--<img src="imgs/letter.gif" align="absmiddle"> <a href="inicio.php">Mensagens</a><br />-->
        <?php 
			if($_SESSION['logado'] == $codigo_user){	
				echo "<img src='imgs/camera.gif' align='absmiddle'> <a href='album.php'>Álbum de fotos</a><br />";
			}
			else{
				echo "<img src='imgs/camera.gif' align='absmiddle'> <a href='verAlbum.php?codigo_user=".$codigo_user."'>Álbum de fotos</a><br />";
			}
			
		?>	
        
        <!--<img src="imgs/icon_comunidade.gif" height="16" width="16" align="absmiddle"> <a href="verComunidades.php">Comunidades</a><br />-->
        <!--<a href="">Fóruns</a><br />-->
        <img src="imgs/sair.png" align="absmiddle"> <a href="logout.php">Sair</a>
    </div>                    
    <!--<img src="imgs/ico_conteudo.png" id="icoMenu">
    <div class="alinhaMenu">
        <img src="imgs/artigo.png" align="absmiddle"> <a href="">Artigos</a><br />
        <img src="imgs/ico.materias.gif" align="absmiddle"> <a href="">Tutoriais</a><br />
        <img src="imgs/forum.png" align="absmiddle"> <a href="">Chat</a><br />
    </div>--> 
    <img src="imgs/ico_amigos.png" id="icoMenu">
    <div class="alinhaMenu">
    	<?php
		$SQL = "SELECT C.codigo as codigo_user, C.foto, C.nome FROM rp_cadastros C JOIN rp_amigos A ON C.codigo = A.codigo_amigo WHERE A.codigo_user =".$codigo_user." AND A.status = 'S' ORDER BY rand() LIMIT 9";
		$rs  = mysql_query($SQL, $cx);
		$x = 1;
		while($linha = mysql_fetch_assoc($rs)){	
		?>
            <a href="verPerfil.php?codigo_user=<?php echo $linha['codigo_user']; ?>"><img src="sgc/uploads/fotos/<?php echo $linha['foto']; ?>" height="36" width="36" border="0" title="<?php echo $linha['nome']; ?>"></a>
        <?php 
		if($x % 3 == 0) echo "<br />";
		$x++;
		} 
		?>            
    </div> 
</div>