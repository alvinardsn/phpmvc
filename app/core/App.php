<?php
class App
{
    protected $controller = 'Home'; // penentuan controller default
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        // controller
        $url = $this->parseURL();

        if (file_exists('../app/controllers/' . $url[0] . '.php')) { // pengecekkan file yg berada di folder controlers
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); //menghapus tandan / di akhir url
            $url = filter_var($url, FILTER_SANITIZE_URL); // memfilter url dari karakter yg memungkinkan untuk di hack / karakter2 aneh
            $url = explode('/', $url); // pemecahan karakter dengan delimeter slash (/) dan element stringnya adalah url
            return $url;
        }
    }
}
