<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == "DELETE") {
// include database and object files
    include_once '../config/database.php';
    include_once '../objects/product.php';

//get database connection
    $dbcon = new Database();
    $db = $dbcon->getDBConnection();

//    prepare product object

    $prod = new Product($db);

    //get the id of the of the product to be edit
    $data = json_decode(file_get_contents("php://input"));
var_dump($data);
    if (!empty($data)) {
        //set ID property of product to be edited
        $prod->id = $data->id;


        //update the product
        if ($prod->delete()) {

            //set response code - 200 ok
            http_response_code(200);

            //tell the user
            echo json_encode(array("message" => "Product was updated"));
        }
        //if unable to update the product, tell the user
    }

    else{
        //set response code - 503 service unavailable
        http_response_code(503);

        //tell the user
        echo json_encode(array("message"=>"bad request"));
    }

}else{
    //set response code - 503 service unavailable
    http_response_code(503);

    //tell the user
    echo json_encode(array("message"=>"Unable to update product"));
}
