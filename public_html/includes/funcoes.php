<?php
// ANEL
function exibeAnel($cod_anel){
	if($cod_anel == 1){
		//$img_anel = "anel_terra.gif";
		$cod_anel = "Anel da Terra";
	}
	if($cod_anel == 2){
		//$img_anel = "anel_agua.gif";
		$cod_anel = "Anel da Água";
	}
	if($cod_anel == 3){
		//$img_anel = "anel_vento.gif";
		$cod_anel = "Anel do Vento";
	}
	if($cod_anel == 4){
		//$img_anel = "anel_fogo.gif";
		$cod_anel = "Anel do Fogo";
	}
	if($cod_anel == 5){
		//$img_anel = "anel_vazio.gif";
		$cod_anel = "Anel do Vazio";
	}
	return $cod_anel;
}

// GERA PAGINAÇÃO
function GeraPaginacao($pagina, $totalLink, $totalPagina, $parametros = ''){
	if($totalPagina > 1):
		echo "<p>";
		
		if($pagina > 1):
			echo " <a href='?pagina=1" . $parametros . "' class='pag'>Primeira</a> ";
		endif;
		
		for($i = $pagina - $totalLink; $i <= $pagina - 1; $i++){
			if($i > 0):
				echo " <a href='?pagina=" . $i . $parametros . "' class='pag'>" . $i . "</a> ";
			endif;
		}
		
		echo " <span class='paginacao_atual'>" . $pagina . "</span> ";
		
		for($i = $pagina + 1; $i <= $pagina + $totalLink; $i++){
			if($i <= $totalPagina):
				echo "<a href='?pagina=" . $i . $parametros . "' class='pag'>" . $i . "</a> ";
			endif;
		}
		
		if($pagina < $totalPagina):
			echo "<a href='?pagina=" . $totalPagina . $parametros . "' class='pag'>&Uacute;ltima</a>";
		endif;
		
		echo "</p>";
		echo "<br />";
	endif;
}

// GERA PAGINAÇÃO PROXIMO / ANTERIOR
function GeraPaginacaoSimples($pagina, $totalPagina, $parametros = ''){
	if($totalPagina > 1):
		
		if($pagina > 1):
			echo "<div class='pag-left'>";
			echo "<a href='?pagina=". ($pagina - 1) . $parametros . "' class='pag'>Jogadas mais recentes</a> ";
			echo "</div>";
		endif;
		
		if($pagina < $totalPagina):
			echo "<div class='pag-right'>";
			echo "<a href='?pagina=" . ($pagina + 1) . $parametros . "' class='pag'>Jogadas anteriores</a>";
			echo "</div>";
		endif;
		
	endif;
}

// GERA PAGINAÇÃO DEMANDA (TWITTER)
function GeraPaginacaoDemanda($pagina, $totalPagina, $label = 'Jogadas anteriores', $maximo = 10){
	if($totalPagina > 1 && $pagina <= $maximo):
		if($pagina < $totalPagina):
			echo "<div class='pag-full'>";
			echo "<a href='javascript:void(0);' rel='" . ($pagina + 1) . "' class='pag'>" . $label . "</a>";
			echo "</div>";
		endif;
	endif;
}

// GERA PAGINAÇÃO DEMANDA (TWITTER)
function GeraPaginacaoDemandaParam($pagina, $totalPagina, $label = 'Jogadas anteriores', $param, $maximo = 10){
	if($totalPagina > 1 && $pagina <= $maximo):
		if($pagina < $totalPagina):
			echo "<div class='pag-full'>";
			echo "<a href='javascript:void(0);' rel='pagina=" . ($pagina + 1) . $param . "' class='pag'>" . $label . "</a>";
			echo "</div>";
		endif;
	endif;
}

// RETIRAR
error_reporting(0);
ini_set('display_errors', FALSE); 

function obterPasta(){

	$arrPasta = split( "/", $_SERVER["REQUEST_URI"] );
	$pasta = $arrPasta[ sizeof( $arrPasta ) - 2 ];

	return $pasta;
}

// *************************** //
// Herança das funções do site //
// *************************** //
//include( "../../sys-php/func.php" );


