<?php
/**
 * Clip
 *
 * @copyright  (c) Clip Team
 * @link       http://github.com/zikula-modules/clip/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Clip
 * @subpackage Workflows_Operations
 */
/**
 * create operation.
 *
 * @param object $pub              Publication object to create.
 * @param bool   $params['online'] Online value for the new publication (optional) (default: 0).
 * @param bool   $params['silent'] Hide or display a status/error message (optional) (default: false).
 * @param string $params['goto']   Goto redirection when the operation is successful (optional).
 *
 * @return bool|array False on failure or Publication core_uniqueid as index with true as value.
 */
function Clip_operation_create(&$pub, $params)
{
    $dom = ZLanguage::getModuleDomain('Clip');
    // parameters processing
    $params['online'] = isset($params['online']) ? (int) (bool) $params['online'] : 0;
    $params['silent'] = isset($params['silent']) ? (bool) $params['silent'] : false;
    $params['goto'] = isset($params['goto']) ? $params['goto'] : null;
    // assign the online value
    $pub['core_online'] = $params['online'];
    // initializes the result flag
    $result = false;
    // utility vars
    $tbl = Doctrine_Core::getTable('ClipModels_Pubdata' . $pub['core_tid']);
    // validate or find a new pid
    if (isset($pub['core_pid']) && !empty($pub['core_pid'])) {
        if (count($tbl->findBy('core_pid', $pub['core_pid']))) {
            return LogUtil::registerError(__('Error! The fixed publication id already exists on the database. Please contact the administrator.', $dom));
        }
    }
    // save the object
    if ($pub->isValid()) {
        $pub->trySave();
        $result = array($pub['core_uniqueid'] => true);
        // hooks: let know that a publication was created
        $pub->notifyHooks('process_edit');
        // event: notify the operation data
        $pub = Matheo\Clip\EventHelper::notify('data.edit.operation.create', $pub, $params)->getData();
    }
    // goto handling
    if ($result) {
        if ($params['goto']) {
            $result['goto'] = $params['goto'];
        } else {
            if (isset($pub['core_goto'])) {
                $result['goto'] = $pub['core_goto'];
            } else {
                // setup a redirect after creation
                $result['goto'] = !$pub['core_online'] ? 'pending' : 'display';
            }
        }
    }
    // output message
    if (!$params['silent']) {
        if ($result) {
            if ($pub['core_online']) {
                LogUtil::registerStatus(__('Done! Publication created.', $dom));
            }
        } else {
            LogUtil::registerError(__('Error! Failed to create the publication.', $dom));
            if (ModUtil::getVar('Clip', 'devmode', false) && $pub->getErrorStackAsString()) {
                LogUtil::registerError($pub->getErrorStackAsString());
            }
        }
    }
    // returns the operation result
    return $result;
}
