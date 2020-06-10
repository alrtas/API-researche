<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET,POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode( '/', $uri );

    include '../../../_includes/connection_db_icc.php';
    $url_id = $_GET["id"];
    $blip = $_GET["blip"];

    function getProduto($url_id, $blip){
        $conn = Connect();
        $id         = $url_id; 
        $id_blip    = $blip;
        $queryByID  = ("SELECT `CAMPANHA`  FROM teclan_cpfs_priorizados.produtos WHERE `CODIGO` = '$id' " );
        $sucess     = $conn->query($queryByID);
        $fila       = mysqli_fetch_assoc($sucess);
        if(sizeof($fila)==0){
            echo json_encode( array("404"));
        }else{
            if($id_blip  == '1'){  
                switch ($fila['CAMPANHA']) {
                    case 'CONDOMINIAL':
                        $fila['CAMPANHA'] = 'Comunicação condominial';
                        break;
                    case 'ALARMES':
                        $fila['CAMPANHA'] = 'Alarmes';
                    break;
                    case 'ANALOGICO':
                        $fila['CAMPANHA'] = 'Centrais analógicas';
                    break;
                    case 'BACK_OFFICE':
                        $fila['CAMPANHA'] = 'default';
                    break;
                    case 'CFTV':
                        $fila['CAMPANHA'] = 'CFTV e Sistema Cloud';
                    break;
                    case 'EASY HOME':
                        $fila['CAMPANHA'] = 'Telefones e Radiocomunicadores';
                    break;
                    case 'ENERGIA':
                        $fila['CAMPANHA'] = 'Energia solar, fontes e nobreaks';
                    break;
                    case 'IAUT':
                        $fila['CAMPANHA'] = 'Controle de acesso corporativo';
                    break;
                    case 'IFIRE':
                        $fila['CAMPANHA'] = 'Incêndio e Iluminação';
                    break;  
                    case 'MIBO':
                        $fila['CAMPANHA'] = 'Linha Mibo';
                    break; 
                    case 'OUTDOOR':
                        $fila['CAMPANHA'] = 'Rádios switches e linha PON';
                    break;  
                    case 'PORTEIROS':
                        $fila['CAMPANHA'] = 'Fechaduras e porteiros residenciais';
                    break;  
                    case 'WIRELESS INDOOR':
                        $fila['CAMPANHA'] = 'Roteadores e Access Points';
                    break;  
                    case 'HIBRIDO':
                        $fila['CAMPANHA'] = 'Centrais hibridas';
                    break;  
                    default:
                        $fila['CAMPANHA'] = 'Produto inexistente na base';
                        break;
                }
                echo(trim(json_encode( array($fila) ), '[]'));
            };
            if($id_blip  == '0' or $id_blip == null){
                echo(trim(json_encode( array($fila) ), '[]')); 
            }
        };
    };
        getProduto($url_id, $blip);
?>