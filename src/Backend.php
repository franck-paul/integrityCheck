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

namespace Dotclear\Plugin\integrityCheck;

use dcAdmin;
use dcCore;
use dcNsProcess;

class Backend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        dcCore::app()->menu[dcAdmin::MENU_SYSTEM]->addItem(
            __('Integrity Check'),
            dcCore::app()->adminurl->get('admin.plugin.integrityCheck'),
            'images/check-on.png',
            preg_match('/plugin.php\?p=integrityCheck(&.*)?$/', $_SERVER['REQUEST_URI']),
            dcCore::app()->auth->isSuperAdmin() && is_readable(DC_DIGESTS)
        );

        return true;
    }
}
