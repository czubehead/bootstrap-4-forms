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
use Czubehead\BootstrapForms\Traits\StandardValidationTrait;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\CheckboxList;
use Nette\Utils\Html;


/**
 * Class CheckboxListInput.
 * Multiple checkboxes in a list.
 *
 * @package Czubehead\BootstrapForms\Inputs
 */
class CheckboxListInput extends CheckboxList implements IValidationInput
{

    use ChoiceInputTrait;
    use StandardValidationTrait {
        showValidation as protected _rawShowValidation;
    }

    public function __construct($label = null, array $items = null)
    {
        parent::__construct(
            $label,
            $items
        );

        $this->getContainerPrototype()
             ->setName('fieldset');
        $this->getSeparatorPrototype()
             ->setName('div')->class[] = CheckboxInput::DEFAULT_CONTAINER_CLASS;

        $this->getControlPrototype()->class[] = CheckboxInput::DEFAULT_CONTROL_CLASS;

        $this->getLabelPrototype()->class[] = CheckboxInput::DEFAULT_LABEL_CLASS;
    }


    /**
     * @inheritdoc
     */
    public function getControl()
    {
        $input_prototype = BaseControl::getControl();

        $fieldset = (clone $this->getContainerPrototype())
            ->setAttribute(
                'disabled',
                $this->isControlDisabled()
            );
        $fieldset->removeChildren();


        $baseId = $this->getHtmlId();
        $c = 0;
        foreach ($this->items as $value => $caption) {
            $line = clone $this->getSeparatorPrototype();

            $htmlId = $baseId . $c;
            $input = (clone $input_prototype)
                ->addAttributes(
                    [
                        'data-nette-rules:' => [$value => $input_prototype->attrs['data-nette-rules']],
                        'name'     => $this->getHtmlName(),
                        'disabled' => $this->isValueDisabled($value),
                        'required' => false,
                        'checked'  => $this->isValueSelected($value),
                        'id'       => $htmlId,
                    ]
                );
            if ($value !== false) {
                $input->setAttribute(
                    'value',
                    $value
                );
            }

            $label = (clone $this->getLabelPrototype())
                ->setAttribute(
                    'for',
                    $htmlId
                )
                ->setText($this->translate($caption));

            $line->addHtml($input);
            $line->addHtml($label);

            $fieldset->addHtml($line);
            $c++;
        }

        return $fieldset;
    }

    /**
     * Modify control in such a way that it explicitly shows its validation state.
     * Returns the modified element.
     *
     * @param Html $control
     *
     * @return Html
     */
    public function showValidation(Html $control)
    {
        // same parent, but no children
        $fieldset = Html::el(
            $control->getName(),
            $control->attrs
        );
        /** @var Html $label */
        foreach ($control->getChildren() as $label) {
            $input = $label->getChildren()[0];
            $label->getChildren()[0] = $this->_rawShowValidation($input);
            $fieldset->addHtml($label);
        }

        return $fieldset;
    }
}