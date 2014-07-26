<?php namespace Regulus\Fractal\Controllers;

use \BaseController;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use Fractal;

use \Site as Site;
use \Form as Form;
use Regulus\TetraText\TetraText as Format;

class CoreController extends BaseController {

	public function __construct()
	{
		$section = "Home";
		Site::setMulti(array('section', 'title'), $section);

		Fractal::setViewsLocation('core');
	}

	public function getIndex()
	{
		return View::make(Fractal::view('home'));
	}

	public function getDeveloper($off = false)
	{
		if ($off == "off") {
			Session::forget('developer');
			return Redirect::to(Fractal::url())->with('messages', array('info' => Lang::get('fractal::messages.developerModeDisabled')));
		} else {
			Site::setDeveloper();
			return Redirect::to(Fractal::url())->with('messages', array('info' => Lang::get('fractal::messages.developerModeEnabled')));
		}
	}

}