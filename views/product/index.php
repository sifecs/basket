<?php
include $_SERVER['DOCUMENT_ROOT'] . '/views/templite/heater.php';
?>
<div class="container">
    <div class="row">

        <div class="col-12"> <a href="/basket"> Корзина </a> </div>
        <?php foreach ($products as $product):?>
            <div class="card col-md-3">
                <div class="card-body">
                    <h5 class="card-title"><?=$product->title?></h5>
                    <p class="card-text"><?=$product->description?></p>
                    <form action="/basket/store" method="post">
                        <input type="hidden" name="product_id" value="<?=$product->id?>">
                        <button type="submit" name="addBasket" class="card-link text-success btn-link btn"> В корзину </button>
                    </form>
                    <form action="/product/<?=$product->id?>" method="post">
                        <button type="submit" class="card-link text-danger btn-link btn">Удалить</button>
                    </form>
                </div>
            </div>
        <?php endForeach ?>
        <div class="mt-4">
            <?php if ($countPage > 1): ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php
                    for($i = 0; $i < $countPage; $i++): ?>
                        <li class="page-item <?=$page == $i ? 'active': ''  ?>"><a class="page-link" href="?page=<?=$i?>"><?=$i + 1?></a></li>
                   <?php endfor ?>
                </ul>
            </nav>
            <?php endif ?>
        </div>
    </div>
</div>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/views/templite/footer.php';
?>