<?php

namespace Bitrix\LaptopStore;

use Bitrix\Main\Entity;
use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;

/**
 * Класс для работы с таблицей "Модель"
 */
class ModelTable extends Entity\DataManager {
    protected static string $tableName = 'b_laptopstore_model';

    /**
     * Структура таблицы
     * @return array
     */
    public static function getMap(): array {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new Entity\StringField('NAME', [
                'required' => true,
            ]),
            new Entity\IntegerField('MANUFACTURER_ID', [
                'required' => true,
            ]),
            new Entity\ReferenceField('MANUFACTURER', ManufacturerTable::class, [
                '=this.MANUFACTURER_ID' => 'ref.ID'
            ]),
        ];
    }

    /**
     * Создание таблицы
     * @throws SqlQueryException
     */
    public static function createTable(): void {
        $connection = Application::getConnection();
        $query = "
        CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
            ID INT AUTO_INCREMENT PRIMARY KEY,
            NAME VARCHAR(255) NOT NULL,
            MANUFACTURER_ID INT NOT NULL,
            FOREIGN KEY (MANUFACTURER_ID) REFERENCES b_laptopstore_manufacturer(ID) ON DELETE CASCADE
        ) ENGINE=InnoDB
    ";

        $connection->queryExecute($query);
    }

    /**
     * Удаление таблицы
     * @return void
     * @throws SqlQueryException
     */
    public static function dropTable(): void {
        $connection = Application::getConnection();
        $query = "DROP TABLE IF EXISTS " . self::$tableName;

        $connection->queryExecute($query);
    }


    /**
     * Метод получения производителей
     * @return array
     */
    public static function getManufacturers(): array
    {
        $result = [];
        $manufacturers = ManufacturerTable::getList([
            'select' => ['ID', 'NAME']
        ]);

        while ($manufacturer = $manufacturers->fetch()) {
            $result[] = $manufacturer;
        }

        return $result;
    }
}

