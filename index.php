<?php
    require('config.php');
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $result = $mysqli->query("SELECT * FROM usuarios");
        $myArray = $result->fetch_all();
        header_remove();
        http_response_code(200);
        header("Content-Type: application/json");

        echo json_encode(array(
            'status' => 200,
            'data' => $myArray
        ));
    }else{
        echo json_encode(array(
            'status' => 400,
            'error_message' => "Bad Request"
        ));
    }
?>