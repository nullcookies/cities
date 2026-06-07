/**
 * Города Беларуси (cities)
 */
function saveCities(data) {
    post('SaveCities', 'module=cities&action=index', data);
}
