<?php
use Phalcon\Mvc\Controller;

session_start();
class ProductsController extends Controller
{
    public function indexAction()
    {
        // fetch all the data from products/get
        $ch = curl_init();
        $url = "http://172.23.0.3/products/get?bearer=$_SESSION[role]";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);

        echo "<pre>";
        print_r($output); die;
    }
    
}
