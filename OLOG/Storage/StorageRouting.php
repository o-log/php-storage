<?php


namespace OLOG\Storage;


class StorageRouting
{
    public static function register()
    {
        \OLOG\Router::processAction(\OLOG\Storage\FileUploaderAjaxAction::class, 0);
    }
}