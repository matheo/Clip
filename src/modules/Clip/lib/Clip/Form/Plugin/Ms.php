<?php
/**
 * Clip
 *
 * @copyright  (c) Clip Team
 * @link       http://code.zikula.org/clip/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Clip
 * @subpackage Form_Plugin
 */

class Clip_Form_Plugin_Ms extends Zikula_Form_Plugin_TextInput
{
    public $pluginTitle;
    public $columnDef = 'C(255)';

    public function setup()
    {
        $this->setDomain(ZLanguage::getModuleDomain('Clip'));
        
        //! field type name
        $this->pluginTitle = $this->__('Mediashare');
    }

    public function getFilename()
    {
        return __FILE__;
    }

    /**
     * Clip processing methods.
     */
    public static function postRead(&$pub, $field)
    {
        $fieldname = $field['name'];
        $data = $pub[$fieldname];

        // default
        $cat = array();

        // if there's a value extract the category
        if (!empty($data) && is_numeric($data)) {
            $cat  = CategoryUtil::getCategoryByID($data);

            if ($cat) {
                $lang = ZLanguage::getLanguageCode();

                // compatible mode to pagesetter
                $cat['fullTitle'] = isset($cat['display_name'][$lang]) ? $cat['display_name'][$lang] : $cat['name'];
                $cat['value']     = $cat['name'];
                $cat['title']     = $cat['name'];
            }
        }

        $pub[$fieldname] = $cat;
    }
}
