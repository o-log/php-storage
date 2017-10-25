<?php
namespace OLOG\Storage;

use OLOG\DB\DB;
use OLOG\Model\ActiveRecordInterface;
use OLOG\Model\ActiveRecordTrait;
use OLOG\Model\ProtectPropertiesTrait;

class File implements
    ActiveRecordInterface
{
    use ActiveRecordTrait;
    use ProtectPropertiesTrait;

    const DB_ID = StorageConstants::SPACE_PHPSTORAGE;
    const DB_TABLE_NAME = 'olog_storage_file';

    const _ID = 'id';
    const _CREATED_AT_TS = 'created_at_ts';
    const _STORAGE_NAME = 'storage_name';
    const _FILE_PATH_IN_STORAGE = 'file_path_in_storage';
    const _ORIGINAL_FILE_NAME = 'original_file_name';

    protected $id;
    protected $created_at_ts; // initialized by constructor
    protected $storage_name;
    protected $file_path_in_storage;
    protected $original_file_name;

    public function __construct()
    {
        $this->created_at_ts = time();
    }

    static public function getAllIdsArrByCreatedAtDesc($offset = 0, $page_size = 30)
    {
        $ids_arr = DB::readColumn(
            self::DB_ID,
            'select ' . self::_ID . ' from ' . self::DB_TABLE_NAME . ' order by ' . self::_CREATED_AT_TS . ' desc limit ' . intval($page_size) . ' offset ' . intval($offset)
        );
        return $ids_arr;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCreatedAtTs()
    {
        return $this->created_at_ts;
    }

    /**
     * @param int $timestamp
     */
    public function setCreatedAtTs($timestamp)
    {
        $this->created_at_ts = $timestamp;
    }

    /**
     * @return string
     */
    public function getStorageName()
    {
        return $this->storage_name;
    }

    /**
     * @param string $storage_name
     */
    public function setStorageName($storage_name)
    {
        $this->storage_name = $storage_name;
    }

    /**
     * @return string
     */
    public function getFilePathInStorage()
    {
        return $this->file_path_in_storage;
    }

    /**
     * @param string $file_path_in_storage
     */
    public function setFilePathInStorage($file_path_in_storage)
    {
        $this->file_path_in_storage = $file_path_in_storage;
    }

    /**
     * @return string
     */
    public function getOriginalFileName()
    {
        return $this->original_file_name;
    }

    /**
     * @param string $original_file_name
     */
    public function setOriginalFileName($original_file_name)
    {
        $this->original_file_name = $original_file_name;
    }

    public function afterDelete()
    {
        $this->removeFromFactoryCache();

        $file_path_in_storage = $this->getFilePathInStorage();
        $storage_name = $this->getStorageName();

        if ($file_path_in_storage && $storage_name) {
            $storage_obj = StorageFactory::getStorageObjByName($storage_name);
            $storage_obj->deleteFileFromStorage($file_path_in_storage);
        }
    }
}