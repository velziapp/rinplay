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
	$fotoUser = mysql_result(mysql_query("SELECT foto FROM rp_cadastros WHERE codigo = '".$_SESSION['logado']."'", $cx),0,0);
	
	// QUANTIDADE DE PEDIDOS DE AMIZADE
	$qtdAdd = mysql_result(mysql_query("SELECT COUNT(*) FROM rp_amigos WHERE codigo_amigo = '".$_SESSION['logado']."' AND status = 'N'", $cx),0,0);
?>
<div class="blocoMenu">
    <div id="div">
        <img src="sgc/uploads/fotos/<?php echo $fotoUser;?>" align="left" id="imgPerfil" height="50" width="50">
        <strong><?php echo $NomeUser[0]." ".$NomeUser[1]; ?></strong><br />
        <a href="dados.php"><img src="imgs/ico_perfil.png" border="0" id="icoperfil"></a>
    </div>    
    <img src="imgs/ico_menu.png" id="icoMenu">
    <div class="alinhaMenu">
        <img src="imgs/letter.gif" align="absmiddle"> <a href="inicio.php">Mensagens</a><br />
        <img src="imgs/camera.gif" align="absmiddle"> <a href="album.php">Álbum de fotos</a><br />
        <img src="imgs/icon_comunidade.gif" height="16" width="16" align="absmiddle"> <a href="comunidades.php">Comunidades</a><br />
        <img src="imgs/icon-add.png" align="absmiddle"> <a href="pedidos.php">Solicitação de Aliado <?php if($qtdAdd > 0) echo "(".$qtdAdd.")"; ?></a><br />
        <!--<a href="">Fóruns</a><br />-->
        <img src="imgs/sair.png" align="absmiddle"> <a href="logout.php">Sair</a>
    </div>                    
    <img src="imgs/ico_conteudo.png" id="icoMenu">
    <div class="alinhaMenu">
        <img src="imgs/artigo.png" align="absmiddle"> <a href="artigos.php">Artigos</a><br />
        <img src="imgs/ico.materias.gif" align="absmiddle"> <a href="tutoriais.php">Tutoriais</a><br />
        <img src="imgs/forum.png" align="absmiddle"> <a href="javascript: AbreForm('chat/index.htm', 520, 500);">Chat</a><br />
    </div> 
    <img src="imgs/ico_amigos.png" id="icoMenu">
    <div class="alinhaMenu">
    	<?php
		$SQL = "SELECT C.codigo as codigo_user,C.foto, C.nome FROM rp_cadastros C JOIN rp_amigos A ON C.codigo = A.codigo_amigo WHERE A.codigo_user =".$_SESSION['logado']." AND A.status = 'S' ORDER BY rand() LIMIT 9";
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