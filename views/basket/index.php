<?php
include $_SERVER['DOCUMENT_ROOT'] . '/views/templite/heater.php';
?>
    <div class="container">

        @include('errorMessage')

        <div class="row">

            <div class="col-12"> <a href="/"> Продукты </a> </div>
                <?php foreach ($baskets as $basket): ?>
                <div class="col-md-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $basket->title ?></h5>
                            <p class="card-text"><?= $basket->description ?></p>

                            <p class="card-text">
                                Количество:

                                <div class="counter row" data-orderId="<?= $basket->id ?>">
                                    <button class="minus btn btn-danger col-md-2">-</button>
                                    <span class="num form-control col mx-3"> <?= $basket->count ?></span>
                                    <button class="plus btn btn-primary col-md-2">+</button>
                                </div>

                            </p>
                            <form >
                                <button type="submit" class="card-link text-success btn-link btn"> Купить(не реализовано) </button>
                            </form>
                            <form action="/basket/<?= $basket->id ?>" method="post">
                                <button type="submit" class="card-link text-danger btn-link btn">Удалить из корзины</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <div class="mt-4">
                {{$basket->links()}}
            </div>
        </div>
    </div>

    <script>
        function s (orderId, count) {
            $.ajax({
                url: "/basket/countAjax",
                method: "POST",
                data: {id : orderId, count: count }
            }).done(function(res) {
                console.log('OK')
                console.log(res)
            });
        }

        $('.plus').click(function(ev) {
            const input = $(this).closest(".counter").find(".num");
            let count = +input.text() + 1;
            let orderId = ev.target.parentElement.dataset.orderid;

            input.text(count);
            s(orderId, count)
        })

        $('.minus').click(function(ev) {
            const input = $(this).closest(".counter").find(".num");
            let orderId = ev.target.parentElement.dataset.orderid;
            let count =  +input.text() - 1;

            if ( count > 0) {
                input.text(count);
                s(orderId, count)
            }
        });
    </script>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/views/templite/footer.php';
?>
