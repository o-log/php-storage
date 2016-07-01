<?php

namespace OLOG\Storage;

use OLOG\Assert;
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
        $storages_arr = ConfWrapper::getRequiredValue(StorageConfigKeys::ROOT . '.' . StorageConfigKeys::STORAGES_ARR);
        
        $storage_config_arr = ConfWrapper::getRequiredSubvalue($storages_arr, $storage_name);
        
        $storage_type = ConfWrapper::getRequiredSubvalue($storage_config_arr, StorageConfigKeys::STORAGE_TYPE);
        Assert::assert($storage_type == StorageConfigKeys::STORAGE_TYPE_LOCAL, 'unsupported storage type');
        
        $storage_root_path = ConfWrapper::getRequiredSubvalue($storage_config_arr, StorageConfigKeys::STORAGE_ROOT_PATH);
        
        $storage_obj = new LocalStorage($storage_name);
        $storage_obj->setName($storage_name);
        $storage_obj->setRootPath($storage_root_path);
        
        return $storage_obj;
    }

    /**
     * @return array
     */
    public static function getStorageNamesInConfigArr()
    {
        $storages_arr = ConfWrapper::getRequiredValue(StorageConfigKeys::ROOT . '.' . StorageConfigKeys::STORAGES_ARR);

        $storage_names_arr = [];
        foreach (array_keys($storages_arr) as $storage_name) {
            $storage_names_arr[$storage_name] = $storage_name;
        }

        return $storage_names_arr;
    }
}