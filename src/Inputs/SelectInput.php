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
use Nette\Forms\Controls\SelectBox;


/**
 * Class SelectInput.
 * Single select.
 * @package Czubehead\BootstrapForms
 */
class SelectInput extends SelectBox implements IValidationInput
{
	use ChoiceInputTrait;
	use InputPromptTrait;
	use StandardValidationTrait;

	/**
	 * SelectInput constructor.
	 * @param null       $label
	 * @param array|null $items
	 */
	public function __construct($label = NULL, $items = NULL)
	{
		parent::__construct($label);
		$this->setItems($items);
	}

	/**
	 * @inheritdoc
	 */
	public function getControl()
	{
		$select = parent::getControl()->setHtml(NULL);

		$select->attrs += [
			'class'    => ['custom-select'],
			'disabled' => $this->isControlDisabled(),
		];
		$options = $this->rawItems;
		if (!empty($this->prompt)) {
			$options = [NULL => $this->prompt] + $options;
		}

		$optList = $this->makeOptionList($options, function ($value) {
			return [
				'selected' => $this->isValueSelected($value),
				'disabled' => $this->isValueDisabled($value),
			];
		});
		foreach ($optList as $item) {
			$select->addHtml($item);
		}

		return $select;
	}

	/**
	 * @return bool
	 */
	public function isOk()
	{
		return $this->isDisabled()
			|| $this->prompt !== null
			|| $this->getValue() !== null
			|| !$this->getOptions()
			|| $this->control->size > 1;
	}
}
