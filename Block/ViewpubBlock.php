<?php
/**
 * Clip
 *
 * @copyright  (c) Clip Team
 * @link       http://github.com/zikula-modules/clip/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Clip
 * @subpackage Block
 */

namespace Matheo\Clip\Block;

use SecurityUtil;
use Matheo\Clip\Access;
use BlockUtil;
use DataUtil;
use LogUtil;
use Matheo\Clip\Util;
use ModUtil;
use FormUtil;

/**
 * Viewpub Block.
 */
class ViewpubBlock extends \Zikula_Controller_AbstractBlock
{
    /**
     * Initialise block.
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('Clip:block:viewpub', 'Block Id:Pubtype Id:');
    }
    
    /**
     * Get information on block.
     */
    public function info()
    {
        return array('module' => 'Clip', 'text_type' => $this->__('Clip Pub'), 'text_type_long' => $this->__('Clip publication block'), 'allow_multiple' => true, 'form_content' => false, 'form_refresh' => false, 'show_preview' => true);
    }
    
    /**
     * Display the block according its configuration.
     */
    public function display($blockinfo)
    {
        $alert = $this->getVar('devmode', false) && Access::toClip(ACCESS_ADMIN);
        // get variables from content block
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        // validation of required parameters
        if (!isset($vars['tid']) || empty($vars['tid'])) {
            return $alert ? $this->__f('Required parameter [%s] not set or empty.', 'tid') : null;
        }
        if (!isset($vars['pid']) || empty($vars['pid'])) {
            return $alert ? $this->__f('Required parameter [%s] not set or empty.', 'pid') : null;
        }
        if (!Util::validateTid($vars['tid'])) {
            return $alert ? LogUtil::registerError($this->__f('Error! Invalid publication type ID passed [%s].', DataUtil::formatForDisplay($vars['tid']))) : null;
        }
        // security check
        // FIXME SECURITY centralize on Access
        if (!SecurityUtil::checkPermission('Clip:block:viewpub', "{$blockinfo['bid']}:{$vars['tid']}:", ACCESS_OVERVIEW)) {
            return '';
        }
        // default values
        $template = isset($vars['template']) && !empty($vars['template']) ? $vars['template'] : '';
        $cachelt = isset($vars['cachelifetime']) ? $vars['cachelifetime'] : null;
        $args = array('tid' => $vars['tid'], 'pid' => $vars['pid'], 'template' => $template ? 'block_' . $template : 'block', 'cachelifetime' => $cachelt);
        $blockinfo['content'] = ModUtil::func('Clip', 'user', 'display', $args);
        if (empty($blockinfo['content'])) {
            return '';
        }
        return BlockUtil::themeBlock($blockinfo);
    }
    
    /**
     * Modify block settings.
     */
    public function modify($blockinfo)
    {
        // get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        // defaults
        if (!isset($vars['tid'])) {
            $vars['tid'] = '';
        }
        if (!isset($vars['pid'])) {
            $vars['pid'] = '';
        }
        if (!isset($vars['template'])) {
            $vars['template'] = '';
        }
        if (!isset($vars['cachelifetime'])) {
            $vars['cachelifetime'] = 0;
        }
        // builds the pubtypes selector
        $pubtypes = Util::getPubType(-1)->toKeyValueArray('tid', 'title');
        // builds the output
        $this->view->assign('vars', $vars)->assign('pubtypes', $pubtypes);
        // return output
        return $this->view->fetch('clip_block_viewpub_modify.tpl');
    }
    
    /**
     * Update block settings.
     */
    public function update($blockinfo)
    {
        $vars = array('tid' => FormUtil::getPassedValue('tid'), 'pid' => FormUtil::getPassedValue('pid'), 'template' => FormUtil::getPassedValue('template'), 'cachelifetime' => FormUtil::getPassedValue('cachelifetime'));
        $blockinfo['content'] = BlockUtil::varsToContent($vars);
        return $blockinfo;
    }
}
