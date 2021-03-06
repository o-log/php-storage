<?php


namespace OLOG\Storage;


class LocalStorage
{
    protected $root_path;

    public function __construct($root_path)
    {
        $this->setRootPath($root_path);
    }

    /**
     * @return string
     */
    public function getRootPath()
    {
        return $this->root_path;
    }

    /**
     * @param string $root_path
     */
    public function setRootPath($root_path)
    {
        $this->root_path = $root_path;
    }

    public function copyToStorage($source_path_in_file_system, $destination_path_in_storage)
    {
        $destination_full_path_in_file_system = $this->getFullFilePathOrUrlInStorage($destination_path_in_storage);
        assert(!file_exists($destination_full_path_in_file_system), 'destination file ' . $destination_full_path_in_file_system . ' already exists!');

        $destination_directory_in_file_system = dirname($destination_full_path_in_file_system);

        if (!file_exists($destination_directory_in_file_system)) {
            if (!mkdir($destination_directory_in_file_system, 0777, true)) {
                $error_arr = error_get_last();
                throw new \Exception('mkdir ' . $destination_directory_in_file_system . ' error: ' . $error_arr['message']);
            }
        }

        if (copy($source_path_in_file_system, $destination_full_path_in_file_system)) {
            return;
        }

        $error_arr = error_get_last();
        throw new \Exception('copy error: ' . $error_arr['message']);
    }

    public function copyFromStorage($source_path_in_storage, $destination_path_in_file_system)
    {
        assert(!file_exists($destination_path_in_file_system), 'destination file ' . $destination_path_in_file_system . ' already exists!');

        $source_full_path_in_file_system = $this->getFullFilePathOrUrlInStorage($source_path_in_storage);
        assert(file_exists($source_full_path_in_file_system), 'source file ' . $source_full_path_in_file_system . ' not found');

        assert(is_file($source_full_path_in_file_system), 'source file ' . $source_full_path_in_file_system . ' is not file');

        $destination_directory_in_file_system = dirname($destination_path_in_file_system);
        if (!file_exists($destination_directory_in_file_system)) {
            if (!mkdir($destination_directory_in_file_system, 0777, true)) {
                $error_arr = error_get_last();
                throw new \Exception('mkdir ' . $destination_directory_in_file_system . ' error: ' . $error_arr['message']);
            }
        }

        if (copy($source_full_path_in_file_system, $destination_path_in_file_system)) {
            return;
        }

        $error_arr = error_get_last();
        throw new \Exception('copy error: ' . $error_arr['message']);
    }

    public function copyFromStorageToStorage($source_path_in_storage, $destination_storage_name, $destination_path_in_storage)
    {
        $source_full_path_in_file_system = $this->getFullFilePathOrUrlInStorage($source_path_in_storage);
        assert(file_exists($source_full_path_in_file_system), 'source file ' . $source_full_path_in_file_system . ' not found');

        assert(is_file($source_full_path_in_file_system), 'source file ' . $source_full_path_in_file_system . ' is not file');

        $destination_storage_obj = StorageFactory::getStorageObjByName($destination_storage_name);

        $destination_storage_obj->copyToStorage($source_full_path_in_file_system, $destination_path_in_storage);
    }

    public function getFullFilePathOrUrlInStorage($path_in_storage)
    {
        $full_path_in_file_system = \OLOG\FilePath::constructPath([$this->getRootPath(), $path_in_storage]);

        return $full_path_in_file_system;
    }

    public function deleteFileFromStorage($path_in_storage)
    {
        $full_path_in_file_system = self::getFullFilePathOrUrlInStorage($path_in_storage);
        unlink($full_path_in_file_system);
    }
}