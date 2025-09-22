<?php

/**
 * @brief integrityCheck, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Bruno Hondelatte and contributors
 *
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
$this->registerModule(
    'IntegrityCheck',
    'Checks dotclear installation files integrity',
    'Bruno Hondelatte and contributors',
    '6.1',
    [
        'date'     => '2025-09-22T10:30:28+0200',
        'requires' => [['core', '2.36']],
        'type'     => 'plugin',

        'details'    => 'https://open-time.net/?q=integrityCheck',
        'support'    => 'https://github.com/franck-paul/integrityCheck',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/integrityCheck/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
