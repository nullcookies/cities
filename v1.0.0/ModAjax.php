<?php
require_once("core2/inc/ajax.func.php");

class ModAjax extends ajaxFunc {

    private const TABLE = 'mod_belarus_cities';

    public function __construct(xajaxResponse $res) {
        parent::__construct($res);
        $this->module = 'cities';
    }

    public function axSaveCities(array $data): xajaxResponse {
        $fields = [
            'name_ru' => 'req',
        ];

        if ($this->ajaxValidate($data, $fields)) {
            return $this->response;
        }

        $errors = [];
        try {
            if (!$this->getSessFormField($data['class_id'], 'refid')) {
                $seq = $this->db->fetchOne("SELECT MAX(seq) + 5 FROM " . self::TABLE);
                $data['control']['seq'] = $seq ?: 5;
                if (empty($data['control']['is_active_sw'])) {
                    $data['control']['is_active_sw'] = 'Y';
                }
            }
            $this->saveData($data);
        } catch (Exception $e) {
            $errors[] = $e->getMessage();
        }

        if (empty($errors)) {
            $this->done($data);
        } else {
            $this->error[] = implode(", ", $errors);
            $this->displayError($data);
        }
        return $this->response;
    }
}
