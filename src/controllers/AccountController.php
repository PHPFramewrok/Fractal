<?php namespace Regulus\Fractal\Controllers;

use \BaseController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

use Fractal;

use Aquanode\Formation\Facade as Form;
use Regulus\ActivityLog\Activity;
use Regulus\SolidSite\SolidSite as Site;
use Regulus\TetraText\TetraText as Format;

class AccountController extends BaseController {

	public function __construct()
	{
		$section = "Account";
		Site::setMulti(array('section', 'title'), $section);

		Fractal::setViewsLocation('account');
	}

	public function getIndex()
	{
		$user = Auth::user();
		Form::setDefaults($user);
		Form::setErrors();
		return View::make(Fractal::view('form'));
	}

	public function postIndex()
	{
		$user = Auth::user();
		Form::setDefaults($user);

		$rules = array(
			'username' => array('required', 'alpha_dash', 'min:3', 'unique:auth_users,username,'.$user->id),
			'email'    => array('required', 'email'),
		);

		if (Fractal::getSetting('Require Unique Email Addresses'))
			$rules['email'][] = 'unique:auth_users,email,'.$user->id;

		if (Input::get('password') != "") {
			$rules['password'] = array('required', 'confirmed');

			$minPasswordLength = Fractal::getSetting('Minimum Password Length');
			if ($minPasswordLength)
				$rules['password'][] = 'min:'.$minPasswordLength;
		}

		Form::setValidationRules($rules);

		$messages = array();
		if (Form::validated()) {
			$messages['success'] = Lang::get('fractal::messages.successUpdated', array('item' => Lang::get('fractal::labels.yourAccount')));

			$user->fill(Input::except('csrf_token', 'password', 'password_confirmation'));
			$user->first_name = Format::name($user->first_name);
			$user->last_name  = Format::name($user->last_name);

			if (Input::get('password') != "")
				$user->password = Hash::make(Input::get('password'));

			$user->save();
		} else {
			$messages['error'] = Lang::get('fractal::messages.errorGeneral');
		}

		return Redirect::to(Fractal::uri('account'))
			->with('messages', $messages)
			->with('errors', Form::getErrors())
			->withInput();
	}


}