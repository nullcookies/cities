<?php

namespace Core2;

require_once DOC_ROOT . 'core2/inc/classes/Common.php';
require_once DOC_ROOT . 'core2/inc/classes/class.list.php';
require_once DOC_ROOT . 'core2/inc/classes/class.edit.php';
require_once DOC_ROOT . 'core2/inc/classes/class.tab.php';

/**
 * @property \Core2\Model\Cities $dataCities
 */
class Cities extends \Common {

    private string $app = 'index.php?module=cities';


    /**
     * Renders the main city management page (list + inline edit/add form).
     */
    public function table(): void {

        $tab = new \tabs('cities');
        $tab->beginContainer('Cities');

        if (isset($_GET['edit'])) {
            $this->renderEditForm((int)$_GET['edit']);
        }

        $this->renderList();

        $tab->endContainer();
    }


    /**
     * Renders the edit / add form for a single city.
     *
     * @param int $refId  0 = new record, >0 = edit existing
     */
    private function renderEditForm(int $refId): void {

        $edit = new \editTable('cities');

        if ($refId === 0) {
            $edit->SQL = "
                SELECT id,
                       name_ru,
                       name_be,
                       region,
                       area,
                       population,
                       foundation_year,
                       is_active_sw
                FROM mod_belarus_cities
                WHERE 1=2
            ";
        } else {
            $edit->SQL = $this->db->quoteInto("
                SELECT id,
                       name_ru,
                       name_be,
                       region,
                       area,
                       population,
                       foundation_year,
                       is_active_sw
                FROM mod_belarus_cities
                WHERE id = ?
            ", $refId);
        }

        $edit->addControl('Название (рус.):', 'TEXT', 'maxlength="255" size="60"', '', '', true);
        $edit->addControl('Название (бел.):', 'TEXT', 'maxlength="255" size="60"');
        $edit->addControl('Область:', 'TEXT', 'maxlength="255" size="60"');
        $edit->addControl('Район:', 'TEXT', 'maxlength="255" size="60"');
        $edit->addControl('Население:', 'NUMBER', '');
        $edit->addControl('Год основания:', 'TEXT', 'maxlength="10" size="10"');
        $edit->addButtonSwitch('is_active_sw', 1);

        $edit->back = $this->app;
        $edit->save("saveCities(xajax.getFormValues(this.id))");
        $edit->showTable();
    }


    /**
     * Renders the searchable cities list table.
     */
    private function renderList(): void {

        $list = new \listTable('mod_belarus_cities');

        $list->table = 'mod_belarus_cities';
        $list->SQL   = "
            SELECT id,
                   name_ru,
                   name_be,
                   region,
                   population,
                   is_active_sw
            FROM mod_belarus_cities
            WHERE 1=1 ADD_SEARCH
            ORDER BY seq, name_ru
        ";

        $list->addSearch('Название (рус.)', 'mod_belarus_cities.name_ru', 'text');

        $list->addColumn('Название (рус.)', '300px', 'TEXT');
        $list->addColumn('Название (бел.)', '',      'TEXT');
        $list->addColumn('Область',         '',      'TEXT');
        $list->addColumn('Население',       '200px', 'TEXT');
        $list->addColumn('',                '1%',    'STATUS_INLINE', 'mod_belarus_cities.is_active_sw');

        $list->addURL    = $this->app . '&edit=0';
        $list->editURL   = $this->app . '&edit=TCOL_00';
        $list->deleteKey = 'mod_belarus_cities.id';

        $list->showTable();
    }
}
