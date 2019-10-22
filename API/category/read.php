<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");

// database connection will be here
include_once '../config/database.php';
include_once '../objects/category.php';

if($_SERVER['REQUEST_METHOD'] == "POST") {
//instantiate databse and product object
$database= new Database();
$db = $database->getDBConnection();

//initialize object
$cat = new Category($db);


//querty product
$stmt = $cat->readAll();

$num = $stmt->rowCount();



//check if more that 0 record found
if($num >0){
    // products array
    $cat_arr=array();
    $cat_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $cat_item = array(
            "id"=> $id,
            "name"=> $name,
            "description"=> html_entity_decode($description)

        );
        array_push( $cat_arr["records"], $cat_item);
    }
// set response code - 200 OK
    http_response_code(200);
    // show products data in json format
    echo json_encode($cat_arr);
}
// no products found will be here
else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No categories found.")
    );
}
}else{
    // set response code - 404 Not found
    http_response_code(400);

    // tell the user no products found
    echo json_encode(
        array("message" => "Bad request!!!")
    );
}
//
