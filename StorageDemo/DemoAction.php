<?php

namespace StorageDemo;

use Config\Config;
use OLOG\ActionInterface;
use OLOG\BT\LayoutBootstrap4;
use OLOG\CRUD\CForm;
use OLOG\CRUD\CTable;
use OLOG\CRUD\FRow;
use OLOG\CRUD\TCol;
use OLOG\CRUD\TWText;
use OLOG\H;
use OLOG\Storage\CRUDFormWidgetFile;
use OLOG\Storage\File;

class DemoAction implements ActionInterface
{
    public function url()
    {
        return '/';
    }

    public function action()
    {
        LayoutBootstrap4::render(function (){
            H::div('Upload requires permission, so you need to tune the user and login or use full access cookie.');

            echo CTable::html(
                DemoModel::class,
                CForm::html(
                    new DemoModel(),
                    [
                        new FRow('file', new CRUDFormWidgetFile(DemoModel::_FILE, [Config::STORAGE_DEMO]))
                    ]
                ),
                [
                    new TCol(
                        '',
                        new TWText(DemoModel::_FILE)
                    )
                ]
            );

            echo CTable::html(
                File::class,
                '',
                [
                    new TCol('id', new TWText(File::_ID)),
                    new TCol('storage name', new TWText(File::_STORAGE_NAME)),
                    new TCol('path in storage', new TWText(File::_FILE_PATH_IN_STORAGE)),
                    new TCol('original name', new TWText(File::_ORIGINAL_FILE_NAME))
                ]
            );
        }, $this);
    }

}