// *************************** //
// Funções exclusivas do admin //
// *************************** //
function gerarNome( $nome_orig ){
	$nome_orig = str_replace( " ", "_", $nome_orig );
	$nome_orig = str_replace( "-", "_", $nome_orig );

	return "{" . date( "YmdHis" ) . time() . "}_" . strtolower( $nome_orig );
}

// --------------
	// comboPorVetor 
	// --------------
	function comboPorVetor( $nome, $ele_cod, $ele_desc, $selecionado, $classe, $tam ){
		echo "<select name='$nome' class='$classe' style='width:$tam;' id='$nome'>";
		for( $i = 0; $i < sizeof($ele_cod); $i++ ){
			echo "<option value='$ele_cod[$i]' ";
			if( $ele_cod[$i] == $selecionado ) echo "SELECTED";
			echo ">$ele_desc[$i]</option>";
		}
		echo "</select>";
	}
	
function comboPorTabela( $nome, $tabela, $col_cod, $col_desc, $selecionado, $classe, $tam, $item0, $onChange ){
	$query_temp = "";
	$query_temp = "SELECT $col_cod, $col_desc FROM $tabela ORDER BY $col_desc ASC";
	$rsMontaCombo = @mysql_query( $query_temp );
	if( $rsMontaCombo ){
		echo "<select name='$nome' id='$nome' class='$classe' style='width:$tam;' " . ( $onChange != "" ? "onChange='" . $onChange . "'" : "" ) . ">";
		if( $item0 ) echo "<option value='0'>:: Selecione ::</option>";
		while( $l = mysql_fetch_row( $rsMontaCombo ) ){
			echo "<option value='$l[0]' ";
			if( $l[0] == $selecionado ) echo "SELECTED";
			echo ">".utf8_encode($l[1])."</option>";
		}
		echo "</select>";
	}
	else echo $php_errormsg;
}

function comboPorTabela2( $nome, $tabela, $col_cod, $col_desc, $selecionado, $classe, $tam, $item0, $onChange ){
	$query_temp = "";
	$query_temp = "SELECT $col_cod, $col_desc FROM $tabela ORDER BY $col_desc ASC";
	$rsMontaCombo = @mysql_query( $query_temp );
	if( $rsMontaCombo ){
		echo "<select name='$nome' id='$nome' class='$classe' style='width:$tam;' " . ( $onChange != "" ? "onChange='" . $onChange . "'" : "" ) . ">";
		if( $item0 ) echo "<option value=''>:: Selecione ::</option>";
		while( $l = mysql_fetch_row( $rsMontaCombo ) ){
			echo "<option value='$l[0]' ";
			if( $l[0] == $selecionado ) echo "SELECTED";
			echo ">".utf8_encode($l[1])."</option>";
		}
		echo "</select>";
	}
	else echo $php_errormsg;
}	



function LimpaEntrada($valor)
{
	$valor = str_replace("--", "", $valor);
	$valor = str_replace("\"", "", $valor);
	$valor = str_replace("'", "", $valor);		
	$valor = str_replace("<", "", $valor);
	$valor = str_replace(">", "", $valor);
	$valor = str_replace(";", "", $valor);
			
	return $valor;
}

function converterData( $data_orig, $destino ){
	if( $data_orig == "" ) return "";
		if( $destino == "in" ){
			$new_Data = explode('/',$data_orig);
			return $new_Data[2]."-".$new_Data[1]."-".$new_Data[0];
			}
		if( $destino == "out"){
			$new_Data = explode('-',$data_orig);
			return $new_Data[2]."/".$new_Data[1]."/".$new_Data[0];
			}
		return "";
}

function converterValor( $valor_orig, $destino )
{
	if( $valor_orig == "" ) return "";
	if( $destino == "in" ){
		$new_Valor = str_replace( ",", ".", $valor_orig );
		return $new_Valor;
	}
	if( $destino == "out"){
		$new_Valor = str_replace( ".", ",", $valor_orig );
		return $new_Valor;
	}
	return "";
}

function converterHora( $horario ){
	$new_horario = explode(':',$horario);
	$retorno = $new_horario[0].":".$new_horario[1];
	return $retorno;
}

