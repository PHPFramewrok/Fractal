<?php namespace Regulus\Fractal\Controllers;

use \BaseController;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use Fractal;

use Regulus\Fractal\Models\ContentPage;
use Regulus\Fractal\Models\ContentArea;
use Regulus\Fractal\Models\ContentLayoutTemplate;

use Regulus\ActivityLog\Activity;
use \Auth as Auth;
use \Site as Site;
use \Form as Form;
use \Format as Format;

class PagesController extends BaseController {

	public function __construct()
	{
		Site::set('section', 'Content');
		$subSection = "Pages";
		Site::setMulti(array('subSection', 'title'), $subSection);

		//set content type and views location
		Fractal::setContentType('page', true);
	}

	public function index()
	{
		$data  = Fractal::setupPagination();
		$pages = ContentPage::getSearchResults($data);

		Fractal::setContentForPagination($pages);

		$data     = Fractal::setPaginationMessage(true);
		$messages = Fractal::getPaginationMessageArray();

		if (!count($pages))
			$pages = ContentPage::orderBy($data['sortField'], $data['sortOrder'])->paginate($data['itemsPerPage']);

		return View::make(Fractal::view('list'))
			->with('content', $pages)
			->with('messages', $messages);
	}

	public function search()
	{
		$data  = Fractal::setupPagination();
		$pages = ContentPage::getSearchResults($data);

		Fractal::setContentForPagination($pages);

		$data = Fractal::setPaginationMessage();

		if (!count($pages))
			$data['content'] = ContentPage::orderBy($data['sortField'], $data['sortOrder'])->paginate($data['itemsPerPage']);

		$data['result']['pages']     = Fractal::getLastPage();
		$data['result']['tableBody'] = Fractal::createTable($data['content'], true);

		return $data['result'];
	}

	public function create()
	{
		Site::set('title', 'Create Page');
		Site::set('wysiwyg', true);

		ContentPage::setDefaultsForNew();
		Form::setErrors();

		$layoutTagOptions = $this->getLayoutTagOptions();

		return View::make(Fractal::view('form'))
			->with('layoutTagOptions', $layoutTagOptions);
	}

	public function store()
	{
		Form::setValidationRules(ContentPage::validationRules());

		$messages = array();
		if (Form::validated()) {
			$messages['success'] = Lang::get('fractal::messages.successCreated', array('item' => Format::a('page')));

			$input = Input::all();
			$input['user_id'] = Auth::user()->id;

			$page = ContentPage::createNew($input);

			//re-export menus to config array in case published status for page has changed
			Fractal::exportMenus();

			Activity::log(array(
				'contentId'   => $page->id,
				'contentType' => 'ContentPage',
				'action'      => 'Create',
				'description' => 'Created a Page',
				'details'     => 'Title: '.$page->title,
			));

			return Redirect::to(Fractal::uri('pages/'.$page->slug.'/edit'))
				->with('messages', $messages);
		} else {
			$messages['error'] = Lang::get('fractal::messages.errorGeneral');
		}

		return Redirect::to(Fractal::uri('pages/create'))
			->with('messages', $messages)
			->with('errors', Form::getErrors())
			->withInput();
	}

	public function edit($slug)
	{
		$page = ContentPage::findBySlug($slug);
		if (empty($page))
			return Redirect::to(Fractal::uri('pages'))
				->with('messages', array('error' => Lang::get('fractal::messages.errorNotFound', array('item' => 'page'))));

		Site::set('title', $page->title.' (Page)');
		Site::set('titleHeading', 'Update Page: <strong>'.Format::entities($page->title).'</strong>');
		Site::set('wysiwyg', true);

		$page->setDefaults(array('contentAreas'));
		Form::setErrors();

		$layoutTagOptions = $this->getLayoutTagOptions($page->getLayoutTags());

		return View::make(Fractal::view('form'))
			->with('update', true)
			->with('id', $page->id)
			->with('pageUrl', $page->getUrl())
			->with('layoutTagOptions', $layoutTagOptions);
	}

