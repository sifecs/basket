<?php

namespace src\Controllers;

use PDO;
class BasketController
{

    public $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index() {
        $stmt =  $this->db->prepare("SELECT baskets.id, baskets.count, baskets.product_id, products.title, products.description FROM baskets 
    LEFT JOIN products ON (baskets.product_id=products.id)");
        $stmt->execute();
        $baskets = $stmt->fetchAll(PDO::FETCH_OBJ);

        $title = 'Карзина';

        include  $_SERVER['DOCUMENT_ROOT'] . '/views/basket/index.php';
    }

    public function countAjax(){
        $data = $_POST;

        if (isset($data['id']) && isset($data['count']) && $data['count'] >= 1 ) {
            $stmt = $this->db->prepare("UPDATE baskets SET `count` = :count WHERE `id` = :id");
            $stmt->execute(['id' => $data['id'], ':count' => $data['count'] ]);
        }
    }

    public function store() {
        $data = $_POST;
        if (isset($data['product_id']) && isset($data['addBasket']) ) {
            $product =  $this->db->prepare("SELECT * FROM products WHERE `id` = :id");
            $product->execute(['id' => $data['product_id']]);
            if ($product->rowCount() > 0) {
                $basket =  $this->db->prepare("SELECT * FROM baskets WHERE `product_id` = :product_id");
                $basket->execute(['product_id' => $data['product_id']]);
                if ($basket->rowCount() != 0) {
                    // Тут будет сохранение сообщения в сессию что товар уже есть в корзине и редирект на листинг товаров
                    return header('Location: /',true, 301);
                }
                $stmt =  $this->db->prepare("INSERT INTO `baskets` (product_id, count) VALUES (:product_id, :count)");
                $stmt->execute(['product_id' => $data['product_id'], 'count' => 1]);
                return header('Location: /',true, 301);
            }
        }
    }

    public function destroy($id)
    {
        $order =  $this->db->prepare("DELETE FROM baskets WHERE `id` = :id");
        $order->execute(['id' => $id]);

        if ($order->rowCount() == 0) {
//            Тут будет сохранение сообщения в сессию и редирект обратно
            return header('Location: /basket',true, 301);
        }
        return header('Location: /',true, 301);
    }

}