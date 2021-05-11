        <div id="lateral-direita">
        	
            <h3>Publicidade</h3>
            <ul>
            <?php
				// DEFINE A DAT DE HOJE //
				$hoje = converterData(date("Y-m-d"),"out");
				// BUSCA OS BANNERS CADATRADOS //
				$banner = "SELECT * FROM rp_banners WHERE status = 'S' AND data_final >= '".$hoje."' ORDER BY rand() LIMIT 3";
				$rsb	= mysql_query($banner, $cx);
				while($l = mysql_fetch_assoc($rsb)){
				?>
					<li><a href="<?php echo $l['link']; ?>" target="_blank"><img src="sgc/uploads/<?php echo $l['arquivo']; ?>" width="250" border="0" id="imgBanner"></a></li>
				<?php
				}
			?>
            </ul>
            
        </div>