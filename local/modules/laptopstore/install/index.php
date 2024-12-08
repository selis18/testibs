<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/local/modules/laptopstore/vendor/autoload.php');

use Bitrix\LaptopStore\LaptopOptionTable;
use Bitrix\LaptopStore\LaptopTable;
use Bitrix\LaptopStore\ManufacturerTable;
use Bitrix\LaptopStore\ModelTable;
use Bitrix\LaptopStore\OptionTable;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


class laptopstore extends CModule
{
    public function __construct()
    {
        $this->MODULE_ID = "laptopstore";
        $this->MODULE_NAME = Loc::getMessage("LAPTOP_STORE_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("LAPTOP_STORE_MODULE_DESCRIPTION");
        $this->MODULE_VERSION = "1.0.0";
        $this->MODULE_VERSION_DATE = "2024-12-01 00:00:00";

        $this->PARTNER_NAME = Loc::getMessage("LAPTOP_STORE_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("LAPTOP_STORE_PARTNER_URI");
    }

    /**
     * @return void
     * @throws Exception
     */
    public function DoInstall(): void
    {
        global $APPLICATION;

        if($this->isVersionD7()) {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallFiles();

        } else {
            // Ошибка, если версия D7 недоступна
            $APPLICATION->ThrowException(Loc::getMessage("LAPTOP_STORE_INSTALL_ERROR_VERSION"));
        }

    }

    /**
     * @return void
     */
    public function DoUninstall()
    {
        global $APPLICATION;

        $context = \Bitrix\Main\Application::getInstance()->getContext();
        $request = $context->getRequest();


        \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

        $this->installTestData();


        if ($request["savedata"] != "Y") {
            $this->UnInstallDB();
        }
    }


    /**
     * Проверка версии
     * @return bool
     */
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    /**
     * @return int
     * @throws Exception
     */
    function InstallDB(): bool|int
    {
        ManufacturerTable::createTable();
        ModelTable::createTable();
        OptionTable::createTable();
        LaptopTable::createTable();
        LaptopOptionTable::createTable();

        return true;
    }

    /**
     * @return int
     * @throws \Bitrix\Main\DB\SqlQueryException
     */
    function UnInstallDB(): int
    {
        LaptopOptionTable::dropTable();
        LaptopTable::dropTable();
        ModelTable::dropTable();
        OptionTable::dropTable();
        ManufacturerTable::dropTable();

        return true;
    }


    /**
     * @return true
     * @throws Exception
     */
    function InstallFiles()
    {
        $sourcePath = $_SERVER["DOCUMENT_ROOT"] . '/local/modules/laptopstore/components/';
        $destinationPath = $_SERVER["DOCUMENT_ROOT"] . '/local/components/';

        if (!\Bitrix\Main\IO\Directory::isDirectoryExists($sourcePath)) {
            throw new \Exception($sourcePath);
        }

        if (!\Bitrix\Main\IO\Directory::isDirectoryExists($destinationPath)) {
            \Bitrix\Main\IO\Directory::createDirectory($destinationPath);
        }

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($sourcePath)) as $file) {
            if ($file->isDir()) {
                continue;
            }

            $filePath = $file->getPathname();
            $relativePath = str_replace($sourcePath, '', $filePath);
            $destinationFilePath = $destinationPath . $relativePath;
            $destinationDirectory = dirname($destinationFilePath);

            if (!\Bitrix\Main\IO\Directory::isDirectoryExists($destinationDirectory)) {
                \Bitrix\Main\IO\Directory::createDirectory($destinationDirectory);
            }

            if (!copy($filePath, $destinationFilePath)) {
                throw new \Exception($filePath);
            }
        }
    }

