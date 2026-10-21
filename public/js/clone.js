contar_elemento = 0;

function clonarElemento(elemento_id) {

	contar_elemento++;
	var elemento = document.getElementById('original_'+elemento_id);
	var secao = document.getElementById(elemento_id);
	var clone = elemento.cloneNode(true);
	clone.id = 'clone_'+elemento_id+'_'+contar_elemento;
	secao.appendChild(clone);

	//Limpas os campos

	var camposInputClonados = clone.getElementsByTagName('input');
	var camposSelectClonados = clone.getElementsByTagName('select');
	var camposTextoClonados = clone.getElementsByTagName('textarea');

	for(il = 0; il < camposInputClonados.length; il++){

		var inputId = camposInputClonados[il].id;
		camposInputClonados[il].id = inputId+'_'+contar_elemento;
		camposInputClonados[il].value = '';
	}

	for(sl = 0; sl < camposSelectClonados.length; sl++){
		var selectId = camposSelectClonados[sl].id;
		camposSelectClonados[sl].id = selectId+'_'+contar_elemento;
		camposSelectClonados[sl].value = '';
	}

	for(tl = 0; tl < camposTextoClonados.length; tl++){
		var textoId = camposTextoClonados[tl].id;
		camposTextoClonados[tl].id = textoId+'_'+contar_elemento;
		camposTextoClonados[tl].value = '';
	}

    afterClone();
}

function deletarElemento(este,elemento_id) {

	var elemento = document.getElementById(este);

	if(este != 'original_'+elemento_id) {
		elemento.remove();
	} else {
		window.alert('O elemento não pode ser removido!');

		//Limpa os campos

		var camposInputElemento = elemento.getElementsByTagName('input');
		var camposSelectElemento = elemento.getElementsByTagName('select');
		var camposTextoElemento = elemento.getElementsByTagName('textarea');

		for(ci = 0; ci < camposInputElemento.length; ci++){
			camposInputElemento[ci].value = '';
		}

		for(cs = 0; cs < camposSelectElemento.length; cs++){
			camposSelectElemento[cs].selectedIndex = 0;
		}

		for(ct = 0; ct < camposTextoElemento.length; ct++){
			camposTextoElemento[ct].value = '';
		}

		//Ignora a ação

		return false;
	}

}

function limparElemento(este) {

	var elemento = document.getElementById(este);

	//Limpa os campos

	var camposInputElemento = elemento.getElementsByTagName('input');
	var camposSelectElemento = elemento.getElementsByTagName('select');
	var camposTextoElemento = elemento.getElementsByTagName('textarea');

	for(ci = 0; ci < camposInputElemento.length; ci++){
		camposInputElemento[ci].value = '';
	}

	for(cs = 0; cs < camposSelectElemento.length; cs++){
		camposSelectElemento[cs].selectedIndex = 0;
	}

	for(ct = 0; ct < camposTextoElemento.length; ct++){
		camposTextoElemento[ct].value = '';
	}

}
