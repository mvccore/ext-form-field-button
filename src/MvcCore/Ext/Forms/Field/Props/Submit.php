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

namespace MvcCore\Ext\Forms\Field\Props;

/**
 * Trait for classes:
 * - `\MvcCore\Ext\Forms\Fields\Image`
 * - `\MvcCore\Ext\Forms\Fields\SubmitButton`
 * - `\MvcCore\Ext\Forms\Fields\SubmitInput`
 */
trait Submit {

	/**
	 * Submit button custom result state to move submit request to custom specific target url.
	 * There are already build in result states:
	 * - `0` (`\MvcCore\Ext\IForm::RESULT_ERRORS`)		- to move submit request to configured error url.
	 * - `1` (`\MvcCore\Ext\IForm::RESULT_SUCCESS`)		- to move submit request to configured success url.
	 * - `2` (`\MvcCore\Ext\IForm::RESULT_PREV_PAGE`)		- to move submit request to configured previous step url.
	 * - `4` (`\MvcCore\Ext\IForm::RESULT_NEXT_PAGE`)		- to move submit request to configured next step url.
	 * If you define any new custom result state for your submit button (`8`, `16` ...), 
	 * you have to implement `$form->SubmittedRedirect();` method by your own to redirect
	 * user submit request by this state to your own specific target url.
	 * @var int|NULL
	 */
	protected $customResultState = NULL;

	/**
	 * Get submit button custom result state to move submit request to custom specific target url.
	 * There are already build in result states:
	 * - `0` - `\MvcCore\Ext\IForm::RESULT_ERRORS`    - to move submit request to configured error url.
	 * - `1` - `\MvcCore\Ext\IForm::RESULT_SUCCESS`   - to move submit request to configured success url.
	 * - `2` - `\MvcCore\Ext\IForm::RESULT_PREV_PAGE` - to move submit request to configured previous step url.
	 * - `4` - `\MvcCore\Ext\IForm::RESULT_NEXT_PAGE` - to move submit request to configured next step url.
	 * If you define any new custom result state for your submit button (`8`, `16` ...), 
	 * you have to implement `$form->SubmittedRedirect();` method by your own to redirect
	 * user submit request by this state to your own specific target url.
	 * @return int|NULL
	 */
	public function GetCustomResultState () {
		return $this->customResultState;
	}

	/**
	 * Set submit button custom result state to move submit request to custom specific target url.
	 * There are already build in result states:
	 * - `0` - `\MvcCore\Ext\IForm::RESULT_ERRORS`    - to move submit request to configured error url.
	 * - `1` - `\MvcCore\Ext\IForm::RESULT_SUCCESS`   - to move submit request to configured success url.
	 * - `2` - `\MvcCore\Ext\IForm::RESULT_PREV_PAGE` - to move submit request to configured previous step url.
	 * - `4` - `\MvcCore\Ext\IForm::RESULT_NEXT_PAGE` - to move submit request to configured next step url.
	 * If you define any new custom result state for your submit button (`8`, `16` ...), 
	 * you have to implement `$form->SubmittedRedirect();` method by your own to redirect
	 * user submit request by this state to your own specific target url.
	 * @param int|NULL $customResultState 
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetCustomResultState ($customResultState = \MvcCore\Ext\IForm::RESULT_NEXT_PAGE) {
		/** @var $this \MvcCore\Ext\Forms\Field */
		$this->customResultState = $customResultState;
		return $this;
	}
}
