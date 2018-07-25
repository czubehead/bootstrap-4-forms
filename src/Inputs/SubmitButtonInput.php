<?php
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 */

namespace Czubehead\BootstrapForms\Inputs;


use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\Html;


/**
 * Class SubmitButtonInput. Form can be submitted with this.
 *
 * @package Czubehead\BootstrapForms\Inputs
 */
class SubmitButtonInput extends SubmitButton {

	/**
	 * SubmitButtonInput constructor.
	 *
	 * @param null|string|Html $content
	 * @param string           $buttonClasses
	 */
	public function __construct($content = null, $buttonClasses = 'btn btn-primary') {
		parent::__construct($content);
		$this->control->class[] = $buttonClasses;
	}
}