function insereLog( $usr, $acao, $itens, $con ){
	$acao = substr( $acao, 0, 255 );
	$sql = "INSERT INTO franca_logs( codigo_usuario, acao, itens, data, hora ) VALUES( $usr, '" . strtolower( $acao ) . "', '" . strtolower( $itens ) . "', '" . date( "Y-m-d" ) . "', '" . date( "H:i:s" ) . "' )";
	mysql_query( $sql, $con );
}

function msgbox( $txt, $pag ){
	
		echo "<script>";
		echo "alert( '$txt' );";
		if( $pag != "" ) echo "opener.location.href= '$pag';";
		echo "this.close();";
		echo "</script>";
	
	}	

// Forma Decimal para gravar no BD
function moeda($get_valor) { 
	$source = array('.', ',');  
	$replace = array('', '.'); 
	$valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto 
	return $valor; //retorna o valor formatado para gravar no banco 
}

function uploadImg($tmp, $nome, $tipo, $largura, $pasta, $redimensiona){
	// VERIFICA SE FAZ O REDIMENSIONAMENTO
	if($redimensiona == 'S'):
		if(($tipo == 'image/jpg') or ($tipo == 'image/jpeg') or ($tipo == 'image/pjpeg')):
			$img = imagecreatefromjpeg($tmp);
		endif;
		if($tipo == 'image/gif'):
			$img = imagecreatefromgif($tmp);
		endif;
		if($tipo == 'image/png' || $tipo == 'image/x-png'):
			$img = imagecreatefrompng($tmp);
		endif;
		$x = imagesx($img);
		$y = imagesy($img);
		if($x > $largura):
			$altura = ($largura * $y) / $x; // CALCULA ALTURA
		else:
			$largura = $x;
			$altura = $y; //NÃO CALCULA ALTURA
		endif;
		$nova = imagecreatetruecolor($largura, $altura);
		
		if($tipo == 'image/png' || $tipo == 'image/x-png'):
			imagealphablending($nova, false);
			imagesavealpha($nova, true);
			$transparent = imagecolorallocatealpha($nova, 255, 255, 255, 127);
			imagefilledrectangle($nova, 0, 0, $largura, $altura, $transparent);
		endif;
		
		imagecopyresampled($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);
		
		if(($tipo == 'image/jpg') or ($tipo == 'image/jpeg') or ($tipo == 'image/pjpeg')):
			imagejpeg($nova, "$pasta/$nome");
			imagedestroy($nova);
			imagedestroy($img);
		endif;
		if($tipo == 'image/gif'):
			$img = imagegif($nova, "$pasta/$nome");
			imagedestroy($nova);
		endif;
		if($tipo == 'image/png' || $tipo == 'image/x-png'):
			imagepng($nova, "$pasta/$nome");
			imagedestroy($nova);
			imagedestroy($img);
		endif;
	else:
		move_uploaded_file($tmp,"$pasta/$nome"); // Aqui, efetuamos o upload, propriamente dito
	endif;
	return $nome;
}

// Função Ano
function Year($date)
{
	$explode = explode(" ", $date);
	$date = $explode[0];
	if ( isset($explode[1])) { $hour = $explode[1];}  else { $hour = NULL; }
	$date = explode("-", ereg_replace('/', '-', $date));
	$date = $date[2];
	return $date;
}

// diferença entre duas datas
function dateDiff($sDataInicial, $sDataFinal)
{
	$sDataI = explode("-", $sDataInicial);
	$sDataF = explode("-", $sDataFinal);
	
	$nDataInicial = mktime(0, 0, 0, $sDataI[1], $sDataI[2], $sDataI[0]);
	$nDataFinal = mktime(0, 0, 0, $sDataF[1], $sDataF[2], $sDataF[0]);
	
	return ($nDataInicial > $nDataFinal) ? floor(($nDataInicial - $nDataFinal)/86400) : floor(($nDataFinal - $nDataInicial)/86400);
}

// Formata DATA
function formataDataBanco($date){
	$explode = explode(" ", $date);
	$date = $explode[0];
	if ( isset($explode[1])) { $hour = $explode[1];}  else { $hour = NULL; }
	$date = explode("-", ereg_replace('/', '-', $date));
	$date = $date[2] . "-" . $date[1] . "-" . $date[0];
	return $date;
}
?>