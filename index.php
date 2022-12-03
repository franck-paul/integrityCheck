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

class adminCheckIntegrity
{
    /**
     * Initializes the page.
     */
    public static function init()
    {
        dcCore::app()->admin->has_bad_files = false;
        dcCore::app()->admin->updater       = new dcUpdate(DC_UPDATE_URL, 'dotclear', DC_UPDATE_VERSION, DC_TPL_CACHE . '/versions');
    }

    /**
     * Processes the request(s).
     */
    public static function process()
    {
        try {
            dcCore::app()->admin->updater->checkIntegrity(DC_ROOT . '/inc/digests', DC_ROOT);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (isset($e->bad_files)) {
                dcCore::app()->admin->has_bad_files = true;

                $msg = __('The following files differ from your initial dotclear installation :') .
                    '<ul><li><strong>' .
                    implode('</strong></li><li><strong>', $e->bad_files) .
                    '</strong></li></ul>';
            } else {
                $msg = __('An unexpected error occured : ') . $e->getMessage();
            }

            dcCore::app()->error->add($msg);
        }
    }

    /**
     * Renders the page.
     */
    public static function render()
    {
        echo
        '<html>' .
        '<head>' .
        '<title>' . __('Integrity Check') . '</title>' .
        '</head>' .
        '<body>' .
        dcPage::breadcrumb(
            [
                __('System')          => '',
                __('Integrity Check') => '',
            ]
        ) .
        dcPage::notices();

        if (!dcCore::app()->admin->has_bad_files) {
            echo
            '<h2>' . __('Diagnostics') . '</h2>' .
            '<p class="message">' . __('All your installation files are correct.') . '</p>';
        }

        echo
        '</body>' .
        '</html>';
    }
}

adminCheckIntegrity::init();
adminCheckIntegrity::process();
adminCheckIntegrity::render();
