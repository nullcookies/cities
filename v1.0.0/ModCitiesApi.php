<?php

require_once DOC_ROOT . 'core2/inc/classes/CommonApi.php';

/**
 * REST API for the cities module.
 *
 * Handles the DELETE requests fired by the list table's multi-select
 * delete button. The JS sends:
 *   DELETE  cities/index/cities?cities.id=1,2,3
 *
 * @property \Core2\Model\Cities $dataCities
 */
class ModCitiesApi extends CommonApi {


    /**
     * @throws \Exception
     */
    public function action_index(): array {

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'DELETE':
                return $this->handleDelete();

            default:
                throw new \Exception('Method not allowed', 405);
        }
    }


    /**
     * Deletes cities by id.
     * Parses the raw query string (e.g. "cities.id=1,2,3") directly
     * because PHP converts "." to "_" in parsed superglobals.
     */
    private function handleDelete(): array {

        $raw = $_SERVER['QUERY_STRING'] ?? '';

        if ($raw && str_contains($raw, '.') && str_contains($raw, '=')) {
            [$keyPart, $valuePart] = explode('=', $raw, 2);
            $parts = explode('.', $keyPart, 2);

            if (count($parts) === 2) {
                [$table, $field] = $parts;

                if ($table === 'mod_belarus_cities' && $field === 'id') {
                    $ids = array_filter(array_map('intval', explode(',', $valuePart)));

                    foreach ($ids as $id) {
                        $row = $this->dataCities->find($id)->current();
                        if ($row) {
                            $row->delete();
                        }
                    }
                }
            }
        }

        return ['loc' => 'index.php?module=cities'];
    }
}
