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
        	
            <?php
				// USUÁRIO LOGADO
				$fotoUser = mysql_fetch_assoc(mysql_query("SELECT nome, foto FROM rp_cadastros WHERE codigo = '".$_SESSION['logado']."'", $cx));
				// NOME DO USUÁRIO LOGADO
				$NomeUser = explode(" ", $fotoUser['nome']);
				// QUANTIDADE DE PEDIDOS DE AMIZADE
				$qtdAdd = mysql_result(mysql_query("SELECT COUNT(*) FROM rp_amigos WHERE codigo_amigo = '".$_SESSION['logado']."' AND status = 'N'", $cx),0,0);
			?>
			<a href="inicio.php"><img src="sgc/uploads/fotos/<?php echo $fotoUser['foto'];?>" width="80" height="80"></a>
            <img src="img/<?php echo $img_anel;?>" height="80" title="<?php echo $titleAnel; ?>" alt="<?php echo $titleAnel; ?>">
            <h4><strong><?php echo $NomeUser[0]." ".$NomeUser[1]; ?></strong></h4>
            <a href="dados.php">Rinface</a>
            <br /><br />
            
            <h5>MENU</h5>
            <ul>
            	<li><img src="img/letter.gif" align="absmiddle"> <a href="inicio.php">Mensagens</a></li>
            	<li><img src="img/camera.gif" align="absmiddle"> <a href="album.php">Álbum de fotos</a></li>
            	<li><img src="img/icon_comunidade.gif" height="16" align="absmiddle"> <a href="comunidade.php">Comunidades</a></li>
            	<li><img src="img/icon_comunidade.gif" height="16" align="absmiddle"> <a href="aliados.php">Aliados</a></li>
            </ul>
            <br />
            
           	<h5>CONTEÚDO</h5>
           	<ul>
                <li><img src="img/artigo.png" align="absmiddle"> <a href="artigos.php">Artigos</a></li>
                <li><img src="img/ico.materias.gif" align="absmiddle"> <a href="tutoriais.php">Tutoriais</a></li>
                <li><img src="img/forum.png" align="absmiddle"> <a href="javascript: AbreForm('chat/chat/', 800, 650);">Chat</a></li>
            </ul>
            <br />
            
            <h5>ALIADO</h5>
            <ul id="lista-aliados">
            
            </ul>
            
        </div>