<?php

function obterPasta(){

	$arrPasta = split( "/", $_SERVER["SCRIPT_NAME"] );
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
	$rsMontaCombo = @mysqli_query($GLOBALS["___mysqli_ston"],  $query_temp );
	if( $rsMontaCombo ){
		echo "<select name='$nome' id='$nome' class='$classe' style='width:$tam;' " . ( $onChange != "" ? "onChange='" . $onChange . "'" : "" ) . ">";
		if( $item0 ) echo "<option value='0'>:: Selecione ::</option>";
		while( $l = mysqli_fetch_row( $rsMontaCombo ) ){
			echo "<option value='$l[0]' ";
			if( $l[0] == $selecionado ) echo "SELECTED";
			echo ">$l[1]</option>";
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
	mysqli_query( $con ,  $sql);
}

function msgbox( $txt, $pag ){
	
		echo "<script>";
		echo "alert( '$txt' );";
		if( $pag != "" ) echo "opener.location.href= '$pag';";
		echo "</script>";
	
	}	

?>