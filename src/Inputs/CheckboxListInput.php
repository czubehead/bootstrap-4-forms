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
use Nette\Forms\Controls\CheckboxList;
use Nette\Utils\Html;
use Tracy\Debugger;


/**
 * Class CheckboxListInput.
 * Multiple checkboxes in a list.
 * @package Czubehead\BootstrapForms\Inputs
 */
class CheckboxListInput extends CheckboxList implements IValidationInput
{
	use ChoiceInputTrait;
	use StandardValidationTrait {
		showValidation as protected _rawShowValidation;
	}

	/**
	 * @inheritdoc
	 * TODO make adjustable
	 */
	public function getControl()
	{
	$og = 	parent::getControl();
	Debugger::barDump($og);
		$fieldset = Html::el('fieldset', [
			'disabled' => $this->isControlDisabled(),
		]);

		$baseId = $this->getHtmlId();
		$c = 0;
		foreach ($this->items as $value => $caption) {
			$line = Html::el('div',[
				'class' => CheckboxInput::DEFAULT_CONTAINER_CLASS
			]);

			$htmlId = $baseId . $c;
			$input = Html::el('input',[
				'type'  => 'checkbox',
				'class' => CheckboxInput::DEFAULT_CONTROL_CLASS,
				'name'     => $this->getHtmlName(),
				'disabled' => $this->isValueDisabled($value),
				'required' => FALSE,
				'checked'  => $this->isValueSelected($value),
				'id'       => $htmlId,
			]);
			if ($value !== FALSE) {
				$input->attrs += [
					'value' => $value,
				];
			}

			$label = Html::el('label',[
				'class' => CheckboxInput::DEFAULT_LABEL_CLASS,
				'for'   => $htmlId
			])->setText($caption);


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
	 * @param Html $control
	 * TODO fix?
	 * @return Html
	 */
	public function showValidation(Html $control)
	{
		// same parent, but no children
		$fieldset = Html::el($control->getName(), $control->attrs);
		/** @var Html $label */
		foreach ($control->getChildren() as $label) {
			$input = $label->getChildren()[0];
			$label->getChildren()[0] = $this->_rawShowValidation($input);
			$fieldset->addHtml($label);
		}

		return $fieldset;
	}
}