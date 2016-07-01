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


}