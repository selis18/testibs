<?php

namespace Bitrix\LaptopStore;

use Bitrix\Main\Entity;
use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;

/**
 * Класс для работы с таблицей "Ноутбук"
 */
class LaptopTable extends Entity\DataManager {
    protected static string $tableName = 'b_laptopstore_laptop';

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
            new Entity\IntegerField('PRICE', [
                'required' => true,
            ]),
            new Entity\IntegerField('YEAR', [
                'required' => true,
            ]),
            new Entity\IntegerField('MODEL_ID', [
                'required' => true,
            ]),
            new Entity\ReferenceField(
                'MODEL',
                ModelTable::class,
                ['=this.MODEL_ID' => 'ref.ID']
            ),
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
                PRICE DECIMAL(10, 2) NOT NULL,
                YEAR INT NOT NULL,
                MODEL_ID INT NOT NULL,
                FOREIGN KEY (MODEL_ID) REFERENCES b_laptopstore_model(ID) ON DELETE CASCADE
            ) ENGINE=InnoDB;
        ";

        $connection->queryExecute($query);
    }

    /**
     * Удаление таблицы
     * @throws SqlQueryException
     */
    public static function dropTable(): void {
        $connection = Application::getConnection();
        $query = "DROP TABLE IF EXISTS " . self::$tableName;

        $connection->queryExecute($query);
    }
}

