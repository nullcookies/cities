<?php

require_once DOC_ROOT . 'core2/inc/ajax.func.php';

/**
 * AJAX handlers for the cities module.
 *
 * Non-admin modules: the dispatcher prefixes every method with "ax",
 * so $edit->save("xajax_saveCity(...)") → PHP calls axSaveCity().
 */
class ModAjax extends ajaxFunc {


    /**
     * @param xajaxResponse $res
     */
    public function __construct(xajaxResponse $res) {

        parent::__construct($res);
        $this->module = 'cities';
    }


    /**
     * Save a city record (insert or update).
     *
     * Called via xajax_saveCity() from the edit form.
     *
     * @param array $data  Form values collected by xajax.getFormValues()
     * @return xajaxResponse
     * @throws \Exception
     */
    public function axSaveCities(array $data): xajaxResponse {

        $fields = [
            'name_ru' => 'req',
        ];

        $data['control'] = $this->clearData($data['control']);

        if ($this->ajaxValidate($data, $fields)) {
            return $this->response;
        }

        if ( ! $this->saveData($data)) {
            return $this->response;
        }

        $this->done($data);
        return $this->response;
    }
}
