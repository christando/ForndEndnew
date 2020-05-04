<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get('/about/', function (Request $request, Response $response, array $args) {
        // kirim pesan ke log
        $this->logger->info("ada orang yang mengakses '/about/'");
    
        // tampilkan pesan
        echo "ini adalah halaman about!";
        
    });

    $app->get("/buah/", function (Request $request, Response $response){
        $sql = "SELECT * FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/buah/{id}", function (Request $request, Response $response, $args){
        $product_id = $args["id"];
        $sql = "SELECT * FROM products WHERE product_id=:product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":product_id" => $product_id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });
    
    $app->get("/buah/search/", function (Request $request, Response $response, $args){
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM products WHERE name LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });
    
    $app->post("/buah/", function (Request $request, Response $response){
    
        $new_buah = $request->getParsedBody();
    
        $sql = "INSERT INTO products (product_id, name, price, image, description) VALUE (:product_id, :name, :price, :image, :description)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":product_id" => uniqid(),
            ":name" => $new_buah["name"],
            ":price" => $new_buah["price"],
            ":image" => $new_buah["image"],
            ":description" => $new_buah["description"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
    
    
    $app->put("/buah/{id}", function (Request $request, Response $response, $args){
        $product_id = $args["id"];
        $new_buah = $request->getParsedBody();
        $sql = "UPDATE products SET name=:name, price=:price, image=:image, description=:description WHERE product_id=:product_id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":product_id" => $product_id,
            ":name" => $new_buah["name"],
            ":price" => $new_buah["price"],
            ":image" => $new_buah["image"],
            ":description" => $new_buah["description"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
    
    
    $app->delete("/buah/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "DELETE FROM products WHERE product_id=:product_id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":product_id" => $id
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
};
