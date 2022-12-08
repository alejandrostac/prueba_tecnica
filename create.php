<?php
    require('config.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        header_remove();
        if(isset($data['name'], $data['lastname'], $data['email'], $data['pass'])){
            if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                if(isset($data['img'])){
                    $img_name = bin2hex(random_bytes(5)) .".jpg";
                    $content = file_get_contents($data['img']);
                    file_put_contents('images/'.$img_name, $content);
                    try {
                        $result = $mysqli->query("INSERT INTO usuarios (name, lastname, email, pass,img) VALUES ('".$data['name']."', '".$data['lastname']."', '".$data['email']."', '".$data['pass']."', '".$img_name."');");
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
                }else{
                    try {
                        $result = $mysqli->query("INSERT INTO usuarios (name, lastname, email, pass) VALUES ('".$data['name']."', '".$data['lastname']."', '".$data['email']."', '".$data['pass']."');");
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
                    'status' => 405,
                    'error_message' => "Email invalido"
                )); 
            }
        }else{
            echo json_encode(array(
                'status' => 405,
                'error_message' => "Solicitud incompleta"
            ));
        }
    }else{
        echo json_encode(array(
            'status' => 400,
            'error_message' => "Bad Request"
        ));
    }
?>