<?php namespace Regulus\Fractal\Controllers;

use \BaseController;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use Fractal;

use Regulus\Fractal\Models\Menu;

use Regulus\ActivityLog\Activity;
use \Auth as Auth;
use \Site as Site;
use \Form as Form;
use \Format as Format;

class MenusController extends BaseController {

	public function __construct()
	{
		Site::set('section', 'Content');
		$subSection = "Menus";
		Site::setMulti(array('subSection', 'title'), $subSection);

		//set content type and views location
		Fractal::setContentType('menu', true);

		Site::set('defaultSorting', array('field' => 'cms'));
	}

	public function index()
	{
		$data  = Fractal::setupPagination();
		$menus = Menu::getSearchResults($data);

		Fractal::setContentForPagination($menus);

		$data     = Fractal::setPaginationMessage(true);
		$messages = Fractal::getPaginationMessageArray();

		if (!count($menus))
			$menus = Menu::orderBy($data['sortField'], $data['sortOrder'])->paginate($data['itemsPerPage']);

		return View::make(Fractal::view('list'))
			->with('content', $menus)
			->with('messages', $messages);
	}

	public function search()
	{
		$data  = Fractal::setupPagination();
		$menus = Menu::getSearchResults($data);

		Fractal::setContentForPagination($menus);

		$data = Fractal::setPaginationMessage();

		if (!count($menus))
			$data['content'] = Menu::orderBy($data['sortField'], $data['sortOrder'])->paginate($data['itemsPerPage']);

		$data['result']['pages']     = Fractal::getLastPage();
		$data['result']['tableBody'] = Fractal::createTable($data['content'], true);

		return $data['result'];
	}

	public function create()
	{
		Site::set('title', 'Create Menu');

		Form::setErrors();

		return View::make(Fractal::view('form'));
	}

	public function store()
	{
		Form::setValidationRules(Menu::validationRules());

		$messages = array();
		if (Form::validated()) {
			$messages['success'] = Lang::get('fractal::messages.successCreated', array('item' => Format::a('menu')));

			$menu = Menu::createNew();

			//re-export menus to config array
			Fractal::exportMenus();

			Activity::log(array(
				'contentId'   => $menu->id,
				'contentType' => 'Menu',
				'action'      => 'Create',
				'description' => 'Created a Menu',
				'details'     => 'Name: '.$menu->name,
			));

			return Redirect::to(Fractal::uri('menus'))
				->with('messages', $messages);
		} else {
			$messages['error'] = Lang::get('fractal::messages.errorGeneral');
		}

		return Redirect::to(Fractal::uri('menus/create'))
			->with('messages', $messages)
			->with('errors', Form::getErrors())
			->withInput();
	}

	public function edit($id)
	{
		$menu = Menu::find($id);
		if (empty($menu))
			return Redirect::to(Fractal::uri('menus'))
				->with('messages', array('error' => Lang::get('fractal::messages.errorNotFound', array('item' => 'menu'))));

		Site::set('title', $menu->name.' (Menu)');
		Site::set('titleHeading', 'Update Menu: <strong>'.Format::entities($menu->name).'</strong>');

		$menu->setDefaults(array('items'));

		return View::make(Fractal::view('form'))
			->with('update', true)
			->with('menu', $menu);
	}

	public function update($id)
	{
		$menu = Menu::find($id);
		if (empty($menu))
			return Redirect::to(Fractal::uri('menus'))
				->with('messages', array('error' => Lang::get('fractal::messages.errorNotFound', array('item' => 'menu'))));

		Form::setValidationRules(Menu::validationRules($id));

		$messages = array();
		if (Form::validated()) {
			$messages['success'] = Lang::get('fractal::messages.successUpdated', array('item' => Format::a('menu')));

			$menu->saveData();

			//re-export menus to config array
			Fractal::exportMenus();

			Activity::log(array(
				'contentId'   => $menu->id,
				'contentType' => 'Menu',
				'action'      => 'Update',
				'description' => 'Updated a Menu',
				'details'     => 'Name: '.$menu->name,
				'updated'     => true,
			));

			return Redirect::to(Fractal::uri('menus'))
				->with('messages', $messages);
		} else {
			$messages['error'] = Lang::get('fractal::messages.errorGeneral');

			return Redirect::to(Fractal::uri('menus/'.$id.'/edit'))
				->with('messages', $messages)
				->with('errors', Form::getErrors())
				->withInput();
		}
	}

	public function destroy($id)
	{
		$result = array(
			'resultType' => 'Error',
			'message'    => Lang::get('fractal::messages.errorGeneral'),
		);

		$menu = Menu::find($id);
		if (empty($menu))
			return $result;

		Activity::log(array(
			'contentId'   => $menu->id,
			'contentType' => 'Menu',
			'action'      => 'Delete',
			'description' => 'Deleted a Menu',
			'details'     => 'Name: '.$menu->name,
		));

		$result['resultType'] = "Success";
		$result['message']    = Lang::get('fractal::messages.successDeleted', array('item' => '<strong>'.$menu->name.'</strong>'));

		$menu->items()->delete();
		$menu->delete();

		return $result;
	}

}