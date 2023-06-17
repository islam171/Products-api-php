<?php

    namespace app\Controllers;

    class Cart
    {
        public static function getAll()
        {
            require './app/configDB.php';
            $res = [];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
            }
            if (isset($userId)) {
                $products = $mysql->query("SELECT * FROM `Cart`");
                $productsList = [];
                while ($product = $products->fetch_assoc()) {
                    $productsList[] = $product;
                }
                http_response_code(200);
                $res = [
                  "status" => "success",
                  "products" => $productsList
                ];
            } else {
                http_response_code(401);
                $res = [
                  "status" => "Invalid token"
                ];
            }
            echo json_encode($res);
        }

        public static function getId($query)
        {
            require './app/configDB.php';
            $res = [];
            $productId = $query['productId'];
            if ($productId) {
                if (!empty($mysql)) {
                    $product = $mysql->query("SELECT * FROM `Products` WHERE `id` = '$productId'")->fetch_assoc();
                }
            }
            if (!is_null($product)) {
                http_response_code(200);
                $res = [
                  "status" => "success",
                  "product" => $product
                ];
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error"
                ];
            }
            echo json_encode($res);
        }
        public static function getUserId()
        {
            require './app/configDB.php';
            $res = [];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
            }
            if (isset($userId)) {
                $userId = $userId['userId'];
                $products = $mysql->query("SELECT * FROM `Cart` WHERE `userId` = '$userId'");

                $productsList = [];
                while ($product = $products->fetch_assoc()) {
                    $productsList[] = $product;
                }
                http_response_code(200);
                $res = [
                  "status" => "success",
                  "products" => $productsList
                ];
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error"
                ];
            }
            echo json_encode($res);
        }

        public static function getUserIdProducts()
        {
            require './app/configDB.php';
            $res = [];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
            }
            if (isset($userId)) {
                $userId = $userId['userId'];
                $cart = $mysql->query("SELECT * FROM `Cart` WHERE `userId` = '$userId'");


                $productsList = [];
                while ($product = $cart->fetch_assoc()) {
                    $productsId = $product['productsId'];
                    $products = $mysql->query("SELECT * FROM `Products` WHERE `id` = '$productsId'")->fetch_assoc();

                    $productsList[] = [
                      'id' =>  $products['id'],
                      'name' => $products['name'],
                      'companyId'=> $products['companyId'],
                      'categoryId'=> $products['categoryId'],
                      'price' => $products['price'],
                      'userId' => $product['userId'],
                      'createAt' => $product['createAt'],
                        'cartId' => $product['id']
                    ];
                }
                http_response_code(200);
                $res = [
                  "status" => "success",
                  "products" => $productsList
                ];
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error"
                ];
            }
            echo json_encode($res);
        }

        public static function deleteId($get)
        {
            require './app/configDB.php';
            $res = [];
            $id = $get['id'];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                if (!is_null($userId)) {
                    $userId = $userId['userId'];
                    $product = $mysql->query("DELETE FROM `Cart` WHERE `id` = '$id' and `userId` = '$userId'" );
                    if (!is_null($product)) {
                        http_response_code(204);
                        $res = [];
                    } else {
                        http_response_code(403);
                        $res = [
                          "status" => "error",
                          'message' => "Invalid token"
                        ];
                    }
                } else {
                    http_response_code(401);
                    $res = [
                      'status' => 'unauthenticated',
                      'message' => 'Invalid token'
                    ];
                }
            } else {
                http_response_code(401);
                $res = [
                  'status' => 'unauthenticated',
                  'message' => 'Missing token'
                ];
            }
            echo json_encode($res);
        }

        public static function create($json)
        {
            require './app/configDB.php';
            $res = [];
            $data = json_decode($json);
            $productId = $data->productId;
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                if ($userId) {
                    $userId = $userId['userId'];
                    $cart = $mysql->query(
                      "INSERT INTO `Cart` (`productsId`, `userId`) VALUES ('$productId', '$userId')"
                    );
                    if ($cart) {
                        http_response_code(200);
                        $res = [
                          "status" => "success",
                          "product" => $cart
                        ];
                    } else {
                        http_response_code(401);
                        $res = [
                          "status" => "error"
                        ];
                    }
                } else {
                    http_response_code(401);
                    $res = [
                      "status" => "error",
                      "message" => "Авторизуйтесь"
                    ];
                }
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error",
                  "message" => "Авторизуйтесь"
                ];
            }
            echo json_encode($res);
        }

        public static function getCatId($query)
        {
            require './app/configDB.php';
            $res = [];
            $catId = $query['catId'];
            echo $catId;
            if (!is_null($catId)) {
                if (isset($mysql)) {
                    $products = $mysql->query("SELECT * FROM `Products` WHERE `categoryId` = '$catId'");
                }
            }
            if ($products) {
                http_response_code(200);
                $productsList = [];
                while ($product = $products->fetch_assoc()) {
                    $productsList[] = $product;
                }
                $res = [
                  "status" => "success",
                  "product" => $productsList
                ];
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error"
                ];
            }
            echo json_encode($res);
        }

        public static function removeId($get)
        {
            require './app/configDB.php';
            $res = [];
            $productsId = $get['productsId'];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                if (!is_null($userId)) {
                    $userId = $userId['userId'];
                    $product = $mysql->query("DELETE FROM `Cart` WHERE `productsId` = '$productsId' and `userId` = '$userId'" );
                    if (!is_null($product)) {
                        http_response_code(204);
                        $res = [];
                    } else {
                        http_response_code(403);
                        $res = [
                          "status" => "error",
                          'message' => "Invalid token"
                        ];
                    }
                } else {
                    http_response_code(401);
                    $res = [
                      'status' => 'unauthenticated',
                      'message' => 'Invalid token'
                    ];
                }
            } else {
                http_response_code(401);
                $res = [
                  'status' => 'unauthenticated',
                  'message' => 'Missing token'
                ];
            }
            echo json_encode($res);
        }

        public static function clear($get)
        {
            require './app/configDB.php';
            $res = [];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                if (!is_null($userId)) {
                    $userId = $userId['userId'];
                    $product = $mysql->query("DELETE FROM `Cart` WHERE `userId` = '$userId'" );
                    if (!is_null($product)) {
                        http_response_code(204);
                        $res = [];
                    } else {
                        http_response_code(403);
                        $res = [
                          "status" => "error",
                          'message' => "Invalid token"
                        ];
                    }
                } else {
                    http_response_code(401);
                    $res = [
                      'status' => 'unauthenticated',
                      'message' => 'Invalid token'
                    ];
                }
            } else {
                http_response_code(401);
                $res = [
                  'status' => 'unauthenticated',
                  'message' => 'Missing token'
                ];
            }
            echo json_encode($res);
        }

    }