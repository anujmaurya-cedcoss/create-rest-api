<?php
use Phalcon\Mvc\Controller;

session_start();
class IndexController extends Controller
{
    public function indexAction()
    {
        // redirected to view
    }
    public function signupAction()
    {

        $arr = [
            'name' => $_POST['name'],
            'mail' => $_POST['mail'],
            'pass' => $_POST['pass'],
            'admin' => (bool) ($_POST['adminSignup'] == 'admin')
        ];
        $ch = curl_init();
        $url = "http://172.23.0.3/register?bearer=admin";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        if ($output) {
            $this->response->redirect('/index/login');
        } else {
            echo "<h3>There was some error</h3>";
            die;
        }
    }

    public function loginAction()
    {
        // redirected to view
    }
    public function doLoginAction()
    {
        $ch = curl_init();
        $url = "http://172.23.0.3/login?bearer=admin";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        if ($output == 'admin') {
            $_SESSION['role'] = 'admin';
            $this->response->redirect('/orders/display');
        } elseif ($output == 'user') {
            $_SESSION['role'] = 'user';
            $this->response->redirect('/orders/create');
        } else {
            // authentication failed
            $this->response->redirect('/index/login');
        }
    }
}
