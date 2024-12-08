<div class="container mt-4">
    <h2>Детальная информация о ноутбуке: <?= htmlspecialchars($arResult['LAPTOP']['NAME']) ?></h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Производитель: <?= htmlspecialchars($arResult['MANUFACTURER']['NAME']) ?></h5>
            <p class="card-text"><strong>Модель:</strong> <?= htmlspecialchars($arResult['MODEL']['NAME']) ?></p>
            <p class="card-text"><strong>Год выпуска:</strong> <?= htmlspecialchars($arResult['LAPTOP']['YEAR']) ?></p>
            <p class="card-text"><strong>Цена:</strong> <?= htmlspecialchars($arResult['LAPTOP']['PRICE']) ?> руб.</p>
            <p class="card-text"><strong>Опции:</strong> <?= htmlspecialchars($arResult['LAPTOP']['OPTIONS']) ?></p>
        </div>
    </div>

    <a href="<?= $APPLICATION->GetCurPageParam('', []); ?>" class="btn btn-primary mt-3">Вернуться к списку</a>
</div>
