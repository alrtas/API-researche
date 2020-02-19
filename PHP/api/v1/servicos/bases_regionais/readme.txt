Como usar

METODO = POST
URL = http://sgi.intelbras.com.br/api/v1/servicos/bases_regionais/
  Headers
    Content-Type = applciation/json
  Body 
    raw => JSON
	{  
	    "id" : "{{$guid}}",
	    "method" : "get",
	    "resource" : "all"
	}


Parametro resource pode variar entre "all","status","sla","bases"


RESPOSTA = STATUS 200 OK
{
    "id": "e6e1433b-70d0-4fcb-8bf7-cd4eeb99a5ae",
    "method": "get",
    "Resource": "all",
    "SLA" : "90",
    "Bases": 30,
    "Operacao": {
        "Status": "Operacional",
        "Atualizada": "18-02-2020 22:14:42",
        "SLA": 100,
        "Online": 27,
        "Offline": 3,
        "Bases_Offline": [
            {
                "Base": "6540529844",
                "Status": "Indisponivel"
            },
            {
                "Base": "6740639182",
                "Status": "Indisponivel"
            },
            {
                "Base": "1940421779",
                "Status": "Nao Atendida"
            }
	]
    }
}

