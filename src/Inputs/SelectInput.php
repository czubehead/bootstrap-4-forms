<?php
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 */

namespace Czubehead\BootstrapForms\Inputs;


use Czubehead\BootstrapForms\Traits\ChoiceInputTrait;
use Czubehead\BootstrapForms\Traits\InputPromptTrait;
use Czubehead\BootstrapForms\Traits\StandardValidationTrait;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\SelectBox;


/**
 * Class SelectInput.
 * Single select.
 *
 * @package Czubehead\BootstrapForms
 */
class SelectInput extends SelectBox implements IValidationInput
{

    use ChoiceInputTrait;
    use InputPromptTrait;
    use StandardValidationTrait;

    /**
     * SelectInput constructor.
     *
     * @param null       $label
     * @param array|null $items
     */
    public function __construct($label = null, $items = null)
    {
        parent::__construct($label);
        $this->setItems($items);

        $this->getControlPrototype()
             ->setName('select')
            ->class[] = 'custom-select';
    }

    /**
     * @inheritdoc
     */
    public function getControl()
    {
        $select = BaseControl::getControl();

        $select->setAttribute(
            'disabled',
            $this->isControlDisabled()
        );

        $options = $this->rawItems;
        if (!empty($this->prompt)) {
            $options = [null => $this->prompt] + $options;
        }

        $optList = $this->makeOptionList(
            $options,
            function ($value) {
                return /* TODO merge with optionAttrs */[
                    'selected' => $this->isValueSelected($value),
                    'disabled' => $this->isValueDisabled($value),
                ];
            }
        );
        foreach ($optList as $item) {
            $select->addHtml($item);
        }

        return $select;
    }
}