<?php
require_once(DOC_ROOT . "core2/inc/classes/class.list.php");
require_once(DOC_ROOT . "core2/inc/classes/class.edit.php");
require_once(DOC_ROOT . "core2/inc/classes/class.tab.php");
require_once(DOC_ROOT . "core2/inc/classes/Alert.php");

class ModCitiesController extends Common {

    private const TABLE = 'mod_belarus_cities';

    public function action_index() {
        if (!$this->checkAcl('cities', 'access')) {
            throw new Exception(911);
        }

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        ob_start();

        $app = "index.php?module={$this->module}&action={$_GET['action']}";
        $tab = new tabs($this->resId);
        $tab->beginContainer($this->_("Города Беларуси"));

        if (isset($_GET['edit'])) {
            $edit = new editTable($this->resId);
            $id = (int)$_GET['edit'];

            $edit->setTable(self::TABLE);
            $edit->SQL = "SELECT id,
                                 name_ru,
                                 name_be,
                                 region,
                                 area,
                                 population,
                                 foundation_year,
                                 is_active_sw,
                                 seq
                          FROM " . self::TABLE . "
                          WHERE id = {$id}";

            $edit->addControl($this->_("Название (рус.):"), "TEXT", "maxlength=\"255\" size=\"40\"", "", "", true);
            $edit->addControl($this->_("Название (бел.):"), "TEXT", "maxlength=\"255\" size=\"40\"");
            $edit->addControl($this->_("Область:"), "TEXT", "maxlength=\"255\" size=\"40\"");
            $edit->addControl($this->_("Район:"), "TEXT", "maxlength=\"255\" size=\"40\"");
            $edit->addControl($this->_("Население:"), "NUMBER", "");
            $edit->addControl($this->_("Год основания:"), "TEXT", "maxlength=\"10\" size=\"10\"");
            $edit->addButtonSwitch(
                'is_active_sw',
                $id ? ($this->db->fetchOne("SELECT is_active_sw FROM " . self::TABLE . " WHERE id = ?", $id) ?: 'N') : 'N'
            );

            $edit->back = $app;
            $edit->save("saveCities(xajax.getFormValues(this.id))");
            $edit->addButton($this->_("Отменить"), "load('$app')");
            $edit->showTable();
        }

        $this->printJsModule('cities', '/js/cities.js');

        $list = new listTable($this->resId);
        $list->table = self::TABLE;

        $list->addSearch($this->_("Название (рус.)"), "name_ru", "TEXT");

        $list->SQL = "SELECT id, name_ru, name_be, region, population, is_active_sw
                      FROM " . self::TABLE . "
                      WHERE 1=1 ADD_SEARCH
                      ORDER BY seq, name_ru";

        $list->addColumn($this->_("Название (рус.)"), "", "TEXT");
        $list->addColumn($this->_("Название (бел.)"), "", "TEXT");
        $list->addColumn($this->_("Область"), "", "TEXT");
        $list->addColumn($this->_("Население"), "", "TEXT");
        $list->addColumn("", "1%", "STATUS_INLINE", self::TABLE . ".is_active_sw");

        $list->addURL  = $app . "&edit=0";
        $list->editURL = $app . "&edit=TCOL_00";
        $list->deleteKey = self::TABLE . ".id";

        $list->showTable();

        $tab->endContainer();

    }
}
