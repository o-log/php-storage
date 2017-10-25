<?php


namespace OLOG\Storage;


use OLOG\Router;

class StorageRouting
{
    public static function register()
    {
        Router::action(FileUploaderAjaxAction::class, 0);
    }
}