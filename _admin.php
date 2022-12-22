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

dcCore::app()->menu[dcAdmin::MENU_SYSTEM]->addItem(
    __('Integrity Check'),
    dcCore::app()->adminurl->get('admin.plugin.integrityCheck'),
    'images/check-on.png',
    preg_match('/plugin.php\?p=integrityCheck(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->isSuperAdmin() && is_readable(DC_DIGESTS)
);
