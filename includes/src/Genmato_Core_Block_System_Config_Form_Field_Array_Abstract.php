<?php

/**
 * @category    Genmato
 * @package     Genmato_Core
 * @copyright   Copyright (c) 2014 Genmato BV (http://genmato.com)
 */
abstract class Genmato_Core_Block_System_Config_Form_Field_Array_Abstract extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_addButton = true;

    /**
     * Add a column to array-grid
     *
     * @param string $name
     * @param array $params
     */
    public function addColumn($name, $params)
    {
        $this->_columns[$name] = array(
            'label' => empty($params['label']) ? 'Column' : $params['label'],
            'title' => empty($params['title']) ? null : $params['title'],
            'action' => empty($params['action']) ? null : $params['action'],
            'size' => empty($params['size']) ? false : $params['size'],
            'style' => empty($params['style']) ? null : $params['style'],
            'class' => empty($params['class']) ? null : $params['class'],
            'renderer' => false,
            'type' => 'text',
            'options' => false,
        );
        if ((!empty($params['renderer'])) && ($params['renderer'] instanceof Mage_Core_Block_Abstract)) {
            $this->_columns[$name]['renderer'] = $params['renderer'];
        }

        if ((!empty($params['type']))) {
            $this->_columns[$name]['type'] = $params['type'];
        }

        if ((!empty($params['options']))) {
            $this->_columns[$name]['options'] = $params['options'];
        }
    }


    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column = $this->_columns[$columnName];
        $inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
        if ($column['renderer']) {
            return $column['renderer']->setInputName($inputName)->setColumnName($columnName)->setColumn(
                $column
            )->toHtml();
        }

        $html = "";
        switch ($column['type']) {
            case "newrow":
                $html = "</tr><tr>";
                break;
            case "hidden":
                $html = '<input type="hidden" name="' . $inputName .
                    '" value="#{' . $columnName . '}" ' .
                    ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
                    ' class="' . (isset($column['class']) ? $column['class'] : 'input-text') . '"' .
                    (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '/>';
                break;
            case "readonly":
                $html = '<input type="text" readonly name="' . $inputName .
                    '" value="#{' . $columnName . '}" ' .
                    ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
                    ' class="' . (isset($column['class']) ? $column['class'] : 'input-text') . '"' .
                    (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '/>';
                break;
            case "text":
                $html = '<input type="text" name="' . $inputName .
                    '" value="#{' . $columnName . '}" ' .
                    ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
                    ' class="' . (isset($column['class']) ? $column['class'] : 'input-text') . '"' .
                    (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '/>';
                break;
            case "textarea":
                $html = '<textarea name="' . $inputName . ' ' .
                    ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
                    ' class="' . (isset($column['class']) ? $column['class'] : 'input-text') . '"' .
                    (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') .
                    '">#{' . $columnName . '}</textarea>';
                break;
            case "button":
                $html = '<button type="button" onclick=' . $column['action'] . '><span>' .
                    $column['title'] . '</span></button>';
                break;
            case "file":
                $html = '<input type="file" name="' . $inputName . '"' .
                    ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
                    ' class="' . (isset($column['class']) ? $column['class'] : '') . '"' .
                    (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '/>';
                $html .= '<br>#{' . $columnName . '}';
                break;
            case "multiselect":
                $html = '<input type="hidden" value="#{' . $columnName .
                    '}" disabled="disabled" class="is-enabled-hidden" />';
                $html .= '<select name="' . $inputName . '[]" value="#{' . $columnName . '}" ' .
                    ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
                    ' class="' . (isset($column['class']) ? $column['class'] : 'select multiselect') . '"' .
                    (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . ' multiple=multiple>';

                if (is_array($column['options'])) {
                    foreach ($column['options'] as $key => $val) {
                        $html .= '<option value="' . $key . '">' . $val . '</option>';
                    }
                }
                $html .= "</select>";
                break;
            case "select":
                $html = '<input type="hidden" value="#{' . $columnName .
                    '}" disabled="disabled" class="is-enabled-hidden" />';
                $html .= '<select name="' . $inputName . '" value="#{' . $columnName . '}" ' .
                    ($column['size'] ? 'size="' . $column['size'] . '"' : '') .
                    ' class="' . (isset($column['class']) ? $column['class'] : 'select') . '"' .
                    (isset($column['style']) ? ' style="' . $column['style'] . '"' : '') . '>';

                if (is_array($column['options'])) {
                    foreach ($column['options'] as $key => $val) {
                        $html .= '<option value="' . $key . '">' . $val . '</option>';
                    }
                }
                $html .= "</select>";
                break;
        }
        return $html;
    }

}
