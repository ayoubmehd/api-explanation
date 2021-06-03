<?php
try {
    //code...
    $cnx = new PDO("mysql: host=localhost; dbname=students", "root", "");
} catch (\Throwable $th) {
    //throw $th;
    var_dump($th);
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');

function handle_request()
{
    return json_decode(file_get_contents('php://input'));
}



function getStudents()
{
    global $cnx;
    if ($_SERVER["REQUEST_METHOD"] !== "GET") return;
    // var_dump($requestBody->firstname);
    $res = $cnx->prepare("SELECT * FROM student");
    $res->execute();

    if (!empty($res->errorInfo()[1])) {
        echo json_encode(["message" => "Error"]);
        return;
    }

    echo json_encode(["data" => $res->fetchAll(PDO::FETCH_ASSOC)]);
}
function create($firstname, $lastname)
{

    global $cnx;
    if ($_SERVER["REQUEST_METHOD"] !== "POST") return;

    $res = $cnx->prepare("INSERT INTO student (firstname, lastname) VALUES(?,?)");
    $res->execute([$firstname, $lastname]);

    if (empty($res->errorInfo()[1])) {
        echo json_encode(["message" => "student added succesfully"]);
    }
}

function update($firstname, $lastname, $id)
{
    global $cnx;
    if ($_SERVER["REQUEST_METHOD"] !== "PUT") return;

    $res = $cnx->prepare("UPDATE student SET firstname = ?, lastname = ? WHERE id = ?");
    $res->execute([$firstname, $lastname, $id]);

    if (empty($res->errorInfo()[1])) {
        echo json_encode(["message" => "student updated succesfully"]);
    }
}

function delete($id)
{
    global $cnx;
    if ($_SERVER["REQUEST_METHOD"] !== "DELETE") return;

    $res = $cnx->prepare("DELETE FROM student WHERE id = ?");
    $res->execute([$id]);

    if (empty($res->errorInfo()[1])) {
        echo json_encode(["message" => "student deleted succesfully"]);
    }
}

$requestBody = handle_request();
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET": {
            getStudents();
            break;
        }
    case "POST": {
            create($requestBody->firstname, $requestBody->lastname);
            break;
        }
    case "PUT": {

            update($requestBody->firstname, $requestBody->lastname, $_GET["id"]);

            break;
        }
    case "DELETE": {
            delete($_GET["id"]);
            break;
        }
}
