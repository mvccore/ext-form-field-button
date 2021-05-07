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
 *                 with default text `Submit` and it's supporting JS code.
 *                 Input has it's custom result state configuration and
 *                 input has no validator.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		SubmitInput 
extends		\MvcCore\Ext\Forms\Field 
implements	\MvcCore\Ext\Forms\Fields\IVisibleField, 
			\MvcCore\Ext\Forms\Fields\ISubmit {

	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	use \MvcCore\Ext\Forms\Field\Props\Submit;
	use \MvcCore\Ext\Forms\Field\Props\FormAttrs;

	/**
	 * Possible values: `submit`.
	 * @var string
	 */
	protected $type = 'submit-input';
	
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
	 * @var \string[]|\stdClass
	 */
	protected static $templates = [
		'control'	=> '<input type="submit" id="{id}" name="{name}" value="{value}"{attrs} />',
	];

	/**
	 * Create new form `<input type="submit" />` control instance.
	 * 
	 * @param  array      $cfg
	 * Config array with public properties and it's
	 * values which you want to configure, presented
	 * in camel case properties names syntax.
	 * 
	 * @param  string     $name 
	 * Form field specific name, used to identify submitted value.
	 * This value is required for all form fields.
	 * @param  string     $type 
	 * Fixed field order number, null by default.
	 * @param  int        $fieldOrder
	 * Form field type, used in `<input type="...">` attribute value.
	 * Every typed field has it's own string value, but base field type 
	 * `\MvcCore\Ext\Forms\Field` has `NULL`.
	 * @param  string     $value 
	 * Form field value. It could be string or array, int or float, it depends
	 * on field implementation. Default value is `NULL`.
	 * @param  string     $title 
	 * Field title, global HTML attribute, optional.
	 * @param  string     $translate 
	 * Boolean flag about field visible texts and error messages translation.
	 * This flag is automatically assigned from `$field->form->GetTranslate();` 
	 * flag in `$field->Init();` method.
	 * @param  string     $translateTitle 
	 * Boolean to translate title text, `TRUE` by default.
	 * @param  array      $cssClasses 
	 * Form field HTML element css classes strings.
	 * Default value is an empty array to not render HTML `class` attribute.
	 * @param  array      $controlAttrs 
	 * Collection with field HTML element additional attributes by array keys/values.
	 * Do not use system attributes as: `id`, `name`, `value`, `readonly`, `disabled`, `class` ...
	 * Those attributes has it's own configurable properties by setter methods or by constructor config array.
	 * HTML field elements are meant: `<input>, <button>, <select>, <textarea> ...`. 
	 * Default value is an empty array to not render any additional attributes.
	 * 
	 * @param  string     $accessKey
	 * The access key global attribute provides a hint for generating
	 * a keyboard shortcut for the current element. The attribute 
	 * value must consist of a single printable character (which 
	 * includes accented and other characters that can be generated 
	 * by the keyboard).
	 * @param  bool       $autoFocus
	 * This Boolean attribute lets you specify that a form control should have input
	 * focus when the page loads. Only one form-associated element in a document can
	 * have this attribute specified. 
	 * @param  bool       $disabled
	 * Form field attribute `disabled`, determination if field value will be 
	 * possible to change by user and if user will be graphically informed about it 
	 * by default browser behaviour or not. Default value is `FALSE`. 
	 * This flag is also used for sure for submit checking. But if any field is 
	 * marked as disabled, browsers always don't send any value under this field name
	 * in submit. If field is configured as disabled, no value sent under field name 
	 * from user will be accepted in submit process and value for this field will 
	 * be used by server side form initialization. 
	 * Disabled attribute has more power than required. If disabled is true and
	 * required is true and if there is no or invalid submitted value, there is no 
	 * required error and it's used value from server side assigned by 
	 * `$form->SetValues();` or from session.
	 * @param  int|string $tabIndex
	 * An integer attribute indicating if the element can take input focus (is focusable), 
	 * if it should participate to sequential keyboard navigation, and if so, at what 
	 * position. You can set `auto` string value to get next form tab-index value automatically. 
	 * Tab-index for every field in form is better to index from value `1` or automatically and 
	 * moved to specific higher value by place, where is form currently rendered by form 
	 * instance method `$form->SetBaseTabIndex()` to move tab-index for each field into 
	 * final values. Tab-index can takes several values:
	 * - a negative value means that the element should be focusable, but should not be 
	 *   reachable via sequential keyboard navigation;
	 * - 0 means that the element should be focusable and reachable via sequential 
	 *   keyboard navigation, but its relative order is defined by the platform convention;
	 * - a positive value means that the element should be focusable and reachable via 
	 *   sequential keyboard navigation; the order in which the elements are focused is 
	 *   the increasing value of the tab-index. If several elements share the same tab-index, 
	 *   their relative order follows their relative positions in the document.
	 * 
	 * @param  int        $customResultState
	 * Submit button custom result state to move submit request to custom specific target url.
	 * There are already build in result states:
	 * - `0` (`\MvcCore\Ext\IForm::RESULT_ERRORS`)		- to move submit request to configured error url.
	 * - `1` (`\MvcCore\Ext\IForm::RESULT_SUCCESS`)		- to move submit request to configured success url.
	 * - `2` (`\MvcCore\Ext\IForm::RESULT_PREV_PAGE`)		- to move submit request to configured previous step url.
	 * - `4` (`\MvcCore\Ext\IForm::RESULT_NEXT_PAGE`)		- to move submit request to configured next step url.
	 * If you define any new custom result state for your submit button (`8`, `16` ...), 
	 * you have to implement `$form->SubmittedRedirect();` method by your own to redirect
	 * user submit request by this state to your own specific target url.
	 * 
	 * @param  string     $formAction
	 * The URL that processes the data submitted by the input element,
	 * if it is a submit button or image. This attribute overrides the
	 * `action` attribute of the element's form owner.
	 * @param  string     $formEnctype
	 * If the input element is a submit button or image, this attribute
	 * specifies how the form values will be encoded 
	 * to send them to the server. Possible values are:
	 * - `application/x-www-form-urlencoded`
	 *   By default, it means all form values will be encoded to 
	 *   `key1=value1&key2=value2...` string.
	 *   Constant: `\MvcCore\Ext\IForm::ENCTYPE_URLENCODED`.
	 * - `multipart/form-data`
	 *   Data will not be encoded to URL string form, this value is required,
	 *   when you are using forms that have a file upload control. 
	 *   Constant: `\MvcCore\Ext\IForm::ENCTYPE_MULTIPART`.
	 * - `text/plain`
	 *   Spaces will be converted to `+` symbols, but no other special 
	 *   characters will be encoded.
	 *   Constant: `\MvcCore\Ext\IForm::ENCTYPE_PLAINTEXT`.
	 * @param  string     $formMethod
	 * If the input element is a submit button or image, this attribute 
	 * specifies the HTTP method that the browser uses to submit the form.
	 * Use `GET` only if form data contains only ASCII characters.
	 * Possible values: `'POST' | 'GET'`
	 * You can use constants:
	 * - `\MvcCore\Ext\IForm::METHOD_POST`
	 * - `\MvcCore\Ext\IForm::METHOD_GET`
	 * @param  bool       $formNoValidate
	 * If the input element is a submit button or image, this Boolean attribute 
	 * specifies that the form shouldn't be validated before submission. This 
	 * attribute overrides the `novalidate` attribute of the element's form owner.
	 * It means there will be no validation on client side, but there is always 
	 * validation on server side.
	 * @param  string     $formTarget
	 * If the input element is a submit button or image, this attribute is 
	 * a name or keyword indicating where to display the response that is 
	 * received by submitting the form. This is a name of, or keyword for, 
	 * a browsing context (e.g. tab, window, or inline frame). This attribute
	 * overrides the target attribute of the elements's form owner. 
	 * The following keywords have special meanings:
	 * - `_self`:      Load the response into the same browsing context as the 
	 *                 current one. This value is the default if the attribute 
	 *                 is not specified.
	 * - `_blank`:     Load the response into a new unnamed browsing context.
	 * - `_parent`:    Load the response into the parent browsing context of 
	 *                 the current one. If there is no parent, this option 
	 *                 behaves the same way as `_self`.
	 * - `_top`:       Load the response into the top-level browsing context 
	 *                 (i.e. the browsing context that is an ancestor of the 
	 *                 current one, and has no parent). If there is no parent, 
	 *                 this option behaves the same way as `_self`.
	 * - `iframename`: The response is displayed in a named `<iframe>`.
	 * 
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\SubmitInput
	 */
	public function __construct (
		array $cfg = [], 

		$name = NULL, 
		$type = NULL, 
		$fieldOrder = NULL,
		$value = NULL, 
		$title = NULL, 
		$translate = NULL, 
		$translateTitle = NULL, 
		array $cssClasses = [], 
		array $controlAttrs = [], 

		$accessKey = NULL,
		$autoFocus = NULL,
		$disabled = NULL,
		$tabIndex = NULL,

		$customResultState = NULL,
		$formAction = NULL,
		$formEnctype = NULL,
		$formMethod = NULL,
		$formNoValidate = NULL,
		$formTarget = NULL
	) {
		$this->consolidateCfg($cfg, func_get_args(), func_num_args());
		parent::__construct($cfg);
		static::$templates = (object) self::$templates;
	}
	
	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` after field
	 * is added into form instance by `$form->AddField();` method. Do not 
	 * use this method even if you don't develop any form field.
	 *  - Check if field has any name, which is required.
	 *  - Set up form and field id attribute by form id and field name.
	 *  - Set up required.
	 *  - Set up translate boolean property.
	 *  - Check if exists button `value` string.
	 *  - Translate button value if necessary.
	 * @param  \MvcCore\Ext\Form $form
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\SubmitInput
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
		if ($this->formNoValidate) $this->formNoValidate = 'formnovalidate';
		$attrsStr = $this->renderControlAttrsWithFieldVars([
			'formAction', 'formEnctype', 'formMethod', 'formNoValidate', 'formTarget'
		]);
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStr .= (strlen($attrsStr) > 0 ? ' ' : '')
				. 'form="' . $this->form->GetId() . '"';
		$formViewClass = $this->form->GetViewClass();
		/** @var \stdClass $templates */
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
