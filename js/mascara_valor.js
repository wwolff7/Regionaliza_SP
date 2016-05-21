	function Mascara(o,f){
        v_obj=o
        v_fun=f
        setTimeout("execmascara()",1)
    }
    
    function execmascara(){
        v_obj.value=v_fun(v_obj.value)
    }
    
    function leech(v){
        v=v.replace(/o/gi,"0")
        v=v.replace(/i/gi,"1")
        v=v.replace(/z/gi,"2")
        v=v.replace(/e/gi,"3")
        v=v.replace(/a/gi,"4")
        v=v.replace(/s/gi,"5")
        v=v.replace(/t/gi,"7")
        return v
    }

	/*Função que permite apenas numeros*/
	function Integer(v){
			return v.replace(/\D/g,"")
	}
	
	/*Função que padroniza telefone (11) 4184-1241*/
	function Telefone(v){
			v=v.replace(/\D/g,"")                            
			v=v.replace(/^(\d\d)(\d)/g,"($1) $2") 
			v=v.replace(/(\d{4})(\d)/,"$1-$2")      
			return v
	}
	
	/*Função que padroniza telefone (11) 41841241*/
	function TelefoneCall(v){
			v=v.replace(/\D/g,"")                            
			v=v.replace(/^(\d\d)(\d)/g,"($1) $2")   
			return v
	}
	
	/*Função que padroniza CPF*/
	function Cpf(v){
			v=v.replace(/\D/g,"")                                   
			v=v.replace(/(\d{3})(\d)/,"$1.$2")         
			v=v.replace(/(\d{3})(\d)/,"$1.$2")         																						 
			v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")

			return v
	}

/*Função que padroniza CEP*/
	function Cep(v){
			v=v.replace(/D/g,"")                            
			v=v.replace(/^(\d{5})(\d)/,"$1-$2") 
			return v
	}
	
	/*Função que padroniza CNPJ*/
	function Cnpj(v){
			v=v.replace(/\D/g,"")                              
			v=v.replace(/^(\d{2})(\d)/,"$1.$2")      
			v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") 
			v=v.replace(/\.(\d{3})(\d)/,".$1/$2")              
			v=v.replace(/(\d{4})(\d)/,"$1-$2")                        
			return v
	}
	
	/*Função que permite apenas numeros Romanos*/
	function Romanos(v){
			v=v.toUpperCase()                        
			v=v.replace(/[^IVXLCDM]/g,"") 
			
			while(v.replace(/^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/,"")!="")
					v=v.replace(/.$/,"")
			return v
	}
	
	/*Função que padroniza o Site*/
	function Site(v){
			v=v.replace(/^http:\/\/?/,"")
			dominio=v
			caminho=""
			if(v.indexOf("/")>-1)
					dominio=v.split("/")[0]
					caminho=v.replace(/[^\/]*/,"")
					dominio=dominio.replace(/[^\w\.\+-:@]/g,"")
					caminho=caminho.replace(/[^\w\d\+-@:\?&=%\(\)\.]/g,"")
					caminho=caminho.replace(/([\?&])=/,"$1")
			if(caminho!="")dominio=dominio.replace(/\.+$/,"")
					v="http://"+dominio+caminho
			return v
	}

	/*Função que padroniza DATA*/
	function Data(v){
			v=v.replace(/\D/g,"") 
			v=v.replace(/(\d{2})(\d)/,"$1/$2") 
			v=v.replace(/(\d{2})(\d)/,"$1/$2") 
			return v
	}
	
	/*Função que padroniza DATA*/
	function Hora(v){
			v=v.replace(/\D/g,"") 
			v=v.replace(/(\d{2})(\d)/,"$1:$2")  
			return v
	}
	
	function Apenas_Numeros(caracter){
		var nTecla = 0;
		if (document.all) {
			nTecla = caracter.keyCode;
		} else {
			nTecla = caracter.which;
		}
		if ((nTecla > 44 && nTecla < 47) 
			|| (nTecla> 47 && nTecla <58)
			|| nTecla == 8 || nTecla == 127
			|| nTecla == 0 || nTecla == 9  // 0 == Tab
			|| nTecla == 13) { // 13 == Enter
			return true;
		} else {
			return false;
		}
	}

	function Apenas_Numeros2(caracter){
		var nTecla = 0;
		if (document.all) {
			nTecla = caracter.keyCode;
		} else {
			nTecla = caracter.which;
		}
		if ((nTecla == 46) 
			|| (nTecla> 47 && nTecla <58)
			|| nTecla == 8 || nTecla == 127
			|| nTecla == 0 || nTecla == 9  // 0 == Tab
			|| nTecla == 13) { // 13 == Enter
			return true;
		} else {
			return false;
		}
	}	
	
	/*Função que padroniza Area*/
	function Area(v){
			v=v.replace(/\D/g,"") 
			v=v.replace(/(\d)(\d{2})$/,"$1.$2") 
			return v	
	}
