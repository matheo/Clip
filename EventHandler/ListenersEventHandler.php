<?php
/**
 * Clip
 *
 * @copyright  (c) Clip Team
 * @link       http://github.com/zikula-modules/clip/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Clip
 * @subpackage EventHandler
 */

namespace Matheo\Clip\EventHandler;
use Zikula_Event;

/**
 * Listeners EventHandler.
 */
class ListenersEventHandler
{
    /**
     * Decorate the Admin Controller output with the panel header.
     *
     * @param Zikula_Event $event
     */
    public static function decorateOutput(Zikula_Event $event)
    {
        // intercept the Admin Controller output only
        if (get_class($event->getSubject()) == 'Clip_Controller_Admin') {
            /** @var \Zikula_View $view */
            $view = $event->getSubject()->getView();
            // acts only when the request type is 'admin'
            if ($view->getRequest()->getControllerName() == 'admin') {
                $func = $event->getArg('modfunc');
                // and only for the methods using base templates
                if (in_array($func[1], array('pubtypeinfo', 'pubtype', 'pubfields', 'relations', 'generator'))) {
                    $view->assign('maincontent', $event->getData());
                    $output = $view->fetch('clip_admin_decorator.tpl');
                    $event->setData($output);
                }
            }
        }
    }
    
    /**
     * Example provider handler.
     *
     * Simple add to, or override elements of the the array contained in $event->data
     *
     * @param Zikula_Event $event
     */
    public static function getFormPlugins(Zikula_Event $event)
    {
        
    }
    
    /**
     * Filters provider handler.
     *
     * Attach the Clip filters to the available ones
     *
     * @param Zikula_Event $event
     */
    public static function getFilterClasses(Zikula_Event $event)
    {
        $classNames = array();
        $classNames['cliplist'] = 'Clip_Filter_Handler_List';
        $classNames['clipmlist'] = 'Clip_Filter_Handler_MultiList';
        $classNames['clipuser'] = 'Clip_Filter_Handler_User';
        $classNames['clipgroup'] = 'Clip_Filter_Handler_Group';
        $classNames['clipdate'] = 'Clip_Filter_Handler_Date';
        $event->setData(array_merge((array) $event->getData(), $classNames));
    }
    
    /**
     * ContentType discovery event handler.
     * 
     * @param Zikula_Event $event
     */
    public static function getContentTypes(Zikula_Event $event)
    {
        $types = $event->getSubject();
        // add content types with add('classname')
        $types->add('Clip_ContentType_ClipPub');
        $types->add('Clip_ContentType_ClipPublist');
    }

}
