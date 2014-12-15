<nav class="navbar navbar-inverse" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			@if (Config::get('fractal::logo') && is_string(Config::get('fractal::logo')))
				<a class="navbar-brand" id="logo" href="{{ URL::to('') }}">
					<img src="{{ Fractal::getImagePathFromConfig('logo') }}" alt="{{{ Site::get('name') }}}" title="{{{ Site::get('name') }}}" id="logo" />
				</a>
			@else
				<a class="navbar-brand" href="{{ URL::to('') }}">{{{ Site::get('name') }}}</a>
			@endif
		</div>

		<div class="navbar-collapse collapse">

			{{ Fractal::getMenuMarkup('Main') }}

			@if (Auth::is('admin'))

				<ul class="nav navbar-nav navbar-right" id="nav-return-to-cms">
					<li>
						<a href="{{ Fractal::url() }}">
							<span class="glyphicon glyphicon-log-in"></span>&nbsp; 
							<span class="menu-item-label">{{ Fractal::lang('labels.returnToCms') }}</span>
						</a>
					</li>
				</ul>

			@endif

			@yield('search')

		</div><!-- /.nav-collapse -->
	</div><!-- /.container -->
</nav>