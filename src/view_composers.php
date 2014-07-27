<?php namespace Regulus\Fractal;

/*
|--------------------------------------------------------------------------
| View Composers
|--------------------------------------------------------------------------
|
| The view composers for the CMS.
|
*/

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

use \Site as Site;
use \Form as Form;

use Regulus\Fractal\Models\ContentPage;

use Regulus\Identify\User;
use Regulus\Identify\Role;

View::composer(Config::get('fractal::viewsLocation').'partials.messages', function($view)
{
	$sessionMessages = Session::get('messages');
	$view->with('sessionMessages', $sessionMessages);
});

View::composer(Config::get('fractal::viewsLocation').'core.home', function($view)
{
	$view->with('hideTitle', true);
});

View::composer(Config::get('fractal::viewsLocation').'menus.form', function($view)
{
	$typeOptions = Form::simpleOptions(array('URI', 'Content Page'));
	$pageOptions = Form::prepOptions(ContentPage::select('id', 'title')->orderBy('title')->get(), array('id', 'title'));

	$view
		->with('typeOptions', $typeOptions)
		->with('pageOptions', $pageOptions);
});