@extends(Config::get('fractal::layout'))

@section(Config::get('fractal::section'))

	{{-- Search & Pagination --}}
	@include(Config::get('fractal::viewsLocation').'partials.search_pagination')

	{{-- Content Table --}}
	<div class="row">
		<div class="col-md-12">
			{{ HTML::table(Config::get('fractal::tables.users'), $users) }}
		</div>
	</div>

	{{-- Bottom Pagination --}}
	@include(Config::get('fractal::viewsLocation').'partials.pagination')

	<a class="btn btn-default" href="{{ Fractal::url('users/create') }}">{{ Lang::get('fractal::labels.createUser') }}</a>

@stop