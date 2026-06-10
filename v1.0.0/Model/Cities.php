<?php

namespace Core2\Model;

/**
 * @property string $id
 * @property string $name_ru
 * @property string $name_be
 * @property string $region
 * @property string $area
 * @property string $population
 * @property string $foundation_year
 * @property string $is_active_sw
 * @property string $seq
 * @property string $lastuser
 * @property string $lastupdate
 */
class Cities extends \Zend_Db_Table_Abstract {

    protected $_name = 'mod_belarus_cities';


    /**
     * @param string $expr
     * @param mixed  $var
     * @return \Zend_Db_Table_Row_Abstract|null
     */
    public function exists(string $expr, mixed $var = []): ?\Zend_Db_Table_Row_Abstract {

        $sel = $this->select()->where($expr, $var);
        return $this->fetchRow($sel->limit(1));
    }


    /**
     * @param string $field
     * @param string $expr
     * @param mixed  $var
     * @return mixed
     */
    public function fetchOne(string $field, string $expr, mixed $var = []): mixed {

        $sel = $this->select();
        if ($var) {
            $sel->where($expr, $var);
        } else {
            $sel->where($expr);
        }
        $row = $this->fetchRow($sel);
        return $row ? $row->$field : null;
    }
}
