-- Cities module: table
CREATE TABLE IF NOT EXISTS `mod_belarus_cities` (
    `id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name_ru`         VARCHAR(255)     NOT NULL,
    `name_be`         VARCHAR(255)     NOT NULL,
    `region`          VARCHAR(255)     NOT NULL,
    `area`            VARCHAR(255)     NOT NULL,
    `population`      INT(10) UNSIGNED NOT NULL DEFAULT 0,
    `foundation_year` VARCHAR(10)      NOT NULL,
    `is_active_sw`    ENUM('Y','N')    NOT NULL DEFAULT 'Y',
    `seq`             INT(11)          NOT NULL DEFAULT 0,
    `lastuser`        INT(10) UNSIGNED DEFAULT NULL,
    `lastupdate`      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Register module in core_modules
INSERT INTO `core_modules` (`module_id`, `m_name`, `visible`, `is_system`, `version`, `seq`, `access_default`)
VALUES (
    'cities',
    'Города Беларуси',
    'Y',
    'N',
    '1.0.0',
    (SELECT COALESCE(MAX(seq), 0) + 5 FROM core_modules AS t),
    base64_encode(serialize([]))
)
ON DUPLICATE KEY UPDATE `visible` = 'Y', `version` = '1.0.0';
