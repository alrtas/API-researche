<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET,POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    $num_bases = '30';
    /* Connects with database */
    include '../../../../_includes/connection.php';
    /*GET URL DATA*/ 
    $post_url_id = $_GET["id"];
    $post_url_method = $_GET["method"];
    $post_url_resource = $_GET["resource"];
    /*POST URL DATA*/ 
    $post_data = json_decode(file_get_contents("php://input"));
    $post_url_id = $post_data->id;
    $post_url_method = $post_data->method;
    $post_url_resource = $post_data->resource;

    switch ($post_url_method) {
        case 'get':
            switch ($post_url_resource) {
                case 'all':
                    echo(
                        json_encode (
                            array(
                                "ID" => $post_url_id,
                                "method" => $post_url_method,
                                "Resource" => $post_url_resource,
                                "Bases"=>getNumBases(),
                                "Operacao" => getStatus()
                            )
                        )
                    );
                    break;
                
                default:
                    http_response_code(404);
                    echo(
                        json_encode(
                            array(
                            "Erro"=> 'Parametro resource errado',
                            "Parametro" => $post_url_resource
                            )
                        )
                    );
                    break;
            }
        break;
            
        default:
        http_response_code(404);
        echo(
            json_encode(
                array(
                "Erro"=> 'Parametro method errado',
                "Parametro" => $post_url_method
                )
            )
        );
        break;
}
        
    

    function getArray($query_parse){
        $query = $query_parse;
        $conn = Connect();
        $resultQuery= $conn->query($query);
        while ($row = mysqli_fetch_assoc($resultQuery)){ $array[] = $row; };
        $conn->close();
        if (sizeof($array) != 0){return $array; }
        else{return $array;}  
    }   
    
    function getNumBases(){ 
        $query_number_bases = "SELECT * FROM atividades.telefonia WHERE `data` LIKE CONCAT((DATE_FORMAT(NOW(), '%d-%m-%Y')), '%') GROUP BY `BASE` ORDER BY `data` DESC;";
        $fila = getArray($query_number_bases);
        $sum_bases = 0;
        if(sizeof($fila)==0){return 0;}
        else{foreach($fila as $row){$sum_bases = $sum_bases+1;}return $sum_bases;}
    }

    function getSla(){
        $query_status = "SELECT `data`,`base`,`status`,CASE `status`WHEN 'Atendida' THEN 1 END AS 'online' FROM `atividades`.`telefonia` WHERE `data` LIKE CONCAT((DATE_FORMAT(NOW(), '%d-%m-%Y')), '%') ORDER BY `data` DESC;";
    }

    function getStatus(){
        $bases = getNumBases();
        $bases_online = 0;
        $query_status = "SELECT `data`,`base`,`status`,CASE `status`WHEN 'Atendida' THEN 1 END AS 'online' FROM `atividades`.`telefonia` WHERE `data` LIKE CONCAT((DATE_FORMAT(NOW(), '%d-%m-%Y')), '%') ORDER BY `data` DESC LIMIT $bases;";
        $fila = getArray($query_status);
        foreach($fila as $row){
            if ($row["status"] == "Atendida"){$bases_online = $bases_online +1;}
            else{
                $bases_offline=array('Base'=>$row["base"]);
            }
        }
        if($bases_online == $bases){$status = 'Operacional';}
        elseif($bases_online == 1){$status = 'Altamente degradado';}
        elseif($bases_online == 0){$status = 'Parado';}
        else {$status = 'Degradado';}

        $slaDay = ($bases_online/$bases)*100;
        $offline = ($bases-$bases_online);

        return array(
            "Status"=>$status,
            "Atualizada"=>$fila[0]["data"],
            "SLA"=>$slaDay,
            "Online"=>$bases_online,
            "Offline"=>$offline,
            "Bases_Offline"=>$bases_offline
        );
    }

?>