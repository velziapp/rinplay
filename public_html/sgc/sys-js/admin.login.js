// JavaScript Document
function preparaAlteraSenha(){
	var o;
			
	o = document.getElementById( "alteraLinha0" );
	o.style.display = "none";
	o = document.getElementById( "alteraLinha1" );
	o.style.display = "table-row";
	o = document.getElementById( "alteraLinha2" );
	o.style.display = "table-row";
	o = document.getElementById( "alteraLinha3" );
	o.style.display = "table-row";
	o = document.getElementById( "novasenha" );
	o.focus();
	o = document.getElementById( "actID" );
	o.value = "alterar";
	o = null;
}

function cancelaAlteraSenha(){
	var o;
			
	o = document.getElementById( "alteraLinha0" );
	o.style.display = "table-row";
	o = document.getElementById( "alteraLinha1" );
	o.style.display = "none";
	o = document.getElementById( "alteraLinha2" );
	o.style.display = "none";
	o = document.getElementById( "alteraLinha3" );
	o.style.display = "none";
	o = document.getElementById( "novasenha" );
	o.focus();
	o = document.getElementById( "actID" );
	o.value = "entrar";
	o = null;
}

function preparaEsqueciSenha(){
	var f;
	
	f = document.getElementById( "frmLogin" );
	f.actID.value = "lembrar";
	f.submit();
}
