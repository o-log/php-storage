<?php

namespace OLOG\Storage;

use OLOG\ConfWrapper;

class StorageFactory
{
    /**
     * @param $storage_name
     * @return LocalStorage
     * @throws \Exception
     */
    public static function getStorageObjByName($storage_name)
    {
        /*
        $storages_arr = ConfWrapper::getRequiredValue(StorageConfigKeys::ROOT . '.' . StorageConfigKeys::STORAGES_ARR);
        
        $storage_obj = ConfWrapper::getRequiredSubvalue($storages_arr, $storage_name);
        
        return $storage_obj;
        */

        return StorageConfig::getStorageObjById($storage_name);
    }

    /**
     * @return array
     */
    public static function getStorageNamesInConfigArr()
    {
        //$storages_arr = ConfWrapper::getRequiredValue(StorageConfigKeys::ROOT . '.' . StorageConfigKeys::STORAGES_ARR);
        $storages_arr = StorageConfig::getStoragesArr();

        $storage_names_arr = [];
        foreach (array_keys($storages_arr) as $storage_name) {
            $storage_names_arr[$storage_name] = $storage_name;
        }

        return $storage_names_arr;
    }
}