$(document).ready(function(){
	// BUSCA CEP EM AJAX
	$("#cep").blur(function(){
		$("#endereco").val("aguarde, carregando...")
		$("#bairro").val("aguarde, carregando...")
		$("#cidade").val("aguarde, carregando...")
		
		q = $("#cep").val()
		$.getScript("http://cep.republicavirtual.com.br/web_cep.php?cep="+q+"&formato=javascript",function(){
			tipo_logradouro = unescape(resultadoCEP.tipo_logradouro)
			if(tipo_logradouro != ""){
				tipo_logradouro = tipo_logradouro + ", "
			}
			endereco = unescape(resultadoCEP.logradouro)
			bairro = unescape(resultadoCEP.bairro)
			cidade = unescape(resultadoCEP.cidade)
			estado = unescape(resultadoCEP.uf)
			
			$("#endereco").val(tipo_logradouro+endereco)
			$("#bairro").val(bairro)
			$("#cidade").val(cidade)
			$("#estado").val(estado)
		});
	});
});