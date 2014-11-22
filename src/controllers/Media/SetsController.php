<?php namespace Regulus\Fractal\Controllers\Media;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use Fractal;

use Regulus\Fractal\Models\Media\Set;
use Regulus\Fractal\Models\Media\Item;

use Regulus\ActivityLog\Activity;
use \Auth;
use \Form;
use \Format;
use \Site;

use Regulus\Fractal\Controllers\BaseController;

class SetsController extends BaseController {

	public function __construct()
	{
		parent::__construct();

		Fractal::setControllerPath($this);

		Site::set('section', 'Media');
		Site::set('subSection', 'Sets');
		Site::set('title', Fractal::lang('labels.mediaSets'));

		//set content type and views location
		Fractal::setContentType('media-set');

		Fractal::setViewsLocation('media.sets');

		Fractal::addTrailItem(Fractal::lang('labels.mediaSets'), Fractal::getControllerPath());
	}

	public function index()
	{
		$data = Fractal::setupPagination();
		$sets = Set::getSearchResults($data);

		Fractal::setContentForPagination($sets);

		$data     = Fractal::setPaginationMessage(true);
		$messages = Fractal::getPaginationMessageArray();

		if (!count($sets))
			$sets = Set::orderBy($data['sortField'], $data['sortOrder'])->paginate($data['itemsPerPage']);

		Fractal::addButton([
			'label' => Fractal::lang('labels.createSet'),
			'icon'  => 'glyphicon glyphicon-folder-open',
			'uri'   => Fractal::uri('create', true),
		]);

		return View::make(Fractal::view('list'))
			->with('content', $sets)
			->with('messages', $messages);
	}

	public function search()
	{
		$data = Fractal::setupPagination();
		$sets = Set::getSearchResults($data);

		Fractal::setContentForPagination($sets);

		$data = Fractal::setPaginationMessage();

		if (!count($sets))
			$data['content'] = Set::orderBy($data['sortField'], $data['sortOrder'])->paginate($data['itemsPerPage']);

		$data['result']['pages']     = Fractal::getLastPage();
		$data['result']['tableBody'] = Fractal::createTable($data['content'], true);

		return $data['result'];
	}

	public function create()
	{
		Site::set('title', Fractal::lang('labels.createSet'));
		Site::set('wysiwyg', true);

		Set::setDefaultsForNew();
		Form::setErrors();

		Fractal::addButton([
			'label' => Fractal::lang('labels.returnToSetsList'),
			'icon'  => 'glyphicon glyphicon-list',
			'uri'   => Fractal::uri('', true),
		]);

		Fractal::addTrailItem(Fractal::lang('labels.create'), Request::url());

		$items = $this->getItems();

		return View::make(Fractal::view('form'))
			->with('items', $items);
	}

	public function store()
	{
		Form::setValidationRules(Set::validationRules());

		$messages = [];
		if (Form::validated()) {
			$messages['success'] = Fractal::lang('messages.successCreated', ['item' => Fractal::langLowerA('labels.set')]);

			$input = Input::all();
			$input['user_id'] = Auth::user()->id;

			$set = Set::createNew($input);

			$set->saveItems(explode(',', $input['items']));

			Activity::log([
				'contentId'   => $set->id,
				'contentType' => 'Set',
				'action'      => 'Create',
				'description' => 'Created a Media Set',
				'details'     => 'Name: '.$set->title,
			]);

			return Redirect::to(Fractal::uri('', true))
				->with('messages', $messages);
		} else {
			$messages['error'] = Fractal::lang('messages.errorGeneral');
		}

		return Redirect::to(Fractal::uri('create', true))
			->with('messages', $messages)
			->with('errors', Form::getErrors())
			->withInput();
	}

	public function edit($slug)
	{
		$set = Set::findBySlug($slug);
		if (empty($set))
			return Redirect::to(Fractal::uri('pages'))->with('messages', [
				'error' => Fractal::lang('messages.errorNotFound', ['item' => Fractal::langLower('labels.set')])
			]);

		Site::set('title', $set->title.' ('.Fractal::lang('labels.mediaSet').')');
		Site::set('titleHeading', Fractal::lang('labels.updateSet').': <strong>'.Format::entities($set->title).'</strong>');
		Site::set('wysiwyg', true);

		Form::setDefaults($set);
		Form::setErrors();

		Fractal::addButton([
			'label' => Fractal::lang('labels.returnToSetsList'),
			'icon'  => 'glyphicon glyphicon-list',
			'uri'   => Fractal::uri('', true),
		]);

		Fractal::addTrailItem(Fractal::lang('labels.update'), Request::url());

		$items = $this->getItems($set);

		return View::make(Fractal::view('form'))
			->with('update', true)
			->with('id', $set->id)
			->with('items', $items);
	}

	public function update($slug)
	{
		$set = Set::findBySlug($slug);
		if (empty($set))
			return Redirect::to(Fractal::uri('pages'))->with('messages', [
				'error' => Fractal::lang('messages.errorNotFound', ['item' => Fractal::langLower('labels.set')])
			]);

		$set->setValidationRules();

		$messages = [];
		if (Form::validated()) {
			$messages['success'] = Fractal::lang('messages.successUpdated', ['item' => Fractal::langLowerA('labels.set')]);

			$set->saveData();

			$set->saveItems(explode(',', Input::get('items')));

			Activity::log([
				'contentId'   => $set->id,
				'contentType' => 'Set',
				'action'      => 'Update',
				'description' => 'Updated a Media Set',
				'details'     => 'Name: '.$set->title,
				'updated'     => true,
			]);

			return Redirect::to(Fractal::uri('', true))
				->with('messages', $messages);
		} else {
			$messages['error'] = Fractal::lang('messages.errorGeneral');

			return Redirect::to(Fractal::uri($slug.'/edit', true))
				->with('messages', $messages)
				->with('errors', Form::getErrors())
				->withInput();
		}
	}

	public function destroy($id)
	{
		$result = [
			'resultType' => 'Error',
			'message'    => Fractal::lang('messages.errorGeneral'),
		];

		$set = Set::find($id);
		if (empty($set))
			return $result;

		Activity::log([
			'contentId'   => $set->id,
			'contentType' => 'Set',
			'action'      => 'Delete',
			'description' => 'Deleted a Media Set',
			'details'     => 'Name: '.$set->title,
		]);

		$result['resultType'] = "Success";
		$result['message']    = Fractal::lang('messages.successDeleted', ['item' => '<strong>'.$set->title.'</strong>']);

		$set->items()->sync([]);

		$set->delete();

		return $result;
	}

	public function addItem()
	{
		$data = [
			'title'              => Fractal::lang('labels.addMediaItem'),
			'mediaItems'         => Item::orderBy('title')->get(),
			'mediaItemsSelected' => !is_null(Input::get('items')) ? Input::get('items') : [],
		];

		return Fractal::modalView('add_item', $data);
	}

	private function getItems($set = null)
	{
		$items = [];

		if (!Input::old('items') && !is_null($set))
		{
			foreach ($set->items as $item)
			{
				$items[] = array_merge($item->toArray(), ['imageUrl' => $item->getImageUrl(true)]);
			}
		} else {
			$itemIds = explode(',', Input::old('items'));

			foreach ($itemIds as $itemId)
			{
				$item = Item::find($itemId);

				if (!empty($item))
					$items[] = array_merge($item->toArray(), ['imageUrl' => $item->getImageUrl(true)]);
			}
		}

		return $items;
	}

}