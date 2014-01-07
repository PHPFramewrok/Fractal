<li class="{{ $menuItem->class }}">
	<a href="{{ URL::to($menuItem->uri) }}" class="{{ $menuItem->anchorClass }}"{{ !empty($menuItem->children) ? ' data-toggle="dropdown"' : '' }}>
		{{ $menuItem->label }}

		@if (!empty($menuItem->children))
			<b class="caret"></b>
		@endif
	</a>

	@if (!empty($menuItem->children))
		<ul class="dropdown-menu">
			@include(Fractal::view('partials.menu', true), array('menu' => $menuItem->children, 'listItemsOnly' => true))
		</ul>
	@endif
</li>