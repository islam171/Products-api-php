<?php

    namespace app\Controllers;

    class Category
    {
        public static function create($json)
        {
            require_once "./app/configDB.php";
            $data = json_decode($json);
            $name = $data->name;
            $category = $mysql->query("INSERT INTO `Сategory` (`name`) VALUES ('$name')");
            $res = [];
            if ($category) {
                http_response_code(200);
                $res = [
                  "status" => "succes",
                ];
            } else {
                http_response_code(405);
                $res = [
                  "status" => "error"
                ];
            }
            echo json_encode($res);
        }

        public static function getAll()
        {
            require_once "./app/configDB.php";
            $res = [];
            if (isset($mysql)) {
                $categories = $mysql->query("SELECT * FROM `Сategory`");
            }
            if (isset($categories)) {
                $categoriesList = [];
                while ($category = $categories->fetch_assoc()) {
                    $categoriesList[] = $category;
                }
                http_response_code(200);
                $res = [
                  "status" => "succes",
                  "categories" => $categoriesList
                ];
            }
            echo json_encode($res);
        }

        public static function getById($get)
        {
            require_once "./app/configDB.php";
            $categoryId = $get['categoryId'];
            $res = [];
            if (isset($mysql)) {
                $category = $mysql->query("SELECT * FROM `Сategory` WHERE `id` = '$categoryId'")->fetch_assoc();
            }
            if (isset($category)) {
                $res = [
                  "status" => "success",
                  "category" => $category
                ];
            }
            echo json_encode($res);
        }

        public static function delete($get)
        {
            require_once "./app/configDB.php";
            $categoryId = $get['categoryId'];
            $res = [];
            if (isset($mysql)) {
                $category = $mysql->query("DELETE FROM `Сategory` WHERE `id` = '$categoryId'");
                if ($category) {
                    http_response_code(204);
                } else {
                    http_response_code(401);
                    $res = [
                      "status" => "error",
                    ];
                }
            }
            echo json_encode($res);
        }

        public static function update($json)
        {
            require_once "./app/configDB.php";
            $res = [];
            $data = json_decode($json);
            $name = $data->name;
            $categoryId = $data->id;

            if (isset($mysql)) {
                $category = $mysql->query("UPDATE `Сategory` SET `name` = '$name' WHERE `id` = '$categoryId'");
                if ($category) {
                    http_response_code(200);
                    $res = [
                      "status" => "success",
                      "category" => $category
                    ];
                } else {
                    http_response_code(401);
                    $res = [
                      "status" => "error",
                    ];
                }
            }
            echo json_encode($res);
        }
    }