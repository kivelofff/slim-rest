<?php

use App\Models\Db;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true,true);

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('It works!');
    return $response;
});

$app->get('/all', function (Request $request, Response $response) {
    $sql = "SELECT * FROM customers";

    try {
        $db = new Db();
        $conn = $db->connect();
        $stmt = $conn->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});

$app->post('/add', function (Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $name = $data["name"];
    $email = $data["email"];
    $phone = $data["phone"];

    $sql = "INSERT INTO customers (name, email, phone) VALUES (:name, :email, :phone)";

    try {
        $db = new Db();
        $conn = $db->connect();

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        $result = $stmt->execute();

        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});
$app->put(
    '/update/{id}',
    function (Request $request, Response $response, array $args)
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $name = $data["name"];
        $email = $data["email"];
        $phone = $data["phone"];

        $sql = "UPDATE customers SET
                name = :name,
                email = :email,
                phone = :phone
        WHERE id = $id";

        try {
            $db = new Db();
            $conn = $db->connect();

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);

            $result = $stmt->execute();

            echo "Update successful!";
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(200);
        } catch (PDOException $e) {
            $error = array(
                "message" => $e->getMessage()
            );

            $response->getBody()->write(json_encode($error));
            return $response
                ->withHeader('content-type', 'application/json')
                ->withStatus(500);
        }
    });

    $app->delete('/delete/{id}', function (Request $request, Response $response, array $args) {
       $id = getValue($args["id"]);

       $sql = "DELETE FROM customers WHERE id = $id";

       try {
           $db = new Db();
           $conn = $db->connect();

           $stmt = $conn->prepare($sql);
           $result = $stmt->execute();

           $response->getBody()->write(json_encode($result));
           return $response
               ->withHeader('content-type', 'application/json')
               ->withStatus(200);
       } catch (PDOException $e) {
           $error = array(
               "message" => $e->getMessage()
           );

           $response->getBody()->write(json_encode($error));
           return $response
               ->withHeader('content-type', 'application/json')
               ->withStatus(500);
       }
    });

$app->run();

function getValue($value) {
    return htmlspecialchars($value);
}
