<?php

class Modulesgarden_Teamandtaskorganizer_Block_Adminhtml_Teamandtaskorganizer_Template_Grid_Renderer_Action
        extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{

    /**
     * Render action column and remove action links if user is not allowed to see this.
     * 
     * @param \Varien_Object $row row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $user = Mage::getSingleton('teamandtaskorganizer/user');

        $data = $this->getColumn()->getData();
        $actions = $this->getColumn()->getActions();

        if ($data['gridType'] === 'task_list') {
            if (!$user->isAllowedToEdit($row)) {
                unset($actions['edit']);
            }
            if (!$user->isAllowedToDelete($row)) {
                unset($actions['delete']);
            }
        }
        if ($data['gridType'] === 'comment_list') {
            if (!$user->isAllowedToEdit($row)) {
                unset($actions['edit']);
            }
            if (!$user->isAllowedToDelete($row)) {
                unset($actions['delete']);
            }
        }

        if (!($data['gridType'] == 'task_list' && !$user->isAllowedToEdit($row) && $user->getUserId() == $row->getUserId())) {
            unset($actions['view']);
        }

        $actions = array_values($actions);
        if (empty($actions) || !is_array($actions)) {
            return '&nbsp;';
        }

        if ($data['gridType'] == 'task_list') {
            $commentsStats = Modulesgarden_Teamandtaskorganizer_Model_Task::getCommentsStatsArray($user->getUserId());
            $commentsCount = isset($commentsStats[$row->getId()]) ? $commentsStats[$row->getId()]['total'] : 0;

            $actions['comments'] = array(
                'caption' => Mage::helper('teamandtaskorganizer')->__('Comments') . '&nbsp;(' . $commentsCount . ')',
                'url' => array(
                    'base' => '*/teamandtaskorganizer_tasks/edittask',
                    'params' => array('tab' => 'task_comments')
                ),
                'class' => 'show-comments',
                'field' => 'id',
            );
        }

        $out = array();
        if (!$this->getColumn()->getNoLink()) {
            foreach ($actions as $action) {
                if (is_array($action)) {
                    $out[] = $this->_toLinkHtml($action, $row);
                }
            }
        }
        return implode('&nbsp;', $out);
    }

}
