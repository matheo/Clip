<?php
/**
 * Clip
 *
 * @copyright  (c) Clip Team
 * @link       http://github.com/zikula-modules/clip/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Clip
 * @subpackage Model
 */

namespace Matheo\Clip\Model;

class WorkflowVarsModel extends \Doctrine_Record
{
    /**
     * Set table definition.
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $this->setTableName('Workflowvars');
        $this->hasColumn(
            'id as id',
            'integer',
            4,
            array('primary' => true, 'autoincrement' => true)
        );
        $this->hasColumn(
            'tid as tid',
            'integer',
            4,
            array('notnull' => true, 'default' => 0)
        );
        $this->hasColumn(
            'workflow as workflow',
            'string',
            255,
            array('notnull' => true, 'default' => '')
        );
        $this->hasColumn(
            'setting as setting',
            'string',
            255,
            array('notnull' => true, 'default' => '')
        );
        $this->hasColumn(
            'value as value',
            'string',
            65535,
            array('notnull' => true, 'default' => '')
        );
    }
    
    /**
     * Record setup.
     *
     * @return void
     */
    public function setUp()
    {
        
    }

}
