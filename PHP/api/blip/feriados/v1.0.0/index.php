<?php
    //Toda a segurança da API é feita via WSO2.

    require '/var/www/html/_backend/db.php';
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET,POST,DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );
    $requestMethod = $_SERVER["REQUEST_METHOD"];

    //Valida que o usuário somente usará os metodos permitidos
    //Retorna erro 405 caso esteja utilizando outros metodos


    switch($requestMethod){

        case 'GET':
            $date = $_GET["date"];
            if(strlen($date) == 0){
                //Valida que não foi passada uma data via campo "date" na query e faz a validação com o dia de hoje
                $timezone  = -3; 
                $todayDate =  gmdate("d/m/Y", time() + 3600*($timezone+date("I")));
                getHoliday($todayDate);
            }else if( strlen($date) == 10){
                //Faz a validação se é feriado com o dia passado
                getHoliday($date);
            }else{
                //Data passada no formato errado
                header("HTTP/1.1 400 Bad request");
                exit();
            }
            break;
        case 'POST':
            $post_data = json_decode(file_get_contents("php://input"));
            $date = $post_data->date;
            $code = $post_data->code;
            $name = $post_data->name;
            $description = $post_data->description;

            if(strlen($date) != 10 || strlen($code) == 0 || strlen($name) == 0 || strlen($description) == 0 || $code == 5 || $code == 6){
                /*Faz a validaçao basica dos dados.
                    DATE =  Deve possuir 10 caracteres -> 12/10/2010
                    CODE =  Não pode estar vazio e tem que ser diferente de 5 ou 6
                    NAME =  Não pode estar vazio
                    DESCRIPTION =  Não pode estar vazio
                */
                header("HTTP/1.1 400 Bad request");
                exit();
            }else{
                $timezone  = -3; 
                $todayDate =  gmdate("Y/m/d", time() + 3600*($timezone+date("I")));
                switch ($code){
                    case 1: $type = 'Feriado Nacional';
                    break;
                    case 4: $type = 'Facultativo';
                    break;
                    case 3: $type = 'Feriado Municipal';
                    break;
                    case 9: $type = 'Dia Convencional';
                    default:
                        $type = 'Outros';
                }
                $query = "INSERT INTO `atividades`.`blip_feriados` (`date`, `name`, `type`, `typeCode`, `description`, `updatedAt`, `start`, `end`) VALUES ('$date', '$name','$type' ,'$code', '$description', '$todayDate', '00:00', '23:59');";
                insert($query);
            }
            break;
        case 'DELETE';
            $date = $_GET["date"];
            if(strlen($date) == 0){
                //Valida que não foi passada uma data via campo "date" na query e faz a validação com o dia de hoje
                $timezone  = -3; 
                $todayDate =  gmdate("d/m/Y", time() + 3600*($timezone+date("I")));
                delHoliday($todayDate);
            }else if( strlen($date) == 10){
                //Faz a validação se é feriado com o dia passado
                delHoliday($date);
            }else{
                //Data passada no formato errado
                header("HTTP/1.1 400 Bad request");
                exit();
            }
            break;
        default:
            header("HTTP/1.1 405 method not allowed");
            exit();
    }

    function getHoliday($date){

        $query ="SELECT * FROM atividades.blip_feriados  where `date` = '$date' and  (`typeCode` <> '5' or `typeCode` <> '6');";
        $result = select($query);
        if($result == 0){
            header("HTTP/1.1 404 not found");
            exit();
        }
        else{
            header("HTTP/1.1 200 OK");
            echo(
                json_encode (
                    array(
                        "date" => $date,
                        "name"=>$result[0][name],
                        "code"=>$result[0][typeCode],
                        "description"=>$result[0][description]
                    )
                )
            );
        }
    }
    function delHoliday($date){
        $query ="SELECT * FROM atividades.blip_feriados  where `date` = '$date' and  (`typeCode` <> '5' or `typeCode` <> '6');";
        $result = select($query);
        if($result == 0){
            header("HTTP/1.1 404 not found");
            exit();
        }else{
            $query = "DELETE FROM `atividades`.`blip_feriados` WHERE (`date` = '$date');";
            $result = select($query);
            if($result == 0){
                header("HTTP/1.1 200 OK");
                exit();
            }
        }
    }

?>