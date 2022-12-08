<?php
    require('config.php');
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        header_remove();
        if(isset($data['id'])){
            $result = $mysqli->query("SELECT * FROM usuarios WHERE id = '".$data['id']."'");
            $rows = mysqli_num_rows($result);
            if($rows > 0){
                $row = $result -> fetch_row();
                if($row[5] != null){
                    if(file_exists("images/".$row[5])){
                        unlink("images/".$row[5]);
                    }
                }
                $result = $mysqli->query("DELETE FROM `usuarios` WHERE id = '".$data['id']."';");
                echo json_encode(array(
                    'status' => 200,
                    'message' => "Registro eliminado correctamente"
                ));
            }else{
                echo json_encode(array(
                    'status' => 400,
                    'error_message' => "No existe el usuario"
                ));    
            }
        }else{
            echo json_encode(array(
                'status' => 400,
                'error_message' => "No se envio el ID a eliminar"
            ));
        }
    }else{
        echo json_encode(array(
            'status' => 400,
            'error_message' => "Bad Request"
        ));
    }
?>