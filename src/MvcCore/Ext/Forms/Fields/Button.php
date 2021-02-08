<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Forms\Fields;

/**
 * Responsibility: init, pre-dispatch and render button based 
 *				   on `<button>` HTML element with types `button` 
 *				   and types `submit` and `reset` in extended classes.
 *				   Button has text `OK` by default and no validators.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Button 
extends		\MvcCore\Ext\Forms\Field
implements	\MvcCore\Ext\Forms\Fields\IVisibleField {
	
	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	
	/**
	 * MvcCore Extension - Form - Field - Button - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.1';

	/**
	 * Possible values: `button` and in extended classes `reset` and `submit`.
	 * @var string
	 */
	protected $type = 'button';

	/**
	 * Default visible button text - `OK`. In extended classes - `Reset` and `Submit`.
	 * This button text is automatically checked, if there is at least any 
	 * visible text and automatically translated, if any translator `callable` 
	 * defined in form instance.
	 * @var string
	 */
	protected $value = 'OK';

	/**
	 * Standard field control natural template string.
	 * @var string
	 */
	public static $templates = [
		'control'	=> '<button id="{id}" name="{name}" type="{type}"{attrs}>{value}</button>',
	];

	/**
	 * Create new form `<button>` instance.
	 * @param array $cfg Config array with public properties and it's 
	 *					 values which you want to configure, presented 
	 *					 in camel case properties names syntax.
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\Button
	 */
	public function __construct(array $cfg = []) {
		parent::__construct($cfg);
		static::$templates = (object) array_merge(
			(array) parent::$templates, 
			(array) self::$templates
		);
	}
	
	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` after field
	 * is added into form instance by `$form->AddField();` method. Do not 
	 * use this method even if you don't develop any form field.
	 * - Check if field has any name, which is required.
	 * - Set up form and field id attribute by form id and field name.
	 * - Set up required.
	 * - Set up translate boolean property.
	 * - Check if exists button `value` string.
	 * @param \MvcCore\Ext\Form $form
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\Button
	 */
	public function SetForm (\MvcCore\Ext\IForm $form) {
		parent::SetForm($form);
		if (!$this->value) $this->throwNewInvalidArgumentException(
			'No button `value` defined.'
		);
		return $this;
	}
	
	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` just before
	 * field is naturally rendered. It sets up field for rendering process.
	 * Do not use this method even if you don't develop any form field.
	 * - Set up field render mode if not defined.
	 * - Translate label text if necessary.
	 * - Translate value text if necessary.
	 * - Set up tab-index if necessary.
	 * @return void
	 */
	public function PreDispatch () {
		parent::PreDispatch();
		if ($this->translate && $this->value)
			$this->value = $this->form->Translate($this->value);
		$this->preDispatchTabIndex();
	}
}
