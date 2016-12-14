<?php


namespace OLOG\Storage;


class FileUploaderAjaxAction implements
    \OLOG\InterfaceAction
{
    const OPERATION_CODE_UPLOAD_FILE = 'OPERATION_CODE_UPLOAD_FILE';
    const FIELD_NAME_UPLOAD_FILE = 'FIELD_NAME_UPLOAD_FILE';
    const FIELD_NAME_UPLOAD_STORAGE_NAME = 'FIELD_NAME_UPLOAD_STORAGE_NAME';

    public function url()
    {
        return '/storage/upload';
    }

    public function action()
    {
        \OLOG\Exits::exit403If(!\OLOG\Auth\Operator::currentOperatorHasAnyOfPermissions([\OLOG\Storage\Permissions::PERMISSION_STORAGE_UPLOAD_FILES]));

        $return_arr = array('success' => false, 'error_message' => 'Ошибка метода запроса');
        \OLOG\Operations::matchOperation(self::OPERATION_CODE_UPLOAD_FILE, function () use (&$return_arr) {
            $return_arr = self::uploadFile();
        });

        \OLOG\Layouts\LayoutJSON::render($return_arr, $this);
    }

    protected static function uploadFile()
    {
        $uploaded_file_obj = \OLOG\POSTFileAccess::createObjByKey(self::FIELD_NAME_UPLOAD_FILE);
        $upload_storage_name = \OLOG\POSTAccess::getRequiredPostValue(self::FIELD_NAME_UPLOAD_STORAGE_NAME);

        $uploaded_file_path_in_storage = self::generateNewUniqFilePath($uploaded_file_obj->getExtension());
        $upload_storage_obj = StorageFactory::getStorageObjByName($upload_storage_name);
        $upload_storage_obj->copyToStorage($uploaded_file_obj->getTempFilepath(), $uploaded_file_path_in_storage);

        $file_obj = new \OLOG\Storage\File();
        $file_obj->setStorageName($upload_storage_name);
        $file_obj->setFilePathInStorage($uploaded_file_path_in_storage);
        $file_obj->setOriginalFileName($uploaded_file_obj->getOriginalFileName());
        $file_obj->save();

        return array('success' => true, 'file_id' => $file_obj->getId());
    }

    protected static function generateNewUniqFilePath($file_extention)
    {
        $filename = md5(uniqid('uploaded_file_', true)) . '.' . $file_extention;
        $first_folder_name = substr($filename, 0, 2);
        $second_folder_name = substr($filename, 2, 2);

        return \OLOG\FilePath::constructPath([$first_folder_name, $second_folder_name, $filename], true);
    }
}