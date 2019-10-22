<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include database abd objcets files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/product.php';


if($_SERVER['REQUEST_METHOD']=="GET"){
//instantiate database and product object
    $database = new Database();
    $db = $database->getDBConnection();

//instialize object
    $prod = new product($db);


        //get keywords
    $keywords = isset($_GET["k-word"])? $_GET["k-word"]:"";

  if(!empty($keywords)&&!is_null($keywords)){
        //query products
        $stmt = $prod->search($keywords);
        $num= $stmt->rowcount();
        if ($num>0) {
            // products array
            $products_arr=array();
            $products_arr["records"]=array();

            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);


                $product_item = array(
                    "id"=> $id,
                    "name"=> $name,
                    "description"=> html_entity_decode($description),
                    "price"=> $price,
                    "category_id" => $category_id,
                    "category_name" => $category_name
                );
                array_push( $products_arr["records"], $product_item);    }

            // set response code - 200 OK
            http_response_code(200);

            // show products data
            echo json_encode($products_arr);

        }
}else{
      header("HTTP/1.1 503 Access Forbidden");
      header("Content-Type: text/plain");
      echo "Please enter key words";
  }

}else{
    header("HTTP/1.1 403 Access Forbidden");
    header("Content-Type: text/plain");
    echo "Bad Request";
}