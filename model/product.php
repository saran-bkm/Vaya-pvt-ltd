<?php

require_once '../db_conn/db_conn.php';

$action = $_POST['action'];

switch ($action) {
  case "ADD":
    $p_name = $_POST['p_name'];
    $price  = $_POST['price'];
    $qty    = $_POST['qty'];

    try {
        $stmt = $conn->prepare("
            INSERT INTO products (product_name, price, qty)
            VALUES ('$p_name', '$price','$qty')
        ");

        $stmt->execute();

        $response = [
            "status" => true,
            "message" => "success"
        ];
        echo json_encode($response);

    } catch (PDOException $e) {
        $response = [
            "status" => false,
            "message" => $e->getMessage()
        ];
        echo json_encode($response);
    }

    break;

    case "LIST":

        try {

            $stmt = $conn->prepare("SELECT * FROM products");
            $stmt->execute();
            $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = "";
            foreach($datas as $d){
               $data .="<tr>";
               $data .="<td>".$d['id']."</td>";
               $data .="<td>".$d['product_name']."</td>";
               $data .="<td>".$d['price']."</td>";
               $data .="<td>".$d['qty']."</td>";
               $data .="<td>".$d['created_at']."</td>";
               $data .= "<td>
                <button class='btn btn-sm btn-primary' onclick='editProduct(" . $d['id'] . ")'>Edit</button>
                <button class='btn btn-sm btn-danger' onclick='deleteProduct(" . $d['id'] . ")'>Delete</button>
              </td>";
               $data .="</tr>";
            }

            $response = [
                "status" => true,
                "message" => "success",
                "data" => $data
            ];

            echo json_encode($response);

        } catch (PDOException $e) {

            $response = [
                "status" => false,
                "message" => $e->getMessage()
            ];
            echo json_encode($response);

        }

    break;

    case "DELETE":

        $id = $_POST['id'];

        try {
            $stmt = $conn->prepare("DELETE FROM products WHERE id = $id");
            $stmt->execute();

            $response = [
                "status" => true,
                "message" => "Product deleted successfully"
            ];
            echo json_encode($response);

        } catch (PDOException $e) {
            $response = [
                "status" => false,
                "message" => $e->getMessage()
            ];
            echo json_encode($response);
        }

    break;

     case "UPDATE":

        $id     = $_POST['id'];  
        $p_name = $_POST['p_name'];
        $price  = $_POST['price'];
        $qty    = $_POST['qty'];

        try {

            $stmt = $conn->prepare("
                UPDATE products 
                SET product_name = '$p_name', price = $price, qty = $qty
                WHERE id = $id
            ");

            $stmt->execute();

            $response = [
                "status"  => true,
                "message" => "updated successfully"
            ];
            echo json_encode($response);

        } catch (PDOException $e) {

            $response = [
                "status"  => false,
                "message" => $e->getMessage()
            ];
            echo json_encode($response);
        }

    break;



  default:
    echo "Invalid action!";
}

?>