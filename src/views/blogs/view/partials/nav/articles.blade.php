<ul class="nav nav-pills nav-stacked nav-side">

	<li class="special">
		<a href="{{ Fractal::blogUrl('') }}">
			<i class="fa fa-chevron-left"></i> {{ Fractal::trans('labels.blog_return_to_home') }}
		</a>
	</li>

	@foreach ($articles as $articleListed)

		<li{!! HTML::activeArea(isset($article->id) && $articleListed->id == $article->id) !!}>
			<a href="{{ $articleListed->getUrl() }}">
				<i class="fa fa-file"></i> {{ $articleListed->getTitle() }}

				@if ($articleListed->published_at)
					<div>
						<time datetime="{{ $articleListed->published_at }}">{{ $articleListed->getPublishedDate() }}</time>
					</div>
				@endif
			</a>
		</li>

	@endforeach

</ul>