@extends(Config::get('fractal::layout'))

@section(Config::get('fractal::section'))

	{{ Form::openResource() }}
		<div class="row">
			<div class="col-md-4">
				{{ Form::field('role') }}
			</div><div class="col-md-4">
				{{ Form::field('name') }}
			</div><div class="col-md-4">
				{{ Form::field('display_order', 'select', array('options' => Form::numberOptions(1, 36))) }}
			</div>
		</div>

		{{ Form::field(Form::submitResource(Lang::get('fractal::labels.role'), (isset($update) && $update)), 'button') }}
	{{ Form::close() }}

@stop