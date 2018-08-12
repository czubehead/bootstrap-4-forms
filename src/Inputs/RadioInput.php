<?php
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 */

namespace Czubehead\BootstrapForms\Inputs;


use Czubehead\BootstrapForms\Enums\RendererOptions;
use Czubehead\BootstrapForms\Traits\ChoiceInputTrait;
use Czubehead\BootstrapForms\Traits\StandardValidationTrait;
use Nette\Forms\Controls\ChoiceControl;
use Nette\Forms\Controls\RadioList;
use Nette\Utils\Html;


/**
 * Class RadioInput. Lets user choose one out of multiple options.
 *
 * @package Czubehead\BootstrapForms
 */
class RadioInput extends RadioList implements IValidationInput
{

    use ChoiceInputTrait;
    use StandardValidationTrait {
        showValidation as protected _rawShowValidation;
    }

    /**
     * @var Html
     */
    //private $container;

    /**
     * @param  string|object
     * @param array|null $items
     */
    public function __construct($label = null, array $items = null)
    {
        parent::__construct(
            $label,
            $items
        );
        $this->control->type = 'radio';
        $this->container = Html::el('fieldset');
        $this->setOption(
            RendererOptions::type,
            'radio'
        );

        $this->getControlPrototype()
            ->class[] = 'custom-control-input';

        $this->getItemLabelPrototype()
            ->class[] = 'custom-control-label';

        $this->getSeparatorPrototype()
             ->setName('div')
            ->class[] = 'custom-control custom-radio';
    }

    /**
     * Generates control's HTML element.
     *
     * @return Html
     */
    public function getControl()
    {
        // has to run
        ChoiceControl::getControl();

        $items = $this->getItems();
        $container = clone $this->getContainerPrototype();

        $c = 0;
        $htmlId = $this->getHtmlId();
        foreach ($items as $value => $caption) {

            $itemHtmlId = $htmlId . $c;

            $wrapper = clone $this->getSeparatorPrototype();

            $wrapper->addHtml(
                (clone $this->getControlPrototype())
                    ->addAttributes(
                        [
                            'type'     => 'radio',
                            'value'    => $value,
                            'name'     => $this->getHtmlName(),
                            'checked'  => $this->isValueSelected($value),
                            'disabled' => $this->isValueDisabled($value),
                            'id'       => $itemHtmlId,
                        ]
                    )
            );

            $wrapper->addHtml(
                (clone $this->getItemLabelPrototype())
                    ->setAttribute(
                        'for',
                        $itemHtmlId
                    )
                    ->setText($this->translate($caption))
            );

            $container->addHtml($wrapper);
            $c++;
        }

        return $container;
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
        $fieldset = Html::el(
            $control->getName(),
            $control->attrs
        );
        /** @var Html $rowDiv */
        foreach ($control->getChildren() as $rowDiv) {
            $input = $rowDiv->getChildren()[0];
            $rowDiv->getChildren()[0] = $this->_rawShowValidation($input);
            $fieldset->addHtml($rowDiv);
        }

        return $control;
    }
}