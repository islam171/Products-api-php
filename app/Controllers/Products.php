<?php

    namespace app\Controllers;

    class Products
    {
        public static function getAll($get)
        {
            require './app/configDB.php';
            $res = [];
            $catId = $get['category'];
            $sort = $get['_sort'];
            $order = $get['_order'];
            if (!empty($mysql)) {
                if (!$catId && !$sort && !$order) {
                    $products = $mysql->query("SELECT * FROM `Products`");
                } else {
                    if ($catId && !$sort && !$order) {
                        $products = $mysql->query("SELECT * FROM `Products` WHERE `categoryId` = '$catId'");
                    } else {
                        if (!$catId && $sort && $order) {
                            $products = $mysql->query("SELECT * FROM `Products` ORDER BY `$sort` $order");
                        } else {
                            if ($catId && $sort && $order) {
                                $products = $mysql->query(
                                  "SELECT * FROM `Products` WHERE `categoryId` = '$catId' ORDER BY `$sort` $order"
                                );
                            } else {
                                http_response_code(401);
                                $res = [
                                  "status" => "error"
                                ];
                            }
                        }
                    }
                }
            }
            if (!is_null($products)) {
                http_response_code(200);
                $productsList = [];
                while ($product = $products->fetch_assoc()) {
                    $productsList[] = [
                      "id" => intval($product['id']),
                      "name" => $product['name'],
                      "companyId" => intval($product['companyId']),
                      "categoryId" => intval($product['categoryId']),
                      "price" => intval($product['price'])
                    ];
                }
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

        public static function getCompanyId($query)
        {
            require './app/configDB.php';
            $res = [];
            $companyId = $query['companyId'];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                    if (!is_null($userId)) {
                        if (!is_null($companyId)) {
                            $products = $mysql->query("SELECT * FROM `Products` WHERE `companyId` = '$companyId'");
                        }
                        if (isset($products)) {
                            http_response_code(200);
                            $productsList = [];
                            while ($product = $products->fetch_assoc()) {
                                $productsList[] = [
                                  "id" => intval($product['id']),
                                  "name" => $product['name'],
                                  "companyId" => intval($product['companyId']),
                                  "categoryId" => intval($product['categoryId']),
                                  "price" => intval($product['price'])
                                ];
                            }
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
                    } else {
                        http_response_code(401);
                        $res = [
                          "status" => "Invalid token"
                        ];
                    }
                } else {
                    http_response_code(501);
                    $res = [
                      "status" => "Error server"
                    ];
                }
            } else {
                http_response_code(403);
                $res = [
                  "status" => "Missing token"
                ];
            }
            echo json_encode($res);
        }

        public static function getProductsByUser()
        {
            require './app/configDB.php';
            $res = [];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT `userId` FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                    if (isset($userId)) {
                        $userId = $userId['userId'];
                        $companyId = $mysql->query(
                          "SELECT `id` FROM `Company` WHERE `OwnerId` = '$userId'"
                        )->fetch_assoc();
                        if (isset($companyId)) {
                            $companyId = $companyId['id'];
                            $products = $mysql->query("SELECT * FROM `Products` WHERE `companyId` = '$companyId'");
                            if (isset($products)) {
                                http_response_code(200);
                                $productsList = [];
                                while ($product = $products->fetch_assoc()) {
                                    $productsList[] = [
                                      "id" => intval($product['id']),
                                      "name" => $product['name'],
                                      "companyId" => intval($product['companyId']),
                                      "categoryId" => intval($product['categoryId']),
                                      "price" => intval($product['price'])
                                    ];
                                }
                                $res = [
                                  "status" => "success",
                                  "products" => $productsList
                                ];
                            } else {
                                http_response_code(404);
                            }
                        } else {
                            http_response_code(403);
                            $res = [
                              "status" => "no right"
                            ];
                        }
                    } else {
                        http_response_code(401);
                        $res = [
                          "status" => "Invalid token"
                        ];
                    }
                } else {
                    http_response_code(501);
                    $res = [
                      "status" => "Error server"
                    ];
                }
            } else {
                http_response_code(403);
                $res = [
                  "status" => "Missing token"
                ];
            }
            echo json_encode($res);
        }

        public static function deleteId($get)
        {
            require './app/configDB.php';
            $res = [];
            $productId = $get['productId'];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                if (!is_null($userId)) {
                    $userId = $userId['userId'];
                    $product = $mysql->query("DELETE FROM `Products` WHERE `id` = '$productId'");
                    if (!is_null($product)) {
                        http_response_code(200);
                        $res = [
                          "status" => "success",
                          "product" => $product
                        ];
                    } else {
                        http_response_code(401);
                        $res = [
                          "status" => "error",
                          'message' => "Invalid token"
                        ];
                    }
                } else {
                    http_response_code(401);
                    $res = [
                      'status' => 'unauthenticated',
                      'message' => 'Missing token'
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

        public static function addProduct($json)
        {
            require './app/configDB.php';
            $res = [];
            $data = json_decode($json);
            $name = $data->name;
            $categoryId = $data->categoryId;
            $price = $data->price;
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $dataToken = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                if ($dataToken) {
                    $userId = $dataToken['userId'];
                    $company = $mysql->query("SELECT * FROM `Company` WHERE `OwnerId` = 1")->fetch_assoc();
                    if ($company) {
                        $companyId = $company['id'];
                        $product = $mysql->query(
                          "INSERT INTO `Products` (`name`, `categoryId`, `price`, `companyId`) VALUES ('$name', '$categoryId', '$price', '$companyId')"
                        );
                        if ($product) {
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
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error",
                  "message" => "Авторизуйтесь"
                ];
            }
            echo json_encode($res);
        }

        public static function updateProduct($json)
        {
            require './app/configDB.php';
            $res = [];
            $data = json_decode($json);
            if (is_null($data->id) || is_null($data->name) || is_null($data->categoryId) || is_null($data->price)) {
                http_response_code(400);
                $res = [
                  "status" => "error",
                  "message" => "Bad request"
                ];
                echo json_encode($res);
                die();
            }
            $productId = $data->id;
            $name = $data->name;
            $categoryId = $data->categoryId;
            $price = $data->price;
            $token = getallheaders()['Authorization'];
            $dataToken = $mysql->query("SELECT * FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
            if (empty($dataToken)) {
                http_response_code(401);
                $res = [
                  "status" => "error",
                  "message" => "Invalid token"
                ];
                echo json_encode($res);
                die();
            }
            $userId = $dataToken['userId'];
            $product = $mysql->query("SELECT * FROM `Products` WHERE `id` = '$productId'")->fetch_assoc();
            if (isset($product)) {
                $companyId = $product['companyId'];
                $company = $mysql->query("SELECT * FROM `Company` WHERE `id` = '$companyId'")->fetch_assoc();
                if ($company['OwnerId'] = $userId) {
                    $product = $mysql->query(
                      "UPDATE `Products` SET `name` = '$name', `categoryId` = '$categoryId', `price` = '$price', `companyId` = '$companyId' WHERE `id` = '$productId'"
                    );
                    if ($product) {
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
                } else {
                    http_response_code(403);
                    $res = [
                      "status" => "forbidden",
                      "message" => "You are not the company owner"
                    ];
                }
            } else {
                http_response_code(404);
                $res = [
                  "status" => "forbidden",
                  "message" => "no such product"
                ];
            }
            echo json_encode($res);
        }

    }