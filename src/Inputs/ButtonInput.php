<?php
/**
 * Created by Petr Čech (czubehead) : https://petrcech.eu
 * Date: 9.7.17
 * Time: 20:02
 * This file belongs to the project bootstrap-4-forms
 * https://github.com/czubehead/bootstrap-4-forms
 */

namespace Czubehead\BootstrapForms\Inputs;


use Nette\Forms\Controls\Button;
use Nette\Utils\Html;
use Tracy\Debugger;


/**
 * Class ButtonInput.
 * Returns &lt;button&gt; whose content can be set as caption. This is not a submit button.
 *
 * @package Czubehead\BootstrapForms
 * @property string $btnClass
 */
class ButtonInput extends Button {
	/**
	 * ButtonInput constructor.
	 *
	 * @param null|string|Html $content
	 * @param string           $buttonClasses
	 */
	public function __construct($content = null, string $buttonClasses = 'btn btn-primary') {
		parent::__construct($content);
		$this->control->class[] = $buttonClasses;
	}
}