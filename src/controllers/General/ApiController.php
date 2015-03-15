<?php namespace Regulus\Fractal\Controllers\General;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

use Fractal;

use Auth;

use Regulus\Fractal\Models\Content\File as ContentFile;
use Regulus\Fractal\Models\Media\Item as MediaItem;

use Regulus\Fractal\Controllers\BaseController;

class ApiController extends BaseController {

	public function postSetUserState()
	{
		return (int) Auth::setState(Input::get('name'), Input::get('state'));
	}

	public function postRemoveUserState()
	{
		return (int) Auth::removeState(Input::get('name'), Input::get('state'));
	}

	public function postSaveContent()
	{
		$contentType = Input::get('content_type');

		if (in_array($contentType, ['page', 'blog-article', 'media-item']))
		{
			if (Input::get('id') == "")
			{
				$content = Input::except('_token');

				if (isset($content['content_areas']))
				{
					foreach ($content['content_areas'] as $i => $contentArea)
					{
						if (isset($contentArea['content_type']) && in_array($contentArea['content_type'], ['Markdown', 'HTML'])
						&& isset($contentArea['content_markdown']) && isset($contentArea['content_html']))
						{
							$content['content_areas'][$i]['content'] = $contentArea['content_'.strtolower($contentArea['content_type'])];

							unset($content['content_areas'][$i]['content_markdown']);
							unset($content['content_areas'][$i]['content_html']);
						}
					}
				}

				Auth::setState('savedContent.'.camel_case(str_replace('-', '_', $contentType)), $content);

				return 1;
			}
		}

		return 0;
	}

	public function postSelectFileMediaItem()
	{
		$type = Input::get('type') == "Media Item" ? Input::get('type') : 'File';

		if ($type == "Media Item")
		{
			$data = [
				'title' => Fractal::trans('labels.select_item', ['item' => Fractal::transChoice('labels.media_item')]),
				'type'  => $type,
				'items' => MediaItem::orderBy('title')->get(),
			];
		} else { //"File"
			$data = [
				'title' => Fractal::trans('labels.select_item', ['item' => Fractal::transChoice('labels.file')]),
				'type'  => $type,
				'items' => ContentFile::orderBy('name')->get(),
			];
		}

		return Fractal::modalView('partials.modals.select_file_media_item', $data, true);
	}

	public function getViewMarkdownGuide()
	{
		return Fractal::modalView('partials.modals.markdown_guide', ['title' => Fractal::trans('labels.markdownGuide')], true);
	}

}