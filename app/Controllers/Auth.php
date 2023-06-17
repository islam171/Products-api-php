<?php

    namespace app\Controllers;

    class Auth
    {
        public static function register($json)
        {
            require './app/configDB.php';
            $data = json_decode($json);
            $username = $data->username;
            $password = $data->password;
            if (isset($mysql)) {
                $user = $mysql->query("SELECT * FROM `Users` WHERE `username` = '$username'")->fetch_assoc();
            }
            $res = [];
            if ($user) {
                http_response_code(400);
                $res = [
                  'status' => "invalid",
                  "message" => "Такой пользовотель ужу существует"
                ];
            } else {
                $password = $password . md5($password . 'dgfdgdf');
                $newUser = $mysql->query(
                  "INSERT INTO `Users`( `username`, `password`) VALUES ('$username','$password')"
                );
                if ($newUser) {
                    http_response_code(201);
                    $res = [
                      'status' => "success"
                    ];
                } else {
                    http_response_code(200);
                    $res = [
                      'status' => "error"
                    ];
                }
            }
            echo json_encode($res);
        }

        public static function login($json)
        {
            require './app/configDB.php';
            $data = json_decode($json);
            $username = $data->username;
            $password = $data->password;
            $password = $password . md5($password . 'dgfdgdf');
            $res = [];
            $user = $mysql->query(
              "SELECT * FROM `Users` WHERE `username` = '$username' AND `password` = '$password'"
            )->fetch_assoc();
            if (!is_null($user)) {
                $userId = $user['id'];
                $token = bin2hex(random_bytes(16));
                $haveToken = $mysql->query("SELECT * FROM `Tokens` WHERE `userId` = '$userId'")->fetch_assoc();
                if (!is_null($haveToken)) {
                    $tokenId = $haveToken['id'];
                    $delTokenResult = $mysql->query("DELETE FROM `Tokens` WHERE `id` = '$tokenId'");
                    if (is_null($delTokenResult)) {
                        $res = [
                          'status' => "error",
                          'message' => 'Не удолость завершить прошлый сеанс'
                        ];
                        echo json_encode($res);
                    }
                }
                $tokenInsertValue = $mysql->query(
                  "INSERT INTO `Tokens` (`token`, `userId`) VALUES ('$token', '$userId')"
                );
                if (!is_null($tokenInsertValue)) {
                    http_response_code(200);
                    $res = [
                      'status' => "success",
                      'token' => $token
                    ];
                } else {
                    $res = [
                      'status' => "error token",
                      "message" => "Не удалось создать токен"
                    ];
                }
            } else {
                http_response_code(401);
                $res = [
                  'status' => "invalid",
                  "message" => "Wrong username or password"
                ];
            }
            echo json_encode($res);
        }

        public static function getProfile()
        {
            require './app/configDB.php';
            $token = getallheaders()['Authorization'];
            if (isset($mysql)) {
                $userId = $mysql->query("SELECT `userId` FROM `Tokens` WHERE `token` = '$token'")->fetch_assoc();
            }
            $userId = $userId['userId'];
            $user = $mysql->query("SELECT * FROM `Users` WHERE `id` = '$userId'")->fetch_assoc();
            if ($userId) {
                http_response_code(200);
                $res = [
                  'status' => "success",
                  "user" => $user
                ];
            } else {
                http_response_code(401);
                $res = [
                  'status' => "success",
                  "message" => "invalid token"
                ];
            }
            echo json_encode($res);
        }

        public static function logout()
        {
            require './app/configDB.php';
            $res = [];
            $token = getallheaders()['Authorization'];
            $delToken = $mysql->query("DELETE FROM `Tokens` WHERE `token` = '$token'");
            if (!is_null($delToken)) {
                http_response_code(200);
                $res = [
                  'status' => "success"
                ];
            }
            echo json_encode($res);
        }

//        public static function getUserByToken()
//        {
//            require './app/configDB.php';
//            $token = getallheaders()['Authorization'];
//            http_response_code(200);
//            $res = [
//              'status' => "success",
//              'token' => $token
//            ];
//            echo json_encode($res);
//        }
    }