<?php
/**
 * Clip
 *
 * @copyright  (c) Clip Team
 * @link       http://github.com/zikula-modules/clip/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    Clip
 * @subpackage Filter
 */

namespace Matheo\Clip\Filter\Handler;

use CategoryUtil;

class MultiList extends \Matheo\Clip\Filter\Handler\List
{
    /**
     * Returns the operators the plugin can handle.
     *
     * @return array Operators.
     */
    public function availableOperators()
    {
        return array('like', 'eq', 'ne', 'sub', 'dis', 'null', 'notnull');
    }
    
    /**
     * Returns DQL code.
     *
     * @param string $field Field name.
     * @param string $op    Operator.
     * @param string $value Test value.
     *
     * @return array Doctrine Query where clause and parameters.
     */
    public function getDql(
        $field,
        $op,
        $value
    ) {
        if (array_search($op, $this->availableOperators()) === false || array_search($field, $this->getFields()) === false) {
            return '';
        }
        $where = '';
        $params = array();
        $column = $this->getColumn($field);
        switch ($op) {
            case 'like':
            case 'eq':
                $where = "{$column} LIKE ?";
                $params[] = '%:' . $value . ':%';
                break;
            case 'ne':
                $where = "{$column} NOT LIKE ?";
                $params[] = '%:' . $value . ':%';
                break;
            case 'sub':
            case 'dis':
                $opr = $op == 'sub' ? 'LIKE' : 'NOT LIKE';
                $where = "{$column} {$opr} ?";
                $params[] = '%:' . $value . ':%';
                $cats = CategoryUtil::getSubCategories($value);
                foreach ($cats as $item) {
                    $where .= ($op == 'sub' ? ' OR' : ' AND') . " {$column} {$opr} ?";
                    $params[] = '%:' . $item['id'] . ':%';
                }
                break;
            case 'null':
                $where = "({$column} = '::' OR {$column} IS NULL)";
                break;
            case 'notnull':
                $where = "({$column} <> '::' OR {$column} IS NOT NULL)";
                break;
        }
        return array('where' => $where, 'params' => $params);
    }

}
