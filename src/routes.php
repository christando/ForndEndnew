<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// Enable CORS

header("Access-Control-Allow-Origin: http://localhost:5163");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");


return function (App $app) {

    
    $container = $app->getContainer();

    $app->get('/excel', function (Request $request, Response $response, array $args) use ($container) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

        $spreadsheet = $reader->load("uploads/RPS_SI2413_RekayasaPerangkatLunak.xlsx");

        $d = $spreadsheet->getSheet(1)->toArray();

        $str = $spreadsheet->getSheet(1)->getCell('B7')->getValue();

        echo $str;
        return count($str);
    });

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

    $app->get("/buah/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM products";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/buah/{id}", function (Request $request, Response $response, $args) {
        $product_id = $args["id"];
        $sql = "SELECT * FROM products WHERE product_id=:product_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":product_id" => $product_id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/buah/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM products WHERE name LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->post("/api/fe/regis", function (Request $request, Response $response) {

        $new_regis = $request->getParsedBody();

        $sql = "INSERT INTO pengguna_rumah (username, password, notelp) VALUE (:username, :password, :notelp)";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":username" => $new_regis["username"],
            ":password" => $new_regis["password"],
            ":notelp" => $new_regis["notelp"],
            // ":registeras" => $new_regis["registeras"]
        ];

        if ($stmt->execute($data))
            return $response->withJson(["status" => "success", "data" => "1"], 200);

        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });


    // $app->put("/buah/{id}", function (Request $request, Response $response, $args) {
    //     $product_id = $args["id"];
    //     $new_buah = $request->getParsedBody();
    //     $sql = "UPDATE products SET name=:name, price=:price, image=:image, description=:description WHERE product_id=:product_id";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":product_id" => $product_id,
    //         ":name" => $new_buah["name"],
    //         ":price" => $new_buah["price"],
    //         ":image" => $new_buah["image"],
    //         ":description" => $new_buah["description"]
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success", "data" => "1"], 200);

    //     return $response->withJson(["status" => "failed", "data" => "0"], 200);
    // });


    // $app->delete("/buah/{id}", function (Request $request, Response $response, $args) {
    //     $id = $args["id"];
    //     $sql = "DELETE FROM products WHERE product_id=:product_id";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":product_id" => $id
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success", "data" => "1"], 200);

    //     return $response->withJson(["status" => "failed", "data" => "0"], 200);
    // });

    // $app->get("/api/progmob/mhs/{nim_progmob}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];
    //     $sql = "SELECT id,nama,nim,alamat,email,foto FROM progmob_mhs WHERE nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result = $stmt->fetchAll();
    //     return $response->withJson($result, 200);
    // });

    // $app->get("/api/progmob/mhs/{nim_progmob}/{nim_krs}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];
    //     $nim_krs = $args["nim_krs"];
    //     $sql = "SELECT id,nama,nim,alamat,email,foto FROM progmob_mhs WHERE nim_progmob = :nim_progmob AND nim = :nim_krs";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob, "nim" => $nim_krs]);
    //     $result = $stmt->fetchAll();
    //     return $response->withJson($result, 200);
    // });

    // $app->post("/api/progmob/mhs/create", function (Request $request, Response $response) {

    //     $new_mhs = $request->getParsedBody();

    //     $sql = "INSERT INTO progmob_mhs (nama,nim,alamat,email,foto,nim_progmob) VALUES (:nama, :nim, :alamat, :email, :foto, :nim_progmob)";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":nama" => $new_mhs["nama"],
    //         ":nim" => $new_mhs["nim"],
    //         ":alamat" => $new_mhs["alamat"],
    //         ":email" => $new_mhs["email"],
    //         ":foto" => "https://images.unsplash.com/photo-1508138221679-760a23a2285b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1567&q=80",
    //         ":nim_progmob" => $new_mhs["nim_progmob"],
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/mhs/createwithfoto", function (Request $request, Response $response) {

    //     $new_mhs = $request->getParsedBody();

    //     $uploadedFiles = $request->getUploadedFiles();
    //     $uploadedFile = $uploadedFiles['foto'];

    //     if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

    //         $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    //         // ubah nama file dengan id unik
    //         $filename = md5(uniqid() . mt_rand()) . "." . $extension;

    //         $directory = $this->get('settings')['upload_directory'];
    //         $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    //         // simpan nama file ke database
    //         $sql = "INSERT INTO progmob_mhs (nama,nim,alamat,email,foto,nim_progmob) VALUES (:nama, :nim, :alamat, :email, :foto, :nim_progmob)";
    //         $stmt = $this->db->prepare($sql);

    //         $data = [
    //             ":nama" => $new_mhs["nama"],
    //             ":nim" => $new_mhs["nim"],
    //             ":alamat" => $new_mhs["alamat"],
    //             ":email" => $new_mhs["email"],
    //             ":foto" => $request->getUri()->getBaseUrl() . "/uploads/" . $filename,
    //             ":nim_progmob" => $new_mhs["nim_progmob"],
    //         ];

    //         if ($stmt->execute($data))
    //             return $response->withJson(["status" => "success"], 200);
    //         else
    //             return $response->withJson(["status" => "failed"], 200);
    //     }
    // });

    // $app->post("/api/progmob/mhs/update", function (Request $request, Response $response) {

    //     $new_mhs = $request->getParsedBody();

    //     $sql = "UPDATE progmob_mhs SET nama = :nama, nim = :nim, alamat = :alamat, email = :email, foto = :foto
    //         WHERE nim = :nim_cari AND nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":nama" => $new_mhs["nama"],
    //         ":nim" => $new_mhs["nim"],
    //         ":alamat" => $new_mhs["alamat"],
    //         ":email" => $new_mhs["email"],
    //         ":foto" => "https://images.unsplash.com/photo-1508138221679-760a23a2285b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1567&q=80",
    //         ":nim_progmob" => $new_mhs["nim_progmob"],
    //         ":nim_cari" => $new_mhs["nim_cari"]
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/mhs/updatewithfoto", function (Request $request, Response $response) {

    //     $new_mhs = $request->getParsedBody();

    //     //cek kalau tidak upload foto
    //     if ($new_mhs["is_foto_update"] == '0') {
    //         // simpan nama file ke database
    //         $new_mhs = $request->getParsedBody();

    //         $sql = "UPDATE progmob_mhs SET nama = :nama, nim = :nim, alamat = :alamat, email = :email
    //             WHERE nim = :nim_cari AND nim_progmob = :nim_progmob";
    //         $stmt = $this->db->prepare($sql);

    //         $data = [
    //             ":nama" => $new_mhs["nama"],
    //             ":nim" => $new_mhs["nim"],
    //             ":alamat" => $new_mhs["alamat"],
    //             ":email" => $new_mhs["email"],
    //             ":nim_progmob" => $new_mhs["nim_progmob"],
    //             ":nim_cari" => $new_mhs["nim_cari"]
    //         ];

    //         if ($stmt->execute($data))
    //             return $response->withJson(["status" => "success"], 200);
    //         else
    //             return $response->withJson(["status" => "failed"], 200);
    //     } else {
    //         $uploadedFiles = $request->getUploadedFiles();
    //         $uploadedFile = $uploadedFiles['foto'];

    //         if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

    //             $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    //             // ubah nama file dengan id unik
    //             $filename = md5(uniqid() . mt_rand()) . "." . $extension;

    //             $directory = $this->get('settings')['upload_directory'];
    //             $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    //             // simpan nama file ke database
    //             $new_mhs = $request->getParsedBody();

    //             $sql = "UPDATE progmob_mhs SET nama = :nama, nim = :nim, alamat = :alamat, email = :email, foto = :foto
    //                 WHERE nim = :nim_cari AND nim_progmob = :nim_progmob";
    //             $stmt = $this->db->prepare($sql);

    //             $data = [
    //                 ":nama" => $new_mhs["nama"],
    //                 ":nim" => $new_mhs["nim"],
    //                 ":alamat" => $new_mhs["alamat"],
    //                 ":email" => $new_mhs["email"],
    //                 ":foto" => $request->getUri()->getBaseUrl() . "/uploads/" . $filename,
    //                 ":nim_progmob" => $new_mhs["nim_progmob"],
    //                 ":nim_cari" => $new_mhs["nim_cari"]
    //             ];

    //             if ($stmt->execute($data))
    //                 return $response->withJson(["status" => "success"], 200);
    //             else
    //                 return $response->withJson(["status" => "failed"], 200);
    //         }
    //     }
    // });

    // $app->post("/api/progmob/mhs/delete", function (Request $request, Response $response) {

    //     $new_mhs = $request->getParsedBody();

    //     $sql = "DELETE FROM progmob_mhs WHERE nim = :nim AND nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":nim" => $new_mhs["nim"],
    //         ":nim_progmob" => $new_mhs["nim_progmob"]
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/login", function (Request $request, Response $response, $args) {
    //     $data = $request->getParsedBody();
    //     $nimnik = $data["nimnik"];
    //     $password = $data["password"];

    //     $sql = "SELECT * FROM progmob_users WHERE nimnik = :nimnik AND password = :password";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nimnik" => $nimnik, "password" => $password]);
    //     $result = $stmt->fetchAll();

    //     return $response->withJson($result, 200);
    // });

    // $app->get("/api/progmob/dosen/{nim_progmob}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];
    //     $sql = "SELECT id,nama,nidn,alamat,email,gelar,foto FROM progmob_dosens WHERE nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result = $stmt->fetchAll();
    //     return $response->withJson($result, 200);
    // });

    // $app->get("/api/progmob/dosen/{nim_progmob}/{nidn}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];
    //     $nidn = $args["nidn"];
    //     $sql = "SELECT id,nama,nidn,alamat,email,gelar,foto FROM progmob_dosens WHERE nim_progmob = :nim_progmob AND nidn = :nidn";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob, "nidn" => $nidn]);
    //     $result = $stmt->fetchAll();
    //     return $response->withJson($result, 200);
    // });

    // $app->post("/api/progmob/dosen/create", function (Request $request, Response $response) {

    //     $new_dosen = $request->getParsedBody();

    //     $sql = "INSERT INTO progmob_dosens (nama,nidn,alamat,email,gelar,foto,nim_progmob) VALUES (:nama, :nidn, :alamat, :email, :gelar, :foto, :nim_progmob)";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":nama" => $new_dosen["nama"],
    //         ":nidn" => $new_dosen["nidn"],
    //         ":alamat" => $new_dosen["alamat"],
    //         ":email" => $new_dosen["email"],
    //         ":gelar" => $new_dosen["gelar"],
    //         ":foto" => "https://images.unsplash.com/photo-1508138221679-760a23a2285b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1567&q=80",
    //         ":nim_progmob" => $new_dosen["nim_progmob"],
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/dosen/update", function (Request $request, Response $response) {

    //     $new_dosen = $request->getParsedBody();

    //     $sql = "UPDATE progmob_dosens SET nama = :nama, nidn = :nidn, alamat = :alamat, email = :email, gelar = :gelar, foto = :foto
    //         WHERE nidn = :nidn_cari AND nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":nama" => $new_dosen["nama"],
    //         ":nidn" => $new_dosen["nidn"],
    //         ":alamat" => $new_dosen["alamat"],
    //         ":email" => $new_dosen["email"],
    //         ":gelar" => $new_dosen["gelar"],
    //         ":foto" => "https://images.unsplash.com/photo-1508138221679-760a23a2285b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1567&q=80",
    //         ":nim_progmob" => $new_dosen["nim_progmob"],
    //         ":nidn_cari" => $new_dosen["nidn_cari"],
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/dosen/createwithfoto", function (Request $request, Response $response) {

    //     $new_dosen = $request->getParsedBody();
    //     $uploadedFiles = $request->getUploadedFiles();
    //     $uploadedFile = $uploadedFiles['foto'];

    //     if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

    //         $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    //         // ubah nama file dengan id unik
    //         $filename = md5(uniqid() . mt_rand()) . "." . $extension;

    //         $directory = $this->get('settings')['upload_directory'];
    //         $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    //         // simpan nama file ke database
    //         $sql = "INSERT INTO progmob_dosens (nama,nidn,alamat,email,gelar,foto,nim_progmob) VALUES (:nama, :nidn, :alamat, :email, :gelar, :foto, :nim_progmob)";
    //         $stmt = $this->db->prepare($sql);

    //         $data = [
    //             ":nama" => $new_dosen["nama"],
    //             ":nidn" => $new_dosen["nidn"],
    //             ":alamat" => $new_dosen["alamat"],
    //             ":email" => $new_dosen["email"],
    //             ":gelar" => $new_dosen["gelar"],
    //             ":foto" => $request->getUri()->getBaseUrl() . "/uploads/" . $filename,
    //             ":nim_progmob" => $new_dosen["nim_progmob"],
    //         ];

    //         if ($stmt->execute($data))
    //             return $response->withJson(["status" => "success"], 200);
    //         else
    //             return $response->withJson(["status" => "failed"], 200);
    //     }
    // });

    // $app->post("/api/progmob/dosen/updatewithfoto", function (Request $request, Response $response) {
    //     $new_dosen = $request->getParsedBody();
    //     if ($new_dosen["is_foto_update"] == '0') {
    //         // simpan nama file ke database
    //         $sql = "UPDATE progmob_dosens SET nama = :nama, nidn = :nidn, alamat = :alamat, email = :email, gelar = :gelar
    //     WHERE nidn = :nidn_cari AND nim_progmob = :nim_progmob";

    //         $stmt = $this->db->prepare($sql);
    //         $data = [
    //             ":nama" => $new_dosen["nama"],
    //             ":nidn" => $new_dosen["nidn"],
    //             ":alamat" => $new_dosen["alamat"],
    //             ":email" => $new_dosen["email"],
    //             ":gelar" => $new_dosen["gelar"],
    //             ":nim_progmob" => $new_dosen["nim_progmob"],
    //             ":nidn_cari" => $new_dosen["nidn_cari"],
    //         ];

    //         if ($stmt->execute($data))
    //             return $response->withJson(["status" => "success"], 200);
    //         else
    //             return $response->withJson(["status" => "failed"], 200);
    //     } else {
    //         $uploadedFiles = $request->getUploadedFiles();
    //         $uploadedFile = $uploadedFiles['foto'];

    //         if ($uploadedFile->getError() === UPLOAD_ERR_OK) {

    //             $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    //             // ubah nama file dengan id unik
    //             $filename = md5(uniqid() . mt_rand()) . "." . $extension;

    //             $directory = $this->get('settings')['upload_directory'];
    //             $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    //             // simpan nama file ke database
    //             $sql = "UPDATE progmob_dosens SET nama = :nama, nidn = :nidn, alamat = :alamat, email = :email, gelar = :gelar, foto = :foto
    //             WHERE nidn = :nidn_cari AND nim_progmob = :nim_progmob";
    //             $stmt = $this->db->prepare($sql);

    //             $data = [
    //                 ":nama" => $new_dosen["nama"],
    //                 ":nidn" => $new_dosen["nidn"],
    //                 ":alamat" => $new_dosen["alamat"],
    //                 ":email" => $new_dosen["email"],
    //                 ":gelar" => $new_dosen["gelar"],
    //                 ":foto" => $request->getUri()->getBaseUrl() . "/uploads/" . $filename,
    //                 ":nim_progmob" => $new_dosen["nim_progmob"],
    //                 ":nidn_cari" => $new_dosen["nidn_cari"],
    //             ];

    //             if ($stmt->execute($data))
    //                 return $response->withJson(["status" => "success"], 200);
    //             else
    //                 return $response->withJson(["status" => "failed"], 200);
    //         }
    //     }
    // });

    // $app->post("/api/progmob/dosen/delete", function (Request $request, Response $response) {

    //     $new_dosen = $request->getParsedBody();

    //     $sql = "DELETE FROM progmob_dosens WHERE nidn = :nidn AND nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":nidn" => $new_dosen["nidn"],
    //         ":nim_progmob" => $new_dosen["nim_progmob"]
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // //===============================mata kuliah================================
    // $app->get("/api/progmob/matkul/{nim_progmob}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];
    //     $sql = "SELECT id,kode,nama,hari,sesi,sks FROM progmob_matkuls WHERE nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result = $stmt->fetchAll();
    //     return $response->withJson($result, 200);
    // });

    // $app->get("/api/progmob/matkul/{nim_progmob}/{kode}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];
    //     $kode = $args["kode"];
    //     $sql = "SELECT id,kode,nama,hari,sesi,sks FROM progmob_matkuls WHERE nim_progmob = :nim_progmob AND kode = :kode";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob, "kode" => $kode]);
    //     $result = $stmt->fetchAll();
    //     return $response->withJson($result, 200);
    // });

    // $app->post("/api/progmob/matkul/create", function (Request $request, Response $response) {

    //     $new_dosen = $request->getParsedBody();

    //     $sql = "INSERT INTO progmob_matkuls (kode,nama,hari,sesi,sks,nim_progmob) VALUES (:kode, :nama, :hari, :sesi, :sks, :nim_progmob)";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":kode" => $new_dosen["kode"],
    //         ":nama" => $new_dosen["nama"],
    //         ":hari" => $new_dosen["hari"],
    //         ":sesi" => $new_dosen["sesi"],
    //         ":sks" => $new_dosen["sks"],
    //         ":nim_progmob" => $new_dosen["nim_progmob"],
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/matkul/update", function (Request $request, Response $response) {

    //     $new_dosen = $request->getParsedBody();

    //     $sql = "UPDATE progmob_matkuls SET kode = :kode, nama = :nama, hari = :hari, sesi = :sesi, sks = :sks
    //         WHERE kode = :kode_cari AND nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":kode" => $new_dosen["kode"],
    //         ":nama" => $new_dosen["nama"],
    //         ":hari" => $new_dosen["hari"],
    //         ":sesi" => $new_dosen["sesi"],
    //         ":sks" => $new_dosen["sks"],
    //         ":nim_progmob" => $new_dosen["nim_progmob"],
    //         ":kode_cari" => $new_dosen["kode_cari"],
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/matkul/delete", function (Request $request, Response $response) {

    //     $new_dosen = $request->getParsedBody();

    //     $sql = "DELETE FROM progmob_matkuls WHERE kode = :kode AND nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":kode" => $new_dosen["kode"],
    //         ":nim_progmob" => $new_dosen["nim_progmob"]
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // //-----------------------------------------jadwal----------------------------------------------------
    // $app->get("/api/progmob/jadwal/{nim_progmob}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];
    //     $sql = "SELECT progmob_jadwals.id, m.nama as matkul, d.nama as dosen, d.nidn,m.hari,m.sesi,
    //     m.sks FROM progmob_matkuls m join progmob_jadwals on progmob_jadwals.id_matkul = m.id
    //     join progmob_dosens d on progmob_jadwals.id_dosen = d.id
    //     WHERE progmob_jadwals.nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result = $stmt->fetchAll();
    //     return $response->withJson($result, 200);
    // });

    // $app->post("/api/progmob/jadwal/create", function (Request $request, Response $response) {
    //     $jadwal = $request->getParsedBody();
    //     $sql = "INSERT INTO progmob_jadwals (id_matkul,id_dosen,nim_progmob) VALUES (:id_matkul, :id_dosen, :nim_progmob)";
    //     $stmt = $this->db->prepare($sql);
    //     $data = [
    //         ":id_matkul" => $jadwal["id_matkul"],
    //         ":id_dosen" => $jadwal["id_dosen"],
    //         ":nim_progmob" => $jadwal["nim_progmob"]
    //     ];
    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/jadwal/update", function (Request $request, Response $response) {
    //     $jadwal = $request->getParsedBody();
    //     $sql = "UPDATE progmob_jadwals SET id_dosen = :id_dosen, id_matkul = :id_matkul
    //         WHERE id = :id";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":id" => $jadwal["id"],
    //         ":id_dosen" => $jadwal["id_dosen"],
    //         ":id_matkul" => $jadwal["id_matkul"]
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // $app->post("/api/progmob/jadwal/delete", function (Request $request, Response $response) {
    //     $jadwal = $request->getParsedBody();

    //     $sql = "DELETE FROM progmob_jadwals WHERE id = :id";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":id" => $jadwal["id"]
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success"], 200);

    //     return $response->withJson(["status" => "failed"], 200);
    // });

    // //-----------------------------untuk dashboard----------------------------
    // $app->get("/api/progmob/dashboard/{nim_progmob}", function (Request $request, Response $response, $args) {
    //     $nim_progmob = $args["nim_progmob"];

    //     //get mhs
    //     $sql = "SELECT count(id) as jml_mhs FROM progmob_mhs WHERE nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result_mhs = $stmt->fetchAll();

    //     //get dosen
    //     $sql = "SELECT count(id) as jml_dosen FROM progmob_dosens WHERE nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result_dosen = $stmt->fetchAll();

    //     //get mk
    //     $sql = "SELECT count(id) as jml_mk FROM progmob_matkuls WHERE nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result_mk = $stmt->fetchAll();

    //     //get dosen
    //     $sql = "SELECT count(id) as jml_jadwal FROM progmob_jadwals WHERE nim_progmob = :nim_progmob";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":nim_progmob" => $nim_progmob]);
    //     $result_jadwal = $stmt->fetchAll();

    //     $result = array(
    //         'mahasiswa' => $result_mhs[0]['jml_mhs'],
    //         'dosen' => $result_dosen[0]['jml_dosen'],
    //         'matakuliah' => $result_mk[0]['jml_mk'],
    //         'jadwal' => $result_jadwal[0]['jml_jadwal'],
    //     );

    //     return $response->withJson($result, 200);
    // });

    // //------------------------------------progmob 2021 tentang petani------------------------------

    // $app->get("/petani/", function (Request $request, Response $response) {
    //     $sql = "SELECT * FROM master_petani";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetchAll();
    //     return $response->withJson(["status" => "success", "data" => $result], 200);
    // });

    // $app->get("/petani/{id}", function (Request $request, Response $response, $args) {
    //     $id = $args["id"];
    //     $sql = "SELECT * FROM master_petani WHERE id=:id";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute([":id" => $id]);
    //     $result = $stmt->fetch();
    //     return $response->withJson(["status" => "success", "data" => $result], 200);
    // });

    // $app->get("/petani/search/", function (Request $request, Response $response, $args) {
    //     $keyword = $request->getQueryParam("keyword");
    //     $sql = "SELECT * FROM master_petani WHERE nama LIKE '%$keyword%'";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute();
    //     $result = $stmt->fetchAll();
    //     return $response->withJson(["status" => "success", "data" => $result], 200);
    // });

    // $app->post("/petani/", function (Request $request, Response $response) {

    //     $petani = $request->getParsedBody();

    //     $sql = "INSERT INTO master_petani (nama, alamat, provinsi, kabupaten, kecamatan, kelurahan, 
    // nama_istri, jumlah_lahan, foto) VALUE (:nama, :alamat, :provinsi, :kabupaten, :kecamatan, :kelurahan,
    // :nama_istri, :jumlah_lahan, :foto)";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":nama" => $petani["nama"],
    //         ":alamat" => $petani["alamat"],
    //         ":provinsi" => $petani["provinsi"],
    //         ":kabupaten" => $petani["kabupaten"],
    //         ":kecamatan" => $petani["kecamatan"],
    //         ":kelurahan" => $petani["kelurahan"],
    //         ":nama_istri" => $petani["nama_istri"],
    //         ":jumlah_lahan" => $petani["jumlah_lahan"],
    //         ":foto" => $petani["foto"],
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success", "data" => "1"], 200);

    //     return $response->withJson(["status" => "failed", "data" => "0"], 200);
    // });


    // $app->put("/petani/{id}", function (Request $request, Response $response, $args) {
    //     $id = $args["id"];
    //     $petani = $request->getParsedBody();
    //     $sql = "UPDATE master_petani SET nama=:nama, alamat=:alamat, provinsi=:provinsi, kabupaten=:kabupaten, 
    // kecamatan=:kecamatan, kelurahan=:kelurahan, nama_istri=:nama_istri, jumlah_lahan=:jumlah_lahan, 
    // foto=:foto WHERE id=:id";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":id" => $id,
    //         ":nama" => $petani["nama"],
    //         ":alamat" => $petani["alamat"],
    //         ":provinsi" => $petani["provinsi"],
    //         ":kabupaten" => $petani["kabupaten"],
    //         ":kecamatan" => $petani["kecamatan"],
    //         ":kelurahan" => $petani["kelurahan"],
    //         ":nama_istri" => $petani["nama_istri"],
    //         ":jumlah_lahan" => $petani["jumlah_lahan"],
    //         ":foto" => $petani["foto"],
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success", "data" => "1"], 200);

    //     return $response->withJson(["status" => "failed", "data" => "0"], 200);
    // });


    // $app->delete("/petani/{id}", function (Request $request, Response $response, $args) {
    //     $id = $args["id"];
    //     $sql = "DELETE FROM master_petani WHERE id=:id";
    //     $stmt = $this->db->prepare($sql);

    //     $data = [
    //         ":id" => $id
    //     ];

    //     if ($stmt->execute($data))
    //         return $response->withJson(["status" => "success", "data" => "1"], 200);

    //     return $response->withJson(["status" => "failed", "data" => "0"], 200);
    // });

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    
    $app->add(function ($req, $res, $next) {
        $response = $next($req, $res);
        return $response
                ->withHeader('Access-Control-Allow-Origin', 'http://localhost:5163')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });
    
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
        $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
        return $handler($req, $res);
    });

    
};
