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
 * Responsibility: init, pre-dispatch and render `<input type="submit">`
 *				   with default text `Submit` and it's supporting JS code.
 *				   Button has it's custom result state configuration and
 *				   button has no validator.
 */
class		SubmitButton 
extends		\MvcCore\Ext\Forms\Fields\Button 
implements	\MvcCore\Ext\Forms\Fields\ISubmit {

	use \MvcCore\Ext\Forms\Field\Props\Submit;
	use \MvcCore\Ext\Forms\Field\Props\FormAttrs;

	/**
	 * Possible values: `submit`.
	 * @var string
	 */
	protected $type = 'submit-button';
	
	/**
	 * Default visible button text - `Submit`.
	 * This button text is automatically checked, if there is at least any 
	 * visible text and automatically translated, if any translator `callable` 
	 * defined in form instance.
	 * @var string
	 */
	protected $value = 'Submit';
	
	/**
	 * Standard field control natural template string.
	 * @var string
	 */
	public static $templates = [
		'control'	=> '<button id="{id}" name="{name}" type="submit"{attrs}>{value}</button>',
	];

	/**
	 * Create new form `<button type="submit" />` control instance.
	 * @param array $cfg Config array with public properties and it's 
	 *					 values which you want to configure, presented 
	 *					 in camel case properties names syntax.
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\ButtonInput
	 */
	public function __construct (array $cfg = []) {
		parent::__construct($cfg);
		static::$templates = (object) self::$templates;
	}
	
	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Forms\Field\Rendering` 
	 * in rendering process. Do not use this method even if you don't develop any form field.
	 * 
	 * Render control tag only without label or specific errors.
	 * @return string
	 */
	public function RenderControl () {
		if ($this->customResultState !== NULL) 
			$this->SetControlAttr('data-result', $this->customResultState);
		$attrsStr = $this->renderControlAttrsWithFieldVars([
			'formAction', 'formEnctype', 'formMethod', 'formNoValidate', 'formTarget'
		]);
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStr .= (strlen($attrsStr) > 0 ? ' ' : '')
				. 'form="' . $this->form->GetId() . '"';
		$formViewClass = $this->form->GetViewClass();
		/** @var $templates \stdClass */
		$templates = static::$templates;
		return $formViewClass::Format($templates->control, [
			'id'		=> $this->id,
			'name'		=> $this->name,
			'type'		=> $this->type,
			'value'		=> htmlspecialchars_decode(htmlspecialchars($this->value, ENT_QUOTES), ENT_QUOTES),
			'attrs'		=> strlen($attrsStr) > 0 ? ' ' . $attrsStr : '',
		]);
	}
}
