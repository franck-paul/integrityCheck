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
declare(strict_types=1);

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'IntegrityCheck',
        'Checks dotclear installation files integrity',
        'Bruno Hondelatte and contributors',
        '6.4',
        [
            'date'     => '2026-11-07T16:56:24+0100',
            'requires' => [['core', '2.36']],
            'type'     => 'plugin',

            'details'    => 'https://open-time.net/?q=integrityCheck',
            'support'    => 'https://github.com/franck-paul/integrityCheck',
            'repository' => 'https://raw.githubusercontent.com/franck-paul/integrityCheck/main/dcstore.xml',
            'license'    => 'gpl2',
        ]
    );
}
