		<?php
        // FOTO E NOME DO PERFIL VISITADO
		$fotoUser = mysql_result(mysql_query("SELECT foto FROM rp_cadastros WHERE codigo = ".$codigo_user, $cx),0,0);
		$nomeUser = mysql_result(mysql_query("SELECT nome FROM rp_cadastros WHERE codigo = ".$codigo_user, $cx),0,0);
		
		$NomeUser = explode(" ", $nomeUser);
		$amigo = mysql_result(mysql_query("SELECT codigo FROM rp_amigos WHERE codigo_amigo = ".$codigo_user." AND status = 'S' AND codigo_user = ".$_SESSION['logado'], $cx),0,0);
		if($amigo == 0) $add = true;
		
		$amigo_pendente = mysql_query("SELECT codigo FROM rp_amigos WHERE codigo_amigo = ".$codigo_user." AND status <> 'S' AND codigo_user = ".$_SESSION['logado'], $cx);
		if(mysql_num_rows($amigo_pendente) > 0) $pendente_amigo = true;
		?>
		
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
        <div id="lateral-esquerda">
        	
			<a href="perfil.php?codigo_user=<?php echo $codigo_user; ?>"><img src="sgc/uploads/fotos/<?php echo $fotoUser;?>" width="80" height="80"></a>
            <img src="img/<?php echo $img_anel;?>" height="80" title="<?php echo $titleAnel; ?>" alt="<?php echo $titleAnel; ?>">
            <h4><strong><?php echo $NomeUser[0]." ".$NomeUser[1]; ?></strong></h4>
            <br />
            
            <h5>MENU</h5>
            <ul>
            	<li><img src="img/letter.gif" align="absmiddle"> <a href="perfil.php?codigo_user=<?php echo $codigo_user; ?>">Perfil</a></li>
                <?php if($add && $_SESSION['logado'] != $codigo_user && !$pendente_amigo){	?>
            	<li><img src="img/usuario_adicionar.gif" align="absmiddle"> <a href="perfil.php?codigo_user=<?php echo $codigo_user; ?>&acao=add">Adicionar</a></li>
                <?php // } elseif($pendente_amigo){	?>
            	<!-- <li><img src="img/usuario_adicionar.gif" align="absmiddle"> Solicitação pendente</li>-->
				<?php } elseif(!$add){ ?>
            	<li><img src="img/icon_comunidade.gif" height="16" align="absmiddle"> <a href="aliados-perfil.php?codigo_user=<?php echo $codigo_user; ?>">Aliados</a></li>
            	<li><img src="img/lixeira.png" align="absmiddle"> <a href="perfil.php?codigo_user=<?php echo $codigo_user; ?>&acao=del">Remover</a></li>
            	<li><img src="img/camera.gif" align="absmiddle"> <a href="album-perfil.php?codigo_user=<?php echo $codigo_user; ?>">Álbum de fotos</a></li>
				<?php } ?>
            </ul>
            <br />
                        
            <h5>ALIADO</h5>
            <ul id="lista-aliados">
            <?php
			$SQL = "SELECT C.codigo as codigo_user,C.foto, C.nome FROM rp_cadastros C JOIN rp_amigos A ON C.codigo = A.codigo_amigo WHERE A.codigo_user =".$codigo_user." AND A.status = 'S' GROUP BY c.codigo ORDER BY rand() LIMIT 9";
			$rs  = mysql_query($SQL, $cx);
			if(mysql_num_rows($rs) > 0):
				while($linha = mysql_fetch_assoc($rs)){	
				?>
					<?php if($linha['codigo_user'] == $_SESSION['logado']): ?>
	                    <li><a href="inicio.php"><img src="sgc/uploads/fotos/<?php echo $linha['foto']; ?>" height="36" width="36" border="0" title="<?php echo $linha['nome']; ?>"></a></li>
                    <?php else: ?>
                        <li><a href="perfil.php?codigo_user=<?php echo $linha['codigo_user']; ?>"><img src="sgc/uploads/fotos/<?php echo $linha['foto']; ?>" height="36" width="36" border="0" title="<?php echo $linha['nome']; ?>"></a></li>
                    <?php endif; ?>				<?php 
				} 
			else:
				echo '<li>Nenhum aliado</li>';
			endif;
			?>
            </ul>
            
        </div>