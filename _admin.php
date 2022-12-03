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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$_menu['System']->addItem(
    __('Integrity Check'),
    'plugin.php?p=integrityCheck',
    'images/check-on.png',
    preg_match('/plugin.php\?p=integrityCheck(&.*)?$/', $_SERVER['REQUEST_URI']),
    $core->auth->isSuperAdmin() && is_readable(DC_DIGESTS)
);
