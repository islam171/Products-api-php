<?php

    namespace app\Controllers;

    class Company
    {
        public static function getAll()
        {
            require './app/configDB.php';
            $res = [];
            if (!empty($mysql)) {
                $companies = $mysql->query("SELECT * FROM `Company`");
            }
            if (!is_null($companies)) {
                http_response_code(200);
                $companyList = [];
                while ($company = $companies->fetch_assoc()) {
                    $companiesList[] = $company;
                }
                $res = [
                  "status" => "success",
                  "products" => $companiesList
                ];
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error"
                ];
            }
            echo json_encode($res);
        }

        public static function getId($get)
        {
            require './app/configDB.php';
            $res = [];
            $companyId = $get['companyId'];
            if (!is_null($companyId)) {
                if (!empty($mysql)) {
                    $company = $mysql->query("SELECT * FROM `Company` WHERE `id` = '$companyId'")->fetch_assoc();
                }
            }
            if (!is_null($company)) {
                http_response_code(200);
                $res = [
                  "status" => "success",
                  "products" => $company
                ];
            } else {
                http_response_code(401);
                $res = [
                  "status" => "error"
                ];
            }
            echo json_encode($res);
        }

        public static function create($json)
        {
            require './app/configDB.php';
            $data = json_decode($json);
            $name = $data->name;
            $token = getallheaders()['Authorization'];
            $res = [];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT `userId` FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                    if (!is_null($userId)) {
                        $userId = $userId['userId'];
                        $company = $mysql->query(
                          "INSERT INTO `Company` (`OwnerId`, `name`) VALUES ('$userId', '$name')"
                        );
                        if ($company) {
                            $res = [
                              'status' => 'success'
                            ];
                            http_response_code(200);
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
                          "status" => "unauthenticated",
                          "message" => "Missing token"
                        ];
                    }
                }
            } else {
                http_response_code(401);
                $res = [
                  "status" => "unauthenticated",
                  "message" => "Missing token"
                ];
            }
            echo json_encode($res);
        }

        public static function delete($get)
        {
            require './app/configDB.php';
            $res = [];
            $companyId = $get['companyId'];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT `userId` FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                if (!is_null($userId)) {
                    $userId = $userId['userId'];
                    $company = $mysql->query(
                      "DELETE FROM `Company` WHERE `id` = '$companyId' and `OwnerId` = '$userId'"
                    );
                    if (!is_null($company)) {
                        http_response_code(200);
                        $res = [
                          "status" => "success",
                          "product" => $company
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

        public static function updateCompanyName($json)
        {
            require './app/configDB.php';
            $res = [];
            $data = json_decode($json);
            $name = $data->name;
            $companyId = $data->id;
            $token = getallheaders()['Authorization'];
            if (isset($mysql)) {
                $userId = $mysql->query("SELECT `userId` FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                if (!is_null($userId)) {
                    $userId = $userId['userId'];
                    $company = $mysql->query("UPDATE `Company` SET `name` = '$name' WHERE `id` = '$companyId' and `OwnerId` = '$userId'");
                    if ($company) {
                        http_response_code(200);
                        $res = [
                          "status" => "success",
                          "product" => $company
                        ];
                    } else {
                        http_response_code(401);
                        $res = [
                          "status" => "forbidden",
                          "message" => "You are not the company owner"
                        ];
                    }
                } else {
                    http_response_code(401);
                    $res = [
                      "status" => "error",
                      "message" => "Авторизуйтесь"
                    ];
                }
            }
            echo json_encode($res);
        }

        public static function CompanyByUser()
        {
            require './app/configDB.php';
            $res = [];
            $token = getallheaders()['Authorization'];
            if (!is_null($token)) {
                if (isset($mysql)) {
                    $userId = $mysql->query("SELECT userId FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
                }
                $userId = $userId['userId'];
                if (!is_null($userId)) {
                    $company = $mysql->query(
                      "SELECT * FROM `Company` WHERE `OwnerId` = '$userId'"
                    )->fetch_assoc();
                    if (!is_null($company)) {
                        http_response_code(200);
                        $res = [
                          "status" => "success",
                          "company" => $company
                        ];
                    } else {
                        http_response_code(200);
                        $res = [
                          "status" => "error",
                          'message' => "No Company"
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
    }