    /**
     * @throws Exception
     */
    function UninstallFiles(): void
    {
        $directoryPath = $_SERVER['DOCUMENT_ROOT'] . '/local/components/laptopstore/';

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($directoryPath)) {
            try {
                \Bitrix\Main\IO\Directory::delete($directoryPath);
            } catch (\Exception $e) {

                throw new \Exception($e->getMessage());
            }
        }
    }

    /**
     * @throws Exception
     */
    public function installData($tableName, $params): void
    {
        foreach ($params as $data) {
            switch ($tableName) {
                case 'b_laptopstore_manufacturer':
                    ManufacturerTable::add($data);
                    break;
                case 'b_laptopstore_model':
                    ModelTable::add($data);
                    break;
                case 'b_laptopstore_laptop':
                    LaptopTable::add($data);
                    break;
                case 'b_laptopstore_option':
                    OptionTable::add($data);
                    break;
                case 'b_laptopstore_laptop_option':
                    LaptopOptionTable::add($data);
                    break;
                default:
                    throw new Exception("Отсутсвует таблица: " . $tableName);
            }
        }
    }

    /**
     * Тестовые данные
     * @return void
     * @throws Exception
     */
    public function installTestData(): void
    {
        $this->installData(
            "b_laptopstore_manufacturer",
            [
                ['NAME' => 'HP'],
                ['NAME' => 'Dell'],
                ['NAME' => 'Lenovo'],
                ['NAME' => 'Apple'],
                ['NAME' => 'Acer'],
                ['NAME' => 'ASUS'],
                ['NAME' => 'Microsoft'],
                ['NAME' => 'Razer'],
                ['NAME' => 'Toshiba'],
                ['NAME' => 'LG']
            ]
        );

        $this->installData(
            "b_laptopstore_model",
            [
                ['NAME' => 'Pavilion', 'MANUFACTURER_ID' => 1],
                ['NAME' => 'Inspiron', 'MANUFACTURER_ID' => 2],
                ['NAME' => 'ThinkPad', 'MANUFACTURER_ID' => 3],
                ['NAME' => 'MacBook Air', 'MANUFACTURER_ID' => 4],
                ['NAME' => 'Aspire', 'MANUFACTURER_ID' => 5],
                ['NAME' => 'ZenBook', 'MANUFACTURER_ID' => 6],
                ['NAME' => 'Surface Laptop', 'MANUFACTURER_ID' => 7],
                ['NAME' => 'Blade Stealth', 'MANUFACTURER_ID' => 8],
                ['NAME' => 'Satellite', 'MANUFACTURER_ID' => 9],
                ['NAME' => 'Gram', 'MANUFACTURER_ID' => 10],
                ['NAME' => 'Envy', 'MANUFACTURER_ID' => 1],
                ['NAME' => 'G5', 'MANUFACTURER_ID' => 2],
                ['NAME' => 'Yoga', 'MANUFACTURER_ID' => 3],
                ['NAME' => 'MacBook Pro', 'MANUFACTURER_ID' => 4],
                ['NAME' => 'Swift', 'MANUFACTURER_ID' => 5],
                ['NAME' => 'ROG Strix', 'MANUFACTURER_ID' => 6],
                ['NAME' => 'Surface Pro', 'MANUFACTURER_ID' => 7],
                ['NAME' => 'Aero', 'MANUFACTURER_ID' => 8],
                ['NAME' => 'Qosmio', 'MANUFACTURER_ID' => 9],
                ['NAME' => 'Ultra', 'MANUFACTURER_ID' => 10]
            ]
        );

        $this->installData(
            "b_laptopstore_laptop",
            [
                ['PRICE' => 649.99, 'YEAR' => 2021, 'MODEL_ID' => 1],
                ['PRICE' => 599.99, 'YEAR' => 2021, 'MODEL_ID' => 2],
                ['PRICE' => 849.99, 'YEAR' => 2021, 'MODEL_ID' => 3],
                ['PRICE' => 999.99, 'YEAR' => 2022, 'MODEL_ID' => 4],
                ['PRICE' => 499.99, 'YEAR' => 2021, 'MODEL_ID' => 5],
                ['PRICE' => 899.99, 'YEAR' => 2022, 'MODEL_ID' => 6],
                ['PRICE' => 849.99, 'YEAR' => 2022, 'MODEL_ID' => 7],
                ['PRICE' => 1899.99, 'YEAR' => 2022, 'MODEL_ID' => 8],
                ['PRICE' => 399.99, 'YEAR' => 2020, 'MODEL_ID' => 9],
                ['PRICE' => 1599.99, 'YEAR' => 2022, 'MODEL_ID' => 10],
                ['PRICE' => 699.99, 'YEAR' => 2021, 'MODEL_ID' => 1],
                ['PRICE' => 999.99, 'YEAR' => 2022, 'MODEL_ID' => 2],
                ['PRICE' => 449.99, 'YEAR' => 2021, 'MODEL_ID' => 3],
                ['PRICE' => 1999.99, 'YEAR' => 2022, 'MODEL_ID' => 4],
                ['PRICE' => 679.99, 'YEAR' => 2021, 'MODEL_ID' => 5],
                ['PRICE' => 1399.99, 'YEAR' => 2022, 'MODEL_ID' => 6],
                ['PRICE' => 749.99, 'YEAR' => 2021, 'MODEL_ID' => 7],
                ['PRICE' => 1699.99, 'YEAR' => 2022, 'MODEL_ID' => 8],
                ['PRICE' => 499.99, 'YEAR' => 2020, 'MODEL_ID' => 9],
                ['PRICE' => 1299.99, 'YEAR' => 2022, 'MODEL_ID' => 10],
                ['PRICE' => 749.99, 'YEAR' => 2022, 'MODEL_ID' => 1],
                ['PRICE' => 1049.99, 'YEAR' => 2022, 'MODEL_ID' => 2],
                ['PRICE' => 1299.99, 'YEAR' => 2022, 'MODEL_ID' => 3],
                ['PRICE' => 2499.99, 'YEAR' => 2022, 'MODEL_ID' => 4],
                ['PRICE' => 799.99, 'YEAR' => 2021, 'MODEL_ID' => 5],
                ['PRICE' => 1099.99, 'YEAR' => 2022, 'MODEL_ID' => 6],
                ['PRICE' => 1249.99, 'YEAR' => 2021, 'MODEL_ID' => 7],
                ['PRICE' => 2299.99, 'YEAR' => 2022, 'MODEL_ID' => 8],
                ['PRICE' => 599.99, 'YEAR' => 2020, 'MODEL_ID' => 9],
                ['PRICE' => 999.99, 'YEAR' => 2022, 'MODEL_ID' => 10]
            ]
        );

        $this->installData(
            "b_laptopstore_option",
            [
                ['NAME' => '16GB RAM'],
                ['NAME' => '32GB RAM'],
                ['NAME' => '512GB SSD'],
                ['NAME' => '1TB SSD'],
                ['NAME' => 'FHD Display'],
                ['NAME' => '4K Display'],
                ['NAME' => 'NVIDIA GTX 1650'],
                ['NAME' => 'Intel i5'],
                ['NAME' => 'Intel i7'],
                ['NAME' => 'Wi-Fi 6'],
                ['NAME' => 'Touchscreen'],
                ['NAME' => 'Backlit Keyboard'],
                ['NAME' => 'Fingerprint Reader'],
                ['NAME' => 'Bluetooth 5.0'],
                ['NAME' => 'HDMI Port'],
                ['NAME' => 'USB-C Port'],
                ['NAME' => 'Webcam 720p'],
                ['NAME' => 'Ethernet Port'],
                ['NAME' => 'Optical Drive'],
                ['NAME' => 'Detachable Keyboard']
            ]
        );

        $this->installData(
            "b_laptopstore_laptop_option",
            [
                ['LAPTOP_ID' => 1, 'OPTION_ID' => 1],
                ['LAPTOP_ID' => 1, 'OPTION_ID' => 3],
                ['LAPTOP_ID' => 2, 'OPTION_ID' => 1],
                ['LAPTOP_ID' => 3, 'OPTION_ID' => 2],
                ['LAPTOP_ID' => 4, 'OPTION_ID' => 1],
                ['LAPTOP_ID' => 5, 'OPTION_ID' => 3],
                ['LAPTOP_ID' => 6, 'OPTION_ID' => 5],
                ['LAPTOP_ID' => 7, 'OPTION_ID' => 2],
                ['LAPTOP_ID' => 8, 'OPTION_ID' => 8],
                ['LAPTOP_ID' => 9, 'OPTION_ID' => 7],
                ['LAPTOP_ID' => 10, 'OPTION_ID' => 1],
                ['LAPTOP_ID' => 11, 'OPTION_ID' => 10],
                ['LAPTOP_ID' => 12, 'OPTION_ID' => 1],
                ['LAPTOP_ID' => 13, 'OPTION_ID' => 9],
                ['LAPTOP_ID' => 14, 'OPTION_ID' => 4],
                ['LAPTOP_ID' => 15, 'OPTION_ID' => 6],
                ['LAPTOP_ID' => 16, 'OPTION_ID' => 2],
                ['LAPTOP_ID' => 17, 'OPTION_ID' => 5],
                ['LAPTOP_ID' => 18, 'OPTION_ID' => 3],
                ['LAPTOP_ID' => 19, 'OPTION_ID' => 8],
                ['LAPTOP_ID' => 20, 'OPTION_ID' => 1],
                ['LAPTOP_ID' => 21, 'OPTION_ID' => 7],
                ['LAPTOP_ID' => 22, 'OPTION_ID' => 6]
            ]
        );
    }

}
?>
