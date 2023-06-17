<?php

    namespace app\Services;

    class Router
    {
        private static $list = [];

        public static function post($url, $class, $method, $formdata = false, $files = false)
        {
            self::$list[] = [
              "url" => $url,
              "class" => $class,
              "method" => $method,
              "req_method" => "POST",
              "formdata" => $formdata,
              "files" => $files,
                "slug" => false
            ];
        }

        public static function get($url, $class, $method, $slug = false)
        {
            self::$list[] = [
              "url" => $url,
              "class" => $class,
              "method" => $method,
              "req_method" => "GET",
              "slug" => $slug,
              "formdata" => false,
              "files" => false
            ];
        }

        public static function delete($url, $class, $method, $slug = false, $formdata = false, $files = false)
        {
            self::$list[] = [
              "url" => $url,
              "class" => $class,
              "method" => $method,
              "req_method" => "DELETE",
              "slug" => $slug,
                "formdata" => $formdata,
                "files" => $files
            ];
        }

        public static function update($url, $class, $method, $formdata = false, $files = false)
        {
            self::$list[] = [
              "url" => $url,
              "class" => $class,
              "method" => $method,
              "req_method" => "PUT",
              "formdata" => $formdata,
              "files" => $files,
              "slug" => false
            ];
        }

        public static function enable()
        {
            $query = $_GET['q'];
            foreach (self::$list as $route) {
                if ($route['url'] === '/' . $query) {
                    $action = new $route['class'];
                    $method = $route['method'];
                    if ($_SERVER['REQUEST_METHOD'] == $route['req_method']) {
                        if ($route['formdata'] && $route['files']) {
                            $action->$method(file_get_contents('php://input'), $_FILES);
                        } elseif ($route['formdata'] && !$route['files'] && !$route['slug']) {
                            $action->$method(file_get_contents('php://input'));
                        } elseif ($route['slug']) {
                            $action->$method($_GET);
                        } else {
                            $action->$method();
                        }
                    }
                }
            }
        }
    }