<?php

require_once DOC_ROOT . 'core2/inc/classes/Common.php';

/**
 * @property \Core2\Model\Cities $dataCities
 */
class ModCitiesController extends \Common {

    protected $module = 'cities';


    public function __construct() {

        parent::__construct();
        $this->module = 'cities';
        $this->path   = 'mod/cities/';
    }


    /**
     * Main page: city list + inline edit/add form.
     */
    public function action_index(): void {

        require_once __DIR__ . '/classes/cities/Cities.php';
        $cities = new \Core2\Cities();
        $cities->table();
    }
}
