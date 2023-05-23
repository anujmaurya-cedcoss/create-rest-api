<?php
use Phalcon\Mvc\Controller;

session_start();
class OrdersController extends Controller
{
    public function indexAction()
    {
        // redirected to view
    }

    public function createAction() {
        // fetch all the data from products/get
        $ch = curl_init();
        $url = "http://172.23.0.3/products/get?bearer=admin";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);

        $this->view->data = json_decode($output);
    }

    public function placeAction() {
        $ch = curl_init();
        $url = "http://172.23.0.3/order/create?bearer=$_SESSION[role]";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $this->response->redirect('/orders/create');
    }
    
    public function displayAction() {
        $ch = curl_init();
        $url = "http://172.23.0.3/orders/get?bearer=$_SESSION[role]";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $this->view->data = (json_decode($output, true));
    }
}
