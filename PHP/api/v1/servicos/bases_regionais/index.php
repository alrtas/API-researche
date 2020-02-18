<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET,POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    $num_bases = '30';
    include '../../../_includes/connection.php';



    $data = json_decode(file_get_contents("php://input"));

    echo $data->id;

    $url_id = $_GET["id"];
    $return = $url_id;
/*
    echo json_encode( $return );

    

    $data = json_decode(file_get_contents("php://input"))
    $product = $data->id
    echo(trim(json_encode( 'teste:'$product ), '[]'));

    /*function getNumBases(){ 
        $conn = Connect();
        $query_number_bases = "SELECT * FROM atividades.telefonia WHERE `data` LIKE CONCAT((DATE_FORMAT(NOW(), '%d-%m-%Y')), '%') GROUP BY `BASE` ORDER BY `data` DESC;";
        $success     = $conn->query($query_number_bases);
        $fila       = mysqli_fetch_assoc($sucess);
        if(sizeof($fila)==0){
            echo json_encode( array("404"));
        }else{
            $num_bases = sizeof($fila);
        }
    }

    function getSla(){

    }

    function getStatus(){
        $conn = Connect();
        $query_status_bases_online = "SELECT 
                                        `data`,
                                        `base`,
                                        `status`,
                                        CASE `status`
                                            WHEN 'Atendida' THEN 1
                                        END AS 'online'
                                        FROM
                                            atividades.telefoni a
                                        WHERE
                                            `data` LIKE CONCAT((DATE_FORMAT(NOW(), '%d-%m-%Y')), '%')
                                        ORDER BY `data` DESC
                                        LIMIT $num_bases;
                                    ";

   }*/
?>