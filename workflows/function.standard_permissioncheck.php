<?php
/**
 * Clip
 *
 * @copyright  (c) Clip Team
 * @link       http://github.com/zikula-modules/clip/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Clip
 * @subpackage Workflows
 */
/**
 * Permission check for workflow schema 'standard'.
 *
 * @param object $pub         Publication instance.
 * @param int    $permLevel   Permission level.
 * @param int    $currentUser Current User ID.
 * @param string $actionId    Action ID.
 *
 * @todo Take in account $actionId processing?
 *
 * @return bool True if allowed to execute the action, false otherwise.
 */
function Workflow_standard_permissioncheck($pub, $permLevel, $currentUser, $actionId)
{
    $pubtype = Matheo\Clip\Util::getPubType($pub->core_tid);
    if ($pubtype->enableeditown == 1 && strpos($actionId, 'author') === 0) {
        // check existing publication author
        if (!$pub->exists() || $pub->core_author == $currentUser) {
            // FIXME allow this only on update operations on offline publications only? not in approved?
            return true;
        }
    }
    return Matheo\Clip\Access::toPub($pubtype, $pub, null, 'exec', null, $permLevel, $currentUser, $actionId);
}
function Workflow_standard_gettextstrings()
{
    no__('Are you sure you want to delete this publication?');
    return array('title' => no__('Standard'), 'description' => no__('This is a two staged workflow with stages for untrusted submissions and finally approved publications. It does not allow corrections of non-editors to published pages.'), 'states' => array(no__('Waiting') => no__('Content has been submitted and is waiting for acceptance'), no__('Approved') => no__('Content has been approved and is available online')), 'actions' => array('initial' => array(no__('Submit and Approve') => no__('Submit a publication and approve immediately'), no__('Submit') => no__('Submit a publication for acceptance by a moderator')), 'waiting' => array(no__('Update and Approve') => no__('Update the content and approve for immediate publishing'), no__('Update') => no__('Update the content for later publishing'), no__('Approve') => no__('Approve the publication for immediate publishing'), no__('Trash') => no__('Move the publication to the recycle bin'), no__('Recover') => no__('Recover the publication from the recycle bin'), no__('Delete') => no__('Delete the publication permanently')), 'approved' => array(no__('Update') => no__('Update the publication content'), no__('Publish') => no__('Make the publication available'), no__('Unpublish') => no__('Hide the publication'), no__('Trash') => no__('Move the publication to the recycle bin'), no__('Recover') => no__('Recover the publication from the recycle bin'), no__('Delete') => no__('Delete the publication permanently'))));
}
