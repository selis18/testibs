<?php

namespace Bitrix\LaptopStore;

use Bitrix\Main\Entity;
use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;

/**
 */
class LaptopOptionTable extends Entity\DataManager {
    protected static string $tableName = 'b_laptopstore_laptop_option';

    /**
     * Структура таблицы
     * @return array
     */
    public static function getMap(): array {
        return [
            new Entity\IntegerField('LAPTOP_ID', [
                'primary' => true,
            ]),
            new Entity\IntegerField('OPTION_ID', [
                'primary' => true,
            ]),
            new Entity\ReferenceField('LAPTOP', LaptopTable::class, [
                '=this.LAPTOP_ID' => 'ref.ID'
            ]),
            new Entity\ReferenceField('OPTION', OptionTable::class, [
                '=this.OPTION_ID' => 'ref.ID'
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
            LAPTOP_ID INT NOT NULL,
            OPTION_ID INT NOT NULL,
            PRIMARY KEY (LAPTOP_ID, OPTION_ID),
            FOREIGN KEY (LAPTOP_ID) REFERENCES b_laptopstore_laptop(ID) ON DELETE CASCADE,
            FOREIGN KEY (OPTION_ID) REFERENCES b_laptopstore_option(ID) ON DELETE CASCADE
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

