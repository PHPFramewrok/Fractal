<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Tables
	|--------------------------------------------------------------------------
	|
	| The table setups for the various models in the CMS.
	|
	*/

	'menus' => array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.menu')))),
		),
		'columns' => array(
			array(
				'attribute' => 'id',
			),
			array(
				'attribute' => 'name',
			),
			array(
				'label'     => 'Preview',
				'method'    => 'getActiveItemPreview()',
			),
			array(
				'label'     => 'CMS',
				'attribute' => 'cms',
				'type'      => 'boolean',
				'developer' => true,
			),
			array(
				'label'     => 'Last Updated',
				'method'    => 'getLastUpdatedDateTime()',
			),
			array(
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => array(
					array(
						'icon'       => 'edit',
						'uri'        => Config::get('fractal::baseUri').'/menus/:id/edit',
						'attributes' => array(
							'title'          => Lang::get('fractal::labels.editMenu'),
						),
					),
					array(
						'icon'       => 'remove',
						'class'      => 'action-item red',
						'attributes' => array(
							'data-item-id'         => ':id',
							'data-item-name'       => ':title',
							'data-action'          => 'delete',
							'data-action-type'     => 'delete',
							'data-action-message'  => 'confirmDelete',
							'title'                => Lang::get('fractal::labels.deleteMenu'),
						),
					),
				),
			),
		),
		'rows' => array(
			'idPrefix' => 'menu',
		),
	),

	'pages' => array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover table-sortable',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.page')))),
		),
		'columns' => array(
			array(
				'attribute' => 'id',
				'sort'      => true,
			),
			array(
				'attribute' => 'title',
				'class'     => 'title',
				'sort'      => true,
			),
			array(
				'attribute' => 'slug',
				'sort'      => true,
			),
			array(
				'label'     => 'Published',
				'method'    => 'getPublishedStatus()',
				'sort'      => 'published_at',
			),
			array(
				'label'     => (Fractal::getSetting('Display Unique Content Views') ? 'Unique ' : '').'Views',
				'method'    => (Fractal::getSetting('Display Unique Content Views') ? 'getUniqueViews()' : 'getViews()'),
				'bodyClass' => 'text-align-right',
			),
			array(
				'label'     => 'Last Updated',
				'method'    => 'getLastUpdatedDateTime()',
				'sort'      => 'updated_at',
			),
			array(
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => array(
					array(
						'icon'       => 'edit',
						'uri'        => Config::get('fractal::baseUri').'/pages/:slug/edit',
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.editPage'),
						),
					),
					array(
						'icon'       => 'file',
						'uri'        => Config::get('fractal::pageUri') == "" ? ':slug' : Config::get('fractal::pageUri').'/:slug',
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.viewPage'),
							'target'       => '_blank',
						),
					),
					array(
						'icon'       => 'remove',
						'class'      => 'action-item red',
						'attributes' => array(
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Lang::get('fractal::labels.deletePage'),
						),
					),
				),
			),
		),
		'rows' => array(
			'idPrefix'       => 'page',
			'classModifiers' => array(
				'danger' => array(
					'isPublished()' => false,
				),
			),
		),
	),

	'files' => array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover table-sortable',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.file')))),
		),
		'columns' => array(
			array(
				'attribute' => 'id',
				'sort'      => true,
			),
			array(
				'label'     => Lang::get('fractal::labels.image'),
				'method'    => 'getThumbnailImage()',
				'class'     => 'image',
				'sort'      => 'filename',
			),
			array(
				'attribute' => 'name',
				'class'     => 'name',
				'sort'      => true,
			),
			array(
				'label'     => Lang::get('fractal::labels.type'),
				'attribute' => 'type',
				'sort'      => true,
			),
			array(
				'label'     => Lang::get('fractal::labels.dimensions'),
				'method'    => 'getImageDimensions()',
				'sort'      => 'width',
			),
			array(
				'label'     => 'Last Updated',
				'method'    => 'getLastUpdatedDateTime()',
				'sort'      => 'updated_at',
			),
			array(
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => array(
					array(
						'icon'       => 'edit',
						'uri'        => Config::get('fractal::baseUri').'/files/:id/edit',
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.editFile'),
						),
					),
					array(
						'icon'       => 'remove',
						'class'      => 'action-item red',
						'attributes' => array(
							'data-item-id'        => ':id',
							'data-item-name'      => ':name',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Lang::get('fractal::labels.deleteFile'),
						),
					),
				),
			),
		),
		'rows' => array(
			'idPrefix'       => 'file',
		),
	),

	'blogArticles' => array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover table-sortable',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.article')))),
		),
		'columns' => array(
			array(
				'attribute' => 'id',
				'sort'      => true,
			),
			array(
				'attribute' => 'title',
				'class'     => 'title',
				'sort'      => true,
			),
			array(
				'attribute' => 'slug',
				'sort'      => true,
			),
			array(
				'label'     => 'Published',
				'method'    => 'getPublishedStatus()',
				'sort'      => 'published_at',
			),
			array(
				'label'     => (Fractal::getSetting('Display Unique Content Views') ? 'Unique ' : '').'Views',
				'method'    => (Fractal::getSetting('Display Unique Content Views') ? 'getUniqueViews()' : 'getViews()'),
				'bodyClass' => 'text-align-right',
			),
			array(
				'label'     => 'Last Updated',
				'method'    => 'getLastUpdatedDateTime()',
				'sort'      => 'updated_at',
			),
			array(
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => array(
					array(
						'icon'       => 'edit',
						'uri'        => Config::get('fractal::baseUri').'/blog/articles/:slug/edit',
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.editArticle'),
						),
					),
					array(
						'icon'       => 'file',
						'url'        => Fractal::blogUrl(Config::get('fractal::blog.baseUri') == false ? 'article/:slug' : Config::get('fractal::blog.baseUri').'/article/:slug'),
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.viewArticle'),
							'target'       => '_blank',
						),
					),
					array(
						'icon'       => 'remove',
						'class'      => 'action-item red',
						'attributes' => array(
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Lang::get('fractal::labels.deleteArticle'),
						),
					),
				),
			),
		),
		'rows' => array(
			'idPrefix'       => 'page',
			'classModifiers' => array(
				'danger' => array(
					'isPublished()' => false,
				),
			),
		),
	),

	'users' => array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover table-sortable',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.user')))),
		),
		'columns' => array(
			array(
				'attribute' => 'id',
				'sort'      => true,
			),
			array(
				'attribute' => 'username',
				'class'     => 'username',
				'sort'      => true,
			),
			array(
				'attribute' => 'name',
				'method'    => 'getName()',
				'sort'      => 'last_name',
			),
			array(
				'label'     => 'Email',
				'elements'  => array(
					array(
						'text' => ':email',
						'href' => 'mailto::email',
					),
				),
				'sort'      => 'email',
			),
			array(
				'label'     => 'Role(s)',
				'method'    => 'roles()',
				'attribute' => 'name',
				'type'      => 'list',
			),
			array(
				'label'     => 'Activated',
				'method'    => 'isActivated()',
				'type'      => 'boolean',
				'sort'      => true,
			),
			array(
				'label'     => 'Banned',
				'method'    => 'isBanned()',
				'type'      => 'boolean',
				'class'     => 'banned',
				'sort'      => true,
			),
			array(
				'label'     => 'Last Updated',
				'attribute' => 'updated_at',
				'type'      => 'dateTime',
				'sort'      => true,
			),
			array(
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => array(
					array(
						'icon'       => 'edit',
						'uri'        => Config::get('fractal::baseUri').'/users/:username/edit',
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.editUser'),
						),
					),
					array(
						'icon'           => 'ban-circle',
						'class'          => 'action-item ban-user red',
						'classModifiers' => array(
							'hidden' => array(
								'isBanned()' => true,
							),
							'invisible' => array(
								'id' => 1,
							),
						),
						'attributes'     => array(
							'data-item-id'         => ':id',
							'data-item-name'       => ':username',
							'data-action-function' => 'actionBanUser',
							'data-action-message'  => 'confirmBanUser',
							'title'                => Lang::get('fractal::labels.banUser'),
						),
					),
					array(
						'icon'           => 'ok-circle',
						'class'          => 'action-item unban-user',
						'classModifiers' => array(
							'hidden'       => array(
								'isBanned()' => false,
							),
							'invisible'    => array(
								'id' => 1,
							),
						),
						'attributes'     => array(
							'data-item-id'         => ':id',
							'data-item-name'       => ':username',
							'data-action-function' => 'actionUnbanUser',
							'data-action-message'  => 'confirmUnbanUser',
							'title'                => Lang::get('fractal::labels.unbanUser'),
						),
					),
					array(
						'icon'           => 'remove',
						'class'          => 'action-item red',
						'classModifiers' => array(
							'invisible'    => array(
								'id' => 1,
							),
						),
						'attributes'     => array(
							'data-item-id'        => ':id',
							'data-item-name'      => ':username',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Lang::get('fractal::labels.deleteUser'),
						),
					),
				),
			),
		),
		'rows' => array(
			'idPrefix'       => 'user',
			'classModifiers' => array(
				'warning' => array(
					'isActivated()' => false,
				),
				'danger' => array(
					'isBanned()'    => true,
				),
			),
		),
	),

	'userRoles' => array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover table-sortable',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.role')))),
		),
		'columns' => array(
			array(
				'attribute' => 'id',
				'sort'      => true,
			),
			array(
				'attribute' => 'role',
				'sort'      => true,
				'developer' => true,
			),
			array(
				'attribute' => 'name',
				'sort'      => true,
				'class'     => 'name',
			),
			array(
				'label'     => 'Last Updated',
				'attribute' => 'updated_at',
				'type'      => 'dateTime',
				'sort'      => true,
			),
			array(
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => array(
					array(
						'icon'       => 'edit',
						'uri'        => Config::get('fractal::baseUri').'/user-roles/:id/edit',
						'attributes' => array(
							'title'        => Lang::get('fractal::labels.editRole'),
						),
					),
					array(
						'icon'       => 'remove',
						'class'      => 'action-item red',
						'classModifiers' => array(
							'invisible'    => array(
								'id' => 1,
							),
						),
						'attributes' => array(
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Lang::get('fractal::labels.deleteRole'),
						),
					),
				),
			),
		),
		'rows' => array(
			'idPrefix' => 'user-role',
		),
	),

	'activities' => array(
		'table' => array(
			'class'         => 'table-striped table-bordered table-hover table-sortable',
			'noDataMessage' => Lang::get('fractal::messages.noItems', array('items' => Str::plural(Lang::get('fractal::labels.activity')))),
		),
		'columns' => array(
			array(
				'label'     => '',
				'method'    => 'getIconMarkup()',
			),
			array(
				'attribute' => 'id',
				'sort'      => true,
			),
			array(
				'label'     => 'Name',
				'method'    => 'getName()',
				'sort'      => 'user_id',
			),
			array(
				'attribute' => 'description',
				'sort'      => true,
			),
			array(
				'attribute' => 'details',
				'sort'      => true,
			),
			array(
				'attribute' => 'developer',
				'type'      => 'boolean',
				'sort'      => true,
				'developer' => true,
			),
			array(
				'label'     => 'IP Address',
				'attribute' => 'ip_address',
				'sort'      => true,
				'developer' => true,
			),
			array(
				'label'     => 'User Agent',
				'method'    => 'getUserAgentPreview()',
				'sort'      => 'user_agent',
				'class'     => 'small-text',
				'developer' => true,
			),
			array(
				'label'     => Lang::get('fractal::labels.timestamp'),
				'attribute' => 'created_at',
				'type'      => 'dateTime',
				'sort'      => true,
			),
		),
		'rows' => array(
			'idPrefix' => 'activity',
		),
	),

);