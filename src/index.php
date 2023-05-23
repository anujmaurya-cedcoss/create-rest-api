<?php

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Collection\Manager;

define("BASE_PATH", (__DIR__));
require_once(BASE_PATH . '/vendor/autoload.php');
require_once(BASE_PATH . '/acl.php');
require_once(BASE_PATH . '/token.php');

// Use Loader() to autoload our model
$container = new FactoryDefault();
$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            'mongodb+srv://root:9SoCvPuQHy0SMXn1@cluster0.nwpyx9q.mongodb.net/?retryWrites=true&w=majority'
        );
        return $mongo->api_store;
    },
    true
);
$container->set(
    'collectionManager',
    function () {
        return new Manager();
    }
);

$app = new Micro($container);

// Retrieves all products
$app->get(
    '/products/get',
    function () {
        $limit = 10;
        $page = 0;
        if (isset($_GET['per_page']) && $_GET['per_page'] > 0) {
            $limit = $_GET['per_page'];
        }
        if (isset($_GET['page']) && $_GET['page'] > 0) {
            $page = $_GET['page'];
        }
        $collection = $this->mongo->products;
        $productList = $collection->find([], ['limit' => (int) $limit, 'skip' => (int) $page * $limit]);
        $data = [];
        foreach ($productList as $product) {
            $data[] = [
                'id' => $product['id'],
                'name' => $product['name'],
            ];
        }
        echo json_encode($data);
    }
);

// Searches for products with $name in their name
$app->get(
    '/products/search/{name}',
    function ($name) {
        $collection = $this->mongo->products;
        $nameList = explode('%20', $name);
        foreach ($nameList as $name) {
            $productList[] = $collection->find(array('name' => array('$regex' => $name)));
        }
        $data = [];
        foreach ($productList as $productL) {
            foreach ($productL as $product) {
                $data[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price']
                ];
            }
        }
        echo json_encode($data);
    }
);

// checking for access
$app->before(
    function () {
        $role = $_GET['bearer'];
        $token = User\Token\generateToken($role);
        $arr = explode('/', $_GET['_url']);
        $access = App\Acl\checkAccess($token, $arr[1], $arr[2]);
        if (!$access) {
            echo "<h1>Not allowed !</h1>";
            die;
        }
    }
);

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo '<h1>This is crazy, but this page was not found!</h1>';
});

$app->handle($_SERVER['REQUEST_URI']);
