<?php

namespace OLOG\Storage;

use OLOG\Assert;

class StorageConfig
{
    static protected $storages_arr = [];

    static public function setStorage($storage_id, $storage_obj){
        self::$storages_arr[$storage_id] = $storage_obj;
    }

    static public function getStoragesArr(){
        return self::$storages_arr;
    }

    static public function getStorageObjById($storage_id){
        Assert::assert(array_key_exists($storage_id, self::$storages_arr));
        return self::$storages_arr[$storage_id];
    }
}