<?php

global $APPLICATION;

use Bitrix\LaptopStore\LaptopOptionTable;
use Bitrix\LaptopStore\ModelTable;
use Bitrix\LaptopStore\LaptopTable;
use Bitrix\LaptopStore\OptionTable;
use Bitrix\Main\Entity;


\Bitrix\Main\UI\Extension::load("ui.bootstrap4");

$manufacturerId = isset($_GET['manufacturerId']) ? intval($_GET['manufacturerId']) : null;
$modelId = isset($_GET['modelId']) ? intval($_GET['modelId']) : null;

$manufacturers = ModelTable::getManufacturers();

// Получение моделей по производителю
$models = [];
if ($manufacturerId) {
    $models = ModelTable::getList([
        'filter' => ['MANUFACTURER_ID' => $manufacturerId],
        'select' => ['ID', 'NAME']
    ])->fetchAll();
}

// Получение ноутбуков по фильтрам
$laptops = LaptopTable::getList([
    'select' => [
        'ID',
        'PRICE',
        'YEAR',
        'MODEL_ID',
        'MODEL_NAME' => 'MODEL.NAME',
        'MANUFACTURER_NAME' => 'MODEL.MANUFACTURER.NAME'
    ],
    'filter' => [
        'MODEL_ID' => array_column($models, 'ID'),
        ($modelId ? ['=MODEL_ID' => $modelId] : [])
    ],
    'runtime' => [
        new \Bitrix\Main\Entity\ReferenceField(
            'MODEL',
            ModelTable::class,
            ['=this.MODEL_ID' => 'ref.ID']
        )
    ]
])->fetchAll();

?>

<div class="container mt-4">
    <h1>Ноутбуки</h1>

    <form id="filterForm" method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="manufacturerSelect">Выберите производителя</label>
                    <select name="manufacturerId" id="manufacturerSelect" class="form-control" onchange="this.form.submit()">
                        <option value="">Выберите производителя</option>
                        <?php foreach ($manufacturers as $manufacturer): ?>
                            <option value="<?= htmlspecialchars($manufacturer['ID']) ?>" <?= ($manufacturerId == $manufacturer['ID']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($manufacturer['NAME']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="modelSelect">Выберите модель</label>
                    <select name="modelId" id="modelSelect" class="form-control" <?= is_null($manufacturerId) ? 'disabled' : '' ?>>
                        <option value="">Выберите производителя</option>
                        <?php foreach ($models as $model): ?>
                            <option value="<?= htmlspecialchars($model['ID']) ?>" <?= ($modelId == $model['ID']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($model['NAME']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="btn btn-secondary mb-3">Сбросить фильтр</a>
    </form>

    <div id="laptopGrid">
        <?php if ($laptops): ?>
            <h2>Результаты поиска:</h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Модель</th>
                    <th>Опции</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($laptops as $laptop):
                    $options = LaptopOptionTable::getList([
                        'filter' => ['LAPTOP_ID' => $laptop['ID']],
                        'select' => ['OPTION.NAME'],
                        'runtime' => [
                            new Entity\ReferenceField(
                                'OPTION',
                                OptionTable::class,
                                ['=this.OPTION_ID' => 'ref.ID'],
                                ['join_type' => 'INNER']
                            )
                        ]
                    ])->fetchAll();
                    ?>
                    <tr>
                        <td>
                            <a href="detail.php?id=<?= htmlspecialchars($laptop['ID']) ?>">
                                <?= htmlspecialchars($laptop['MANUFACTURER_NAME'] . ' ' . $laptop['MODEL_NAME']) ?>
                            </a>
                        </td>
                        <td>
                            <?= htmlspecialchars($laptop['MODEL_NAME'] ?? 'Неизвестно') ?>
                        </td>
                        <td>
                            <?php if (count($options) > 0): ?>
                                <?php foreach ($options as $option): ?>
                                    <?= htmlspecialchars($option['NAME']) ?><br>
                                <?php endforeach; ?>
                            <?php else: ?>
                                Нет
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Нет результатов</p>
        <?php endif; ?>
    </div>
</div>



