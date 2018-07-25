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

	const DEFAULT_CLASS = 'btn-primary';

	/**
	 * SubmitButtonInput constructor.
	 *
	 * @param null|string|Html $content
	 * @param string           $buttonClass
	 */
	public function __construct($content = null, $buttonClass = self::DEFAULT_CLASS) {
		parent::__construct($content);
		$this->control->class[] = 'btn';
		$this->control->class[] = $buttonClass;
	}
}