	public function update($slug)
	{
		$page = ContentPage::findBySlug($slug);
		if (empty($page))
			return Redirect::to(Fractal::uri('pages'))
				->with('messages', array('error' => Lang::get('fractal::messages.errorNotFound', array('item' => 'page'))));

		$page->setValidationRules();

		$messages = array();
		if (Form::validated()) {
			$messages['success'] = Lang::get('fractal::messages.successUpdated', array('item' => Format::a('page')));

			$page->saveData();

			//re-export menus to config array in case published status for page has changed
			Fractal::exportMenus();

			Activity::log(array(
				'contentId'   => $page->id,
				'contentType' => 'ContentPage',
				'action'      => 'Update',
				'description' => 'Updated a Page',
				'details'     => 'Title: '.$page->title,
				'updated'     => true,
			));

			return Redirect::to(Fractal::uri('pages/'.$slug.'/edit'))
				->with('messages', $messages);
		} else {
			$messages['error'] = Lang::get('fractal::messages.errorGeneral');

			return Redirect::to(Fractal::uri('pages/'.$slug.'/edit'))
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

		$page = ContentPage::find($id);
		if (empty($page))
			return $result;

		Activity::log(array(
			'contentId'   => $page->id,
			'contentType' => 'ContentPage',
			'action'      => 'Delete',
			'description' => 'Deleted a Page',
			'details'     => 'Title: '.$page->title,
		));

		$result['resultType'] = "Success";
		$result['message']    = Lang::get('fractal::messages.successDeleted', array('item' => '<strong>'.$page->title.'</strong>'));

		$page->contentAreas()->sync(array());
		$page->delete();

		return $result;
	}

	public function view($slug = 'home')
	{
		$page = ContentPage::where('slug', $slug);

		if (Auth::isNot('admin'))
			$page->onlyPublished();

		$page = $page->first();

		if (empty($page))
			return Redirect::to('');

		Site::setMulti(array('section', 'subSection', 'title'), $page->title);
		Site::set('menus', 'Front');

		$page->logView();

		$messages = array();
		if (!$page->isPublished()) {
			if ($page->isPublishedFuture())
				$messages['info'] = Lang::get('fractal::messages.notPublishedUntil', array(
					'item'     => strtolower(Lang::get('fractal::labels.page')),
					'dateTime' => $page->getPublishedDateTime(),
				));
			else
				$messages['info'] = Lang::get('fractal::messages.notPublished', array('item' => Lang::get('fractal::labels.page')));
		}

		return View::make(Config::get('fractal::pageView'))
			->with('page', $page)
			->with('messages', $messages);
	}

	public function layoutTags()
	{
		$input = Input::all();
		if (!isset($input['layout_template_id']) || !isset($input['layout']))
			return "";

		$layout = $input['layout'];
		if ($input['layout_template_id'] != "") {
			$template = ContentLayoutTemplate::find($input['layout_template_id']);
			if (!empty($template))
				$layout = $template->layout;
		}

		return json_encode(Fractal::getLayoutTagsFromLayout($layout));
	}

	private function getLayoutTagOptions($layoutTagOptions = array())
	{
		if (Input::old()) {
			$layout = Input::old('layout');
			if (Input::old('layout_template_id') != "") {
				$template = ContentLayoutTemplate::find(Input::old('layout_template_id'));
				if (!empty($template))
					$layout = $template->layout;
			}

			$layoutTagOptions = Fractal::getLayoutTagsFromLayout($layout);
		}

		return $layoutTagOptions;
	}

	public function renderMarkdownContent()
	{
		return Fractal::renderMarkdownContent(Input::get('content'));
	}

	public function addContentArea($id = false)
	{
		$data = array(
			'title'        => Lang::get('fractal::labels.addContentArea'),
			'pageId'       => $id,
			'contentAreas' => ContentArea::orderBy('title')->get(),
		);

		return Fractal::modalView('add_content_area', $data);
	}

	public function getContentArea($id = false)
	{
		return ContentArea::find($id)->toJson();
	}

	public function deleteContentArea($id)
	{
		$contentArea = ContentArea::find($id);
		if ($contentArea) {
			if (!$contentArea->contentPages()->count()) {
				$contentArea->delete();
				return "Success";
			}
		}

		return "Error";
	}

}