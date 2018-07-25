<?php
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 */

namespace Czubehead\BootstrapForms\Inputs;


use Czubehead\BootstrapForms\Traits\StandardValidationTrait;
use Nette\Forms\Controls\Checkbox;
use Nette\Utils\Html;


/**
 * Class CheckboxInput. Single checkbox.
 *
 * @package Czubehead\BootstrapForms\Inputs
 */
class CheckboxInput extends Checkbox implements IValidationInput
{
	use StandardValidationTrait {
		// we only want to use it on a specific child
		showValidation as protected _rawShowValidation;
	}

	const
		DEFAULT_CONTROL_CLASS = 'custom-control-input',
		DEFAULT_LABEL_CLASS = 'custom-control-label',
		DEFAULT_CONTAINER_CLASS = 'custom-control custom-checkbox';

	/**
	 * CheckboxInput constructor.
	 *
	 * @param string|object $label
	 * @param string        $controlClass
	 * @param string        $labelClass
	 * @param string        $containerCLass
	 */
	public function __construct(
		$label = null,
		$controlClass = self::DEFAULT_CONTROL_CLASS,
		$labelClass = self::DEFAULT_LABEL_CLASS,
		$containerCLass = self::DEFAULT_CONTAINER_CLASS
	) {
		parent::__construct($label);
		$this->control->class[] = $controlClass;
		$this->label->class[]   = $labelClass;

		$this->getSeparatorPrototype()
		     ->setName('div')->class[] = $containerCLass;
	}

	/**
	 * Generates a checkbox
	 *
	 * @return Html
	 */
	public function getControl() {
		return (clone $this->getSeparatorPrototype())
		            ->addHtml($this->getControlPart())
		            ->addHtml($this->getLabelPart());
	}

	/**
	 * Modify control in such a way that it explicitly shows its validation state.
	 * Returns the modified element.
	 *
	 * @param Html $control
	 * TODO fix?
	 *
	 * @return Html
	 */
	public function showValidation(Html $control) {
		// add validation classes to the first child, which is <input>
		$control->getChildren()[0] = $this->_rawShowValidation($control->getChildren()[0]);

		return $control;
	}
}