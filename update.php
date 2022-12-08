<?php
    require('config.php');
    if ($_SERVER["REQUEST_METHOD"] == "PUT") {
        $json = file_get_contents('php://input');
        // Converts it into a PHP object 
        $data = json_decode($json, true);
        header_remove();
        if(isset($data['id'])){
            if(!isset($data['name']) && !isset($data['lastname']) && !isset($data['email']) && !isset($data['pass'])){
                echo json_encode(array(
                    'status' => 400,
                    'message' => "No se recibio datos para actualizar"
                ));
            }else{
                
                $query = "UPDATE usuarios SET ";
                if(isset($data['name'])){
                    $query.= "name='".$data['name']."' ,";
                }
                if(isset($data['lastname'])){
                    $query.= "lastname='".$data['lastname']."' ,";
                }
                if(isset($data['email'])){
                    if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                        $query.= "email='".$data['email']."' ,";
                    }else{
                        echo json_encode(array(
                            'status' => 400,
                            'message' => "Correo no valido"
                        ));
                        exit();
                    }
                }
                if(isset($data['pass'])){
                    $query.= "pass='".$data['pass']."' ,";
                }
                if(isset($data['img'])){
                    $img_name = bin2hex(random_bytes(5)) .".jpg";
                    $content = file_get_contents($data['img']);
                    file_put_contents('images/'.$img_name, $content);
                    $query.= "img='".$img_name."' ,";
                }
                $query = substr_replace($query ,"",-1);
                $query .= "Where id = '".$data['id']."'";
                try {
                    //$result = $mysqli->query("UPDATE `usuarios` SET `name`='holi',`lastname`='holi',`email`='prueba@gmail.com',`pass`='123',`img`='prueba.jpg' WHERE 'id' = '1'");
                    $result = $mysqli->query($query);
                    echo json_encode(array(
                        'status' => 200,
                        'message' => "Registro guardado correctamente"
                    ));
                }catch (Exception $e){
                    $error = $e->getMessage();
                    echo json_encode(array(
                        'status' => 400,
                        'error_message' => $error
                    ));
                }
            }
        }else{
            echo json_encode(array(
                'status' => 400,
                'message' => "No se recibio un ID para actualizar"
            ));
        }
    }else{
        echo json_encode(array(
            'status' => 400,
            'error_message' => "Bad Request"
        ));
    }
?>