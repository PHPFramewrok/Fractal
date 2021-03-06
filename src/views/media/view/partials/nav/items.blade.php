<ul class="nav nav-pills nav-stacked nav-side">

	<li class="special">
		<a href="{{ Fractal::mediaUrl('') }}">
			<i class="fa fa-chevron-left"></i> {{ Fractal::trans('labels.media_return_to_home') }}
		</a>
	</li>

	@foreach ($mediaItems as $mediaItemListed)

		<li{{ HTML::activeArea(isset($mediaItem->id) && $mediaItemListed->id == $mediaItem->id) }}>
			<a href="{{ $mediaItemListed->getUrl() }}">
				<i class="fa fa-file"></i> {{ $mediaItemListed->getTitle() }}

				@if ($mediaItemListed->published_at)
					<div>
						<time datetime="{{ $mediaItemListed->published_at }}">{{ $mediaItemListed->getPublishedDate() }}</time>
					</div>
				@endif
			</a>
		</li>

	@endforeach

</ul>