// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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

function AbreScrollForm(vfnURL, vfnLargura, vfnAltura){
	var vT;
	var vL;
	var vNome;
	var vVetor;
	vT = (window.screen.height/2) - (vfnAltura/2);
	vT = (vT-40);
	vL = (window.screen.width/2) - (vfnLargura/2);	
	window.open(vfnURL, '_blank', 'scrollbars=yes width='+ vfnLargura +', height='+ vfnAltura +', left='+ vL +', top='+vT);
}


// Função para inserir barras
      function NumeroBarras(String){
        var Posicao=0, cont;
        for(cont=0;cont<=String.value.length;cont++){
          if(String.value.substr(cont,1)=='/')
            Posicao++;
        }
        return Posicao;
      }

      function Data_onkeydown(Controle) {
        //Backspace(8), Tab(9), Esc(27), End(35), Home(36), Esq(37), Dir(39), Del(46), Barra[/](111, 193, 223)
        if 
        (
          (isNaN(String.fromCharCode(event.keyCode)))
          &&
          ((event.keyCode<96)||(event.keyCode>105))
          &&
          ((event.keyCode != 8)&&(event.keyCode != 27)&&(event.keyCode != 35)&&(event.keyCode != 36)&&(event.keyCode != 37)&&(event.keyCode != 39)&&(event.keyCode != 46)&&(event.keyCode != 111)&&(event.keyCode != 193)&&(event.keyCode != 223))
        )
          event.returnValue = false;
        else
          if ((event.keyCode != 111)&&(event.keyCode != 193)&&(event.keyCode != 223)){
            if (((Controle.value.length == 2)||(Controle.value.length == 5))&&(event.keyCode != 8)&&(event.keyCode != 9))
              Controle.value = Controle.value + '/';
          }
          else{
            if((Controle.value.length==0)||(Controle.value.substr(Controle.value.length-1,1)=='/')||(NumeroBarras(Controle)>=2))
              event.returnValue = false;
            else
              if (!((Controle.value.length == 2)||(Controle.value.length == 5)||(Controle.value.length >= 10)))
                Controle.value = Controle.value.substring(0,Controle.value.length-1) + '0' + Controle.value.substring(Controle.value.length-1, Controle.value.length)
          }
      }
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function MM_reloadPage(init) {  //reloads the window if Nav4 resized
	  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
		document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
	  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
	}
	MM_reloadPage(true);
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function MM_findObj(n, d) { //v4.01
	  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	  if(!x && d.getElementById) x=d.getElementById(n); return x;
	}
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function MM_showHideLayers() { //v6.0
	  var i,p,v,obj,args=MM_showHideLayers.arguments;
	  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
		if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
		obj.visibility=v; }
	}
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function MudaCorMenu(src, cor){
		src.className=cor;
	}
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	function novo(url, largura, altura)
	{
		var topo, left
		topo = (window.screen.height / 2) - (altura / 2);
		iLeft = (window.screen.width / 2) - (largura / 2);
		window.open(url, "Form", "width="+ largura +", height="+ altura +", left="+ left +", top=" + topo);
	}	
	
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    function mudaStatus(codigo, status, tabela){

    document.location.href = "index.php?codigo="+codigo+"&status="+status+"&tabela="+tabela+"&acao=status";

    }
	
	function mudaStatus3(codigo, codigo_noticia, status, tabela){

    document.location.href = "exibe_comentarios.php?codigo="+codigo+"&codigo_noticia="+codigo_noticia+"&status="+status+"&tabela="+tabela+"&acao=status";

    }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function mudaStatus2(codigo, status, tabela,semana){

    document.location.href = "programa.php?codigo="+codigo+"&status="+status+"&tabela="+tabela+"&acao=status&semana="+semana;

    }
	
	function excluir(codigo, tabela){

    document.location.href = "index.php?codigo="+codigo+"&tabela="+tabela+"&acao=excluir";

    }
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function excluir2(codigo, tabela, semana){

    document.location.href = "programa.php?codigo="+codigo+"&tabela="+tabela+"&semana="+semana+"&acao=excluir";

    }

//	+++++++++++++++++
	function buscar(tipo){
	var chave = document.cBusca_Novo.cChave.value;
	var campo = document.cBusca_Novo.cCampo.value;
	document.location.href = "index.php?TipoBusca="+tipo+"&cChave="+chave+"&cCampo="+campo;
	}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function buscar2(tipo,semana){
	var chave = document.cBusca_Novo.cChave.value;
	var campo = document.cBusca_Novo.cCampo.value;
	document.location.href = "programa.php?TipoBusca="+tipo+"&cChave="+chave+"&cCampo="+campo+"&semana="+semana;
	}

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function validaDestaque()
{
	if((document.frm_programacao.destaque.value == "n") || (document.frm_programacao.destaque.value == "s"))
	 {
		document.frm_programacao.destaque.style.background = "#FFFFFF";
		document.frm_programacao.submit(); 
	 }
	 else
	 {		
		alert("Digite s ou n !");
		document.frm_programacao.destaque.style.background = "#fc5a5a";
		document.frm_programacao.destaque.focus();
	 } 	
}
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 function validaFormulario()
 {
	
	if(document.frm_banner.descricao.value == "")
	 {
		 alert("Preencha todos os campos !");
		 document.frm_banner.descricao.style.background = "#fc5a5a";
		 document.frm_banner.descricao.focus();
	 }
	 else
	 {
		document.frm_banner.descricao.style.background = "#FFFFFF";
		validaLink();
	 } 
 }

 function validaLink()
 {
	if(document.frm_banner.link.value == "")
	 {
		 alert("Preencha todos os campos !");
		 document.frm_banner.link.style.background = "#fc5a5a";
		 document.frm_banner.link.focus();
	 }
	 else
	 {
		document.frm_banner.link.style.background = "#FFFFFF";
		document.frm_banner.submit();
	 } 
 }
 
 
 
 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function validaFormulario2()
 {
	 if(document.frm_noticias.titulo.value == "")
	 {
		 alert("Preencha todos os campos !");
		 document.frm_noticias.titulo.style.background = "#fc5a5a";
		 document.frm_noticias.titulo.focus();
	 }
	else
	 {
		document.frm_noticias.titulo.style.background = "#FFFFFF";
		document.frm_noticias.submit();
	 } 
 }
 
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function validaSemana()
{
 if ((document.frm_programacao.semana.value > 7) || (document.frm_programacao.semana.value < 1))	
 {
 	alert("Apenas números entre 1 e 7");
 	document.frm_noticias.titulo.style.background = "#fc5a5a";
	document.frm_noticias.titulo.focus();
 }
 else
 	{
		document.frm_programacao.semana.style.background = "#FFFFFF";
		validaDestaque();
	 } 
}

 
