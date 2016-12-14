<?php


namespace OLOG\Storage;

use OLOG\BT\BT;
use OLOG\CRUD\CRUDFieldsAccess;
use OLOG\CRUD\InterfaceCRUDFormWidget;
use OLOG\HTML;
use OLOG\Sanitize;

class CRUDFormWidgetFile implements InterfaceCRUDFormWidget
{
    protected $field_name;
    protected $storages_arr;
    protected $is_required;

    /**
     * CRUDFormWidgetFile constructor.
     * @param string $field_name
     * @param array $storages_arr
     * @param bool $is_required
     */
    public function __construct($field_name, array $storages_arr, $is_required = false)
    {
        $this->setFieldName($field_name);
        $this->setStoragesArr($storages_arr);
        $this->setIsRequired($is_required);
    }

    /**
     * @return boolean
     */
    public function isIsRequired()
    {
        return $this->is_required;
    }

    /**
     * @param boolean $is_required
     */
    public function setIsRequired($is_required)
    {
        $this->is_required = $is_required;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * @param string $field_name
     */
    public function setFieldName($field_name)
    {
        $this->field_name = $field_name;
    }

    /**
     * @return array
     */
    public function getStoragesArr()
    {
        return $this->storages_arr;
    }

    /**
     * @param array $storages_arr
     */
    public function setStoragesArr($storages_arr)
    {
        $this->storages_arr = $storages_arr;
    }

    public function html($obj)
    {
        $field_name = $this->getFieldName();
        $field_value = CRUDFieldsAccess::getObjectFieldValue($obj, $field_name);

        $is_null_value = '';

        if (is_null($field_value)) {
            $is_null_value = "1";
        }

        $select_element_id = 'js_select_' . rand(1, 999999);
        $choose_form_element_id = 'collapse_' . rand(1, 999999);

        $is_required_str = '';
        if ($this->isIsRequired()) {
            $is_required_str = 'required';
        }

        $html = '';
        $html .= '<div class="input-group">';

        if (true) {
            $html .= '<span class="input-group-btn">';
            $html .= '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#' . $choose_form_element_id . '"><span class="glyphicon glyphicon-upload"></span></button>';
            $html .= '</span>';
        }

        $html .= '<span class="input-group-btn">';
        $html .= '<button type="button" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '_btn_is_null" class="btn btn-default" data-toggle="modal"><span class="glyphicon glyphicon-remove"></span></button>';
        $html .= '</span>';
        $html .= '<input type="hidden" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '_is_null" name="' . Sanitize::sanitizeAttrValue($field_name) . '___is_null" value="' . $is_null_value . '"/>';
        $html .= '<input readonly ' . $is_required_str . ' type="input" id="' . Sanitize::sanitizeAttrValue($select_element_id) . '" name="' . Sanitize::sanitizeAttrValue($field_name) . '" class="form-control" value="' . $field_value . '"/>';
        $html .= '</div>';


        $upload_form_id = 'file_ajax_upload_form_' . uniqid();
        $upload_form = HTML::tag('div', ['id' => $upload_form_id], function () {

            echo HTML::tag('div', ['class' => 'form-group'], function () {
                $storage_select_styles = '';
                if (count($this->getStoragesArr()) == 1) {
                    $storage_select_styles = 'display: none;';
                }

                echo \OLOG\HTML::tag('select', [
                    'name' => \OLOG\Storage\FileUploaderAjaxAction::FIELD_NAME_UPLOAD_STORAGE_NAME,
                    'style' => $storage_select_styles,
                    'class' => 'form-control',
                    'onchange' => 'processUpload();'
                ], function () {
                    if (count($this->getStoragesArr()) != 1) {
                        echo '<option></option>';
                    }
                    foreach ($this->getStoragesArr() as $storage_name => $storage_id) {
                        echo '<option value="' . \OLOG\Sanitize::sanitizeAttrValue($storage_id) . '">' . \OLOG\Sanitize::sanitizeTagContent($storage_name) . '</option>';
                    }
                });
            });

            echo HTML::tag('div', ['class' => 'form-group'], function () {
                echo \OLOG\HTML::tag('input', [
                    'type' => 'file',
                    'name' => \OLOG\Storage\FileUploaderAjaxAction::FIELD_NAME_UPLOAD_FILE,
                    'class' => 'form-control',
                    'onchange' => 'processUpload();'
                ], '');
            });

            echo \OLOG\HTML::tag('div', ['class' => 'alert alert-danger', 'role' => 'alert', 'style' => 'display: none;'], '');
            echo \OLOG\HTML::tag('div', ['class' => 'progress', 'style' => 'display: none;'], function () {
                echo \OLOG\HTML::tag('div', [
                    'class' => 'progress-bar',
                    'role' => 'progressbar',
                    'aria-valuenow' => '0',
                    'aria-valuemin' => '0',
                    'aria-valuemax' => '100',
                    'style' => 'width: 0;',
                ], '');
            });
        });

        ob_start();
        ?>

        <script type="text/javascript">
            $('#<?= $select_element_id ?>_btn_is_null').on('click', function (e) {
                e.preventDefault();
                $('#<?= $select_element_id ?>').val('').trigger('change');
                $('#<?= $select_element_id ?>_is_null').val(1);
            });

            function processUpload() {
                var $upload_form = $('#<?= $upload_form_id ?>');
                var $upload_storage_name_input = $('[name="<?= \OLOG\Storage\FileUploaderAjaxAction::FIELD_NAME_UPLOAD_STORAGE_NAME ?>"]', $upload_form);
                var $upload_file_input = $('[name="<?= \OLOG\Storage\FileUploaderAjaxAction::FIELD_NAME_UPLOAD_FILE ?>"]', $upload_form);

                if (($upload_storage_name_input.val() == '') || ($upload_file_input.val() == '')) {
                    return;
                }

                var upload_file_name = $upload_file_input[0].files[0];
                if ((typeof upload_file_name == "undefined") || (upload_file_name == '')) {
                    return;
                }
                $upload_storage_name_input.attr("disabled", true);
                $upload_file_input.attr("disabled", true);

                var form_data = new FormData();
                form_data.append("<?= \OLOG\Storage\FileUploaderAjaxAction::FIELD_NAME_UPLOAD_STORAGE_NAME ?>", $upload_storage_name_input.val());
                form_data.append("<?= \OLOG\Storage\FileUploaderAjaxAction::FIELD_NAME_UPLOAD_FILE ?>", upload_file_name);
                form_data.append("<?= \OLOG\Operations::FIELD_NAME_OPERATION_CODE?>", "<?= \OLOG\Storage\FileUploaderAjaxAction::OPERATION_CODE_UPLOAD_FILE ?>");

                var upload_errors = $(".alert", $upload_form);
                upload_errors.fadeOut();

                var progress_bar = $(".progress-bar", $upload_form);
                var progress_bar_div = $(".progress", $upload_form);
                progress_bar_div.fadeIn();

                $.ajax({
                    type: "post",
                    url: "<?= (new FileUploaderAjaxAction)->url() ?>",
                    data: form_data,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();

                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentage = Math.floor((evt.loaded / evt.total) * 100);
                                progress_bar.width(percentage + "%").attr("aria-valuenow", percentage);
                            }
                        }, false);

                        return xhr;
                    }
                }).done(function (data) {
                    $upload_storage_name_input.attr("disabled", false);
                    $upload_file_input.attr("disabled", false);
                    progress_bar_div.fadeOut();

                    if (!data.success) {
                        upload_errors.html(data.error_message);
                        upload_errors.fadeIn();
                        return;
                    }

                    $('#<?= $choose_form_element_id ?>').modal('hide');
                    $('#<?= $select_element_id ?>_is_null').val('');
                    $('#<?= $select_element_id ?>').val(data.file_id);

                }).fail(function () {
                    $upload_storage_name_input.attr("disabled", false);
                    $upload_file_input.attr("disabled", false);
                    progress_bar_div.fadeOut();

                    upload_errors.html('Ошибка сервера');
                    upload_errors.fadeIn();
                });
            }
        </script>

        <?php
        $upload_script = ob_get_clean();

        $html .= BT::modal($choose_form_element_id, 'Закачать файл', $upload_form . $upload_script);

        return $html;
    }
}