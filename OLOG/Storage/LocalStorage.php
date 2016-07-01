<?php


namespace OLOG\Storage;


class LocalStorage
{
    protected $root_path;
    protected $name;

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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function putFileToStorage($source_path, $destination_path_in_storage)
    {
        $destination_full_path = $this->getRootPath() . $destination_path_in_storage;
        $destination_directory = dirname($destination_full_path);

        if (!file_exists($destination_directory)) {
            if (!mkdir($destination_directory, 0777, true)) {
                $error_arr = error_get_last();
                throw new \Exception('mkdir ' . $destination_directory . ' error: ' . $error_arr['message']);
            }
        }

        if (copy($source_path, $destination_full_path)) {
            return;
        }

        $error_arr = error_get_last();
        throw new \Exception('copy error: ' . $error_arr['message']);
    }

    public function getFileFromStorage($source_path_in_storage, $destination_path)
    {
        $source_full_path = $this->getRootPath() . $source_path_in_storage;
        \OLOG\Assert::assert(file_exists($source_full_path), 'file ' . $source_full_path . ' not found');

        \OLOG\Assert::assert(is_file($source_full_path), 'file ' . $source_full_path . ' not found or is not file');

        $destination_directory = dirname($destination_path);
        if (!file_exists($destination_directory)) {
            if (!mkdir($destination_directory, 0777, true)) {
                $error_arr = error_get_last();
                throw new \Exception('mkdir ' . $destination_directory . ' error: ' . $error_arr['message']);
            }
        }

        if (copy($source_full_path, $destination_path)) {
            return;
        }

        $error_arr = error_get_last();
        throw new \Exception('copy error: ' . $error_arr['message']);
    }
}