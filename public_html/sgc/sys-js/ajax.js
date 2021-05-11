function criaRequisicao( obj ){

	if( window.XMLHttpRequest ) obj.req = new XMLHttpRequest();
	else if( window.ActiveXObject ) obj.req = new ActiveXObject( "Microsoft.XMLHTTP" ); 

}

function mandaRequisicao( obj, url, meth, sync, callback ){

	if( obj.req ){
		obj.req.onreadystatechange = eval( callback );
		obj.req.open( meth, url, sync );
		obj.req.send( null );			
	}

}

function destroiRequisicao( obj ){
	
	obj.req = null;
	obj = null;
	
}