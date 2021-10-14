<?php

namespace src\Controllers;
use PDO;

class ProductController
{
    public $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index() {
        $title = 'Главная';
        // пагинация уйдёт в отдельную функцию
        $count = $this->db->query("SELECT count(*) FROM products")->fetchColumn();
        $page  = isset($_GET['page']) ? (int)$_GET['page']: 0;
        $limit = 4;
        $countPage = ceil($count / $limit);
        $start = $page * $limit;


        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $products = $this->db->prepare("SELECT * FROM products  LIMIT :start, :limit");
        $products->execute(['start' => $start, 'limit' => $limit]);
        $products = $products->fetchAll(PDO::FETCH_OBJ);

        include  $_SERVER['DOCUMENT_ROOT'] . '/views/product/index.php';
    }

    public function destroy($id)
    {
        $product =  $this->db->prepare("DELETE FROM products WHERE `id` = :id");
        $product->execute(['id' => $id]);
        if ($product->rowCount() == 0) {
//            'Ошибка удаления'
//            Тут будет сохранение сообщения в сессию и редирект обратно
            return header('Location: /',true, 301);
        }
        return header('Location: /',true, 301);
    }


}