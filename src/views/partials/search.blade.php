<div class="col-md-4 padding-0">
	{{ Form::text('search', null, array('placeholder' => Lang::get('fractal::labels.searchBox'))) }}

	{{ Form::hidden('page', (isset($page) ? $page : 1)) }}
	{{ Form::hidden('changing_page', 0) }}

	{{ Form::hidden('sort_field') }}
	{{ Form::hidden('sort_order') }}
</div>