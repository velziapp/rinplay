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
<?php
	// NOME DO USUÁRIO LOGADO
	$NomeUser = explode(" ", $_SESSION['Nome']);
	
	// FOTO DO USUÁRIO LOGADO
	$fotoUser = mysqli_result(mysqli_query( $cx, "SELECT foto FROM rp_cadastros WHERE codigo = '".$_SESSION['logado']."'"), 0, 0);
?>
<div class="blocoMenu">
    <div id="div">
        <img src="sgc/uploads/fotos/<?php echo $fotoUser;?>" align="left" id="imgPerfil" height="50" width="50">
        <strong><?php echo $NomeUser[0]." ".$NomeUser[1]; ?></strong><br />
        <a href="dados.php"><img src="imgs/ico_perfil.png" border="0" id="icoperfil"></a>
    </div>    
    <img src="imgs/ico_menu.png" id="icoMenu">
    <div class="alinhaMenu">
        <!--<a href="inicio.php">Mensagens</a><br />
        <a href="album.php">Álbum de fotos</a><br />
        <a href="comunidades.php">Comunidades</a><br />-->
        <?php 
			if($edono){	
       			echo "<img src='imgs/editar.png' align='absmiddle'> <a href='javascript:mostraAlterar();'>Editar</a><br />";
			}
			if($edono){	
       			echo "<img src='imgs/lixeira.png' align='absmiddle'> <a href='minhasComunidades.php?acao=excluirComunidade&codigo_comunidade=".$codigo_comunidade."'>Lixeira</a><br />";
			}
			if($fazparte){
        		echo "<img src='imgs/plus.gif' align='absmiddle'> <a href='verTopicos.php?codigo_comunidade=".$codigo_comunidade."&nome=".$nome."'>Fórum</a><br />";
				echo "<img src='imgs/iconSair.png' align='absmiddle'> <a href='exibeComunidade.php?acao=deixar&codigo_comunidade=".$codigo_comunidade."&nome=".$nome."'>Abandonar</a><br />";
			}
			if(!$fazparte){	
       			echo "<img src='imgs/entrar.png' align='absmiddle'> <a href='exibeComunidade.php?acao=participar&codigo_comunidade=".$codigo_comunidade."&nome=".$nome."'>Participar</a><br />";
			}
		?>	
        <img src="imgs/sair.png" align="absmiddle"> <a href="logout.php">Sair</a>
    </div>                    
    <img src="imgs/ico_conteudo.png" id="icoMenu">
    <div class="alinhaMenu">
        <img src="imgs/artigo.png" align="absmiddle"> <a href="artigos.php">Artigos</a><br />
        <img src="imgs/ico.materias.gif" align="absmiddle"> <a href="tutoriais.php">Tutoriais</a><br />
        <img src="imgs/forum.png" align="absmiddle"> <a href="javascript: AbreForm('chat/index.htm', 520, 500);">Chat</a><br />
    </div> 
    <img src="imgs/ico_membros.png" id="icoMenu">
    <div class="alinhaMenu">
    	<?php
		$SQL = "SELECT C.codigo as codigo_user, C.foto, C.nome FROM rp_cadastros C JOIN rp_comunidades_membros M ON C.codigo = M.codigo_user WHERE M.codigo_comunidade =".$codigo_comunidade." LIMIT 9";
		$rs  = mysqli_query( $cx, $SQL);
		$x = 1;
		while($linha = mysqli_fetch_assoc($rs)){	
		?>
            <a href="verPerfil.php?codigo_user=<?php echo $linha['codigo_user']; ?>"><img src="sgc/uploads/fotos/<?php echo $linha['foto']; ?>" height="36" width="36" border="0" title="<?php echo $linha['nome']; ?>"></a>
        <?php 
		if($x % 3 == 0) echo "<br />";
		$x++;
		} 
		?>            
    </div> 
</div>