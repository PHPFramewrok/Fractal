@extends(config('cms.layout'))

@section(config('cms.content_section'))

	<script type="text/javascript">
		$(document).ready(function()
		{
			@if (!isset($update) || !$update)
				$('#field-name').keyup(function()
				{
					$('#field-name').val($('#field-name').val().replace(/  /g, ' '));

					var slug = Fractal.strToSlug($('#field-name').val());
					$('#field-slug').val(slug);
				});
			@endif

			$('#field-slug').keyup(function()
			{
				var slug = Fractal.strToSlug($('#field-slug').val());
				$('#field-slug').val(slug);
			});
		});
	</script>

	{!! Form::openResource() !!}

		<div class="row">
			<div class="col-md-6">
				{!! Form::field('name') !!}
			</div>
			<div class="col-md-6">
				{!! Form::field('slug') !!}
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				{!! Form::field(Form::submitResource(Fractal::transChoice('labels.category')), 'button') !!}
			</div>
		</div>

	{!! Form::close() !!}

@stop