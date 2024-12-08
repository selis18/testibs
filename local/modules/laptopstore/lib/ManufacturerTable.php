<?php

namespace Bitrix\LaptopStore;

use Bitrix\Main\Entity;
use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\SystemException;

/**
 * Класс для работы с таблицей "Производитель"
 */
class ManufacturerTable extends Entity\DataManager {
    protected static string $tableName = 'b_laptopstore_manufacturer';

    /**
     * Структура таблицы
     * @return array
     * @throws SystemException
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
            new Entity\ReferenceField('MODELS', ModelTable::class, [
                '=this.ID' => 'ref.MANUFACTURER_ID'
            ]),
        ];
    }

    /**
     * Создание таблицы
     * @throws SqlQueryException
     */
    public static function createTable(): void
    {
        $connection = Application::getConnection();
        $query = "
            CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
                ID INT AUTO_INCREMENT PRIMARY KEY,
                NAME VARCHAR(255) NOT NULL
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
}
