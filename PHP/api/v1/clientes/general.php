<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET,POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    /*
        url example = "sgi.intelbras.com.br/api/v1/clientes/general.php?cpf=09489601918&qtd=5" as an GET
        return example in JSON
            {
                "CPF":"09489601918"
                "Quantas vezes já ligou":"33"
                "Campanha que mais liga":"OUTDOOR"
                "Ligações por mes":"12"
                "":""
                "Ligação Nro":"1"
                    "Data":"2019-12-25 08:33"
                    "Numero":"48996260373"
                    "Campanha":"OUTDOOR"
                    "Atendente":"th052070"
                    "Tempo em fila":"0 min"
                    "Tempo em ligacao" :"10 min"
                    "nota":"7"
                    "Gravacao":"G:/Gravador/Destino/intelbras/CFTV/2017-03-23/c3158e25dd6841d3a936e6badde58048-67853eba37ee46519bb4926426cac3b4-iu050085.mp3"
                    "Quem desligou?":"A"
                    "Protocolo telefonico":"201910101319002"
                "Ligação Nro":"2"
                    "Data":"2019-12-25 08:33"
                    "Numero":"48996260373"
                    "Campanha":"OUTDOOR"
                    "Atendente":"th052070"
                    "Tempo em fila":"0 min"
                    "Tempo em ligacao" :"10 min"
                    "nota":"7"
                    "Gravacao":"G:/Gravador/Destino/intelbras/CFTV/2017-03-23/c3158e25dd6841d3a936e6badde58048-67853eba37ee46519bb4926426cac3b4-iu050085.mp3"
                    "Quem desligou?":"A"
                    "Protocolo telefonico":"201910101319002"
            }
    
    */


    /* mySQL STUFF
            - DATA                  - Tabela iteracoes
            - Numero                - Tabela iteracoes
            - Campanha              - Tabela iteracoes
            - Atendente             - Tabela iteracoes
            - tempo em fila         - Tabela callcycles
            - tempo em ligacao      - Tabela callcycles
            - nota                  - Tabela pesquisa
            - gravacao              - Tabela recordings
            - desligamento          - Tabela recordings
            - protcolo telefonico   - Tabela recordings
    */


    $qtd = $_GET["qtd"];
    $cpf = $_GET["cpf"];

    if ($cpf.sizeof() != 11 ){
        /* Return some JSON error by CPF size */
    }
    if ($qtd.sizeof() = 0){
        /* Return some JSON error by qtd size */
    }


    $sql_SESSIONID    = "SELECT `SESSIONID` FROM `teclan_hist_intelbras`.`interacoes` WHERE `CPF`='$cpf' AND `SESSIONID` REGEXP 'TLC' ORDER BY `ID` DESC  LIMIT $qtd;"
    $sql_howManyCalls = " SELECT COUNT(`SESSIONID`) FROM `teclan_hist_intelbras`.`interacoes` WHERE `CPF`='$cpf' AND `SESSIONID` REGEXP 'TLC' ORDER BY `ID` DESC;"

?>