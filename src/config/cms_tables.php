<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Tables
	|--------------------------------------------------------------------------
	|
	| The table setups for the various models in the CMS.
	|
	*/

	'menus' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.menu', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
			],
			[
				'attribute' => 'name',
			],
			[
				'label'     => 'Preview',
				'method'    => 'getActiveItemPreview()',
			],
			[
				'label'     => 'CMS',
				'attribute' => 'cms',
				'type'      => 'boolean',
				'developer' => true,
			],
			[
				'label'     => 'Last Updated',
				'method'    => 'getLastUpdatedDateTime()',
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/menus/:id/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_menu'),
						],
					],
					[
						'icon'       => 'remove',
						'class'      => 'btn btn-danger action-item',
						'attributes' => [
							'data-item-id'         => ':id',
							'data-item-name'       => ':title',
							'data-action'          => 'delete',
							'data-action-type'     => 'delete',
							'data-action-message'  => 'confirmDelete',
							'title'                => Fractal::trans('labels.delete_menu'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'menu',
		],
	],

	'pages' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.page', 2)]),
		],
		'columns' => [
			[
				'attribute'  => 'id',
				'sort'       => true,
			],
			[
				'attribute'  => 'title',
				'class'      => 'title',
				'sort'       => true,
			],
			[
				'attribute'  => 'slug',
				'sort'       => true,
			],
			[
				'label'      => 'Published',
				'method'     => 'getPublishedStatus()',
				'sort'       => 'published_at',
			],
			[
				'label'      => (Fractal::getSetting('Display Unique Content Views') ? 'Unique ' : '').'Views',
				'method'     => (Fractal::getSetting('Display Unique Content Views') ? 'getUniqueViews()' : 'getViews()'),
				'body_class' => 'text-align-right',
			],
			[
				'label'      => 'Last Updated',
				'method'     => 'getLastUpdatedDateTime()',
				'sort'       => 'updated_at',
			],
			[
				'label'      => 'Actions',
				'class'      => 'actions',
				'elements'   => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/pages/:slug/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_page'),
						],
					],
					[
						'icon'       => 'file',
						'class'      => 'btn btn-default',
						'uri'        => config('cms.page_uri') == "" ? ':slug' : config('cms.page_uri').'/:slug',
						'attributes' => [
							'title'  => Fractal::trans('labels.view_page'),
						],
					],
					[
						'icon'       => 'remove',
						'class'      => 'btn btn-danger action-item',
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_page'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix'       => 'page',
			'class_modifiers' => [
				'danger' => [
					'isPublished()' => false,
				],
			],
		],
	],

	'files' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.file', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'label'     => Fractal::trans('labels.image'),
				'method'    => 'getThumbnailImage()',
				'class'     => 'image',
				'sort'      => 'filename',
			],
			[
				'attribute' => 'name',
				'class'     => 'name',
				'sort'      => true,
			],
			[
				'label'     => Fractal::trans('labels.type'),
				'method'    => 'getType()',
				'sort'      => 'type_id',
			],
			[
				'label'     => Fractal::trans('labels.dimensions'),
				'method'    => 'getImageDimensions()',
				'sort'      => 'width',
			],
			[
				'label'     => 'Last Updated',
				'method'    => 'getLastUpdatedDateTime()',
				'sort'      => 'updated_at',
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/files/:id/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_file'),
						],
					],
					[
						'icon'       => 'remove',
						'class'      => 'btn btn-danger action-item',
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':name',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_file'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'file',
		],
	],

	'layout_templates' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.layout_template', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'attribute' => 'name',
				'class'     => 'name',
				'sort'      => true,
			],
			[
				'label'     => Fractal::trans('labels.pages'),
				'method'    => 'getNumberOfPages()',
			],
			[
				'label'     => Fractal::trans('labels.blog_articles'),
				'method'    => 'getNumberOfArticles()',
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'class_modifiers' => [
							'invisible' => [
								'static' => true,
							],
						],
						'uri'        => config('cms.base_uri').'/layout-templates/:id/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_layout_template'),
						],
					],
					[
						'icon'           => 'remove',
						'class'          => 'btn btn-danger action-item',
						'class_modifiers' => [
							'invisible' => [
								'static' => true,
							],
						],
						'attributes'     => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':name',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_layout_template'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'layout-template',
		],
	],

	'media_items' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.media_item', 2)]),
		],
		'columns' => [
			[
				'attribute'  => 'id',
				'sort'       => true,
			],
			[
				'label'      => Fractal::trans('labels.image'),
				'method'     => 'getThumbnailImage()',
				'class'      => 'image',
				'sort'       => 'filename',
			],
			[
				'label'      => Fractal::trans('labels.media_type'),
				'method'     => 'getType()',
				'sort'       => 'media_type_id',
			],
			[
				'attribute'  => 'title',
				'class'      => 'title',
				'sort'       => true,
			],
			[
				'label'      => 'Published',
				'method'     => 'getPublishedStatus()',
				'sort'       => 'published_at',
			],
			[
				'label'      => Fractal::trans('labels.media_sets'),
				'method'     => 'getNumberOfSets()',
				'body_class' => 'text-align-right',
			],
			[
				'label'      => (Fractal::getSetting('Display Unique Content Views') ? 'Unique ' : '').'Views',
				'method'     => (Fractal::getSetting('Display Unique Content Views') ? 'getUniqueViews()' : 'getViews()'),
				'body_class' => 'text-align-right',
			],
			[
				'label'      => (Fractal::getSetting('Display Unique Content Downloads') ? 'Unique ' : '').'Downloads',
				'method'     => (Fractal::getSetting('Display Unique Content Downloads') ? 'getUniqueDownloads()' : 'getDownloads()'),
				'body_class' => 'text-align-right',
			],
			[
				'label'      => 'Last Updated',
				'method'     => 'getLastUpdatedDateTime()',
				'sort'       => 'updated_at',
			],
			[
				'label'      => 'Actions',
				'class'      => 'actions',
				'elements'   => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/media/items/:slug/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_item'),
						],
					],
					[
						'icon'       => 'file',
						'class'      => 'btn btn-default',
						'url'        => Fractal::mediaUrl(config('media.base_uri') == false ? 'item/:slug' : config('media.base_uri').'/article/:slug'),
						'attributes' => [
							'title'  => Fractal::trans('labels.view_item'),
						],
					],
					[
						'icon'       => 'remove',
						'class'      => 'btn btn-danger action-item',
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-url'     => 'items/:id',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_item'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix'       => 'media-item',
			'class_modifiers' => [
				'danger' => [
					'isPublished()' => false,
				],
			],
		],
	],

	'media_types' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.media_type', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'attribute' => 'name',
				'class'     => 'name',
				'sort'      => true,
			],
			[
				'attribute' => 'slug',
				'sort'      => true,
			],
			[
				'label'     => 'File Type',
				'method'    => 'getFileType()',
				'sort'      => 'file_type_id',
			],
			[
				'label'     => 'Media Source Required',
				'attribute' => 'media_source_required',
				'type'      => 'boolean',
				'sort'      => true,
			],
			[
				'label'     => Fractal::trans('labels.items'),
				'method'    => 'getNumberOfItems()',
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/media/types/:slug/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_type'),
						],
					],
					[
						'icon'       => 'remove',
						'class'      => 'btn btn-danger action-item',
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':name',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-url'     => 'types/:id',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_type'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'media-type',
		],
	],

	'media_sets' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.media_set', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'attribute' => 'title',
				'class'     => 'name',
				'sort'      => true,
			],
			[
				'attribute' => 'slug',
				'sort'      => true,
			],
			[
				'label'     => Fractal::trans('labels.items'),
				'method'    => 'getNumberOfItems()',
			],
			[
				'attribute' => 'image_gallery',
				'type'      => 'boolean',
				'sort'      => true,
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/media/sets/:slug/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_set'),
						],
					],
					[
						'icon'           => 'remove',
						'class'          => 'btn btn-danger action-item',
						'attributes'     => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':name',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-url'     => 'sets/:id',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_set'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'media-set',
		],
	],

	'blog_articles' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.article', 2)]),
		],
		'columns' => [
			[
				'attribute'  => 'id',
				'sort'       => true,
			],
			[
				'attribute'  => 'title',
				'class'      => 'title',
				'sort'       => true,
			],
			[
				'attribute'  => 'slug',
				'sort'       => true,
			],
			[
				'label'      => Fractal::transChoice('labels.category', 2),
				'method'     => 'categories()',
				'attribute'  => 'name',
				'type'       => 'list',
			],
			[
				'label'      => 'Published',
				'method'     => 'getPublishedStatus()',
				'sort'       => 'published_at',
			],
			[
				'label'      => (Fractal::getSetting('Display Unique Content Views') ? 'Unique ' : '').'Views',
				'method'     => (Fractal::getSetting('Display Unique Content Views') ? 'getUniqueViews()' : 'getViews()'),
				'body_class' => 'text-align-right',
			],
			[
				'label'      => 'Last Updated',
				'method'     => 'getLastUpdatedDateTime()',
				'sort'       => 'updated_at',
			],
			[
				'label'      => 'Actions',
				'class'      => 'actions',
				'elements'   => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/blogs/articles/:slug/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_article'),
						],
					],
					[
						'icon'       => 'file',
						'class'      => 'btn btn-default',
						'url'        => Fractal::blogUrl(config('blogs.base_uri') == false ? 'article/:slug' : config('blogs.base_uri').'/article/:slug'),
						'attributes' => [
							'title'  => Fractal::trans('labels.view_article'),
						],
					],
					[
						'icon'       => 'remove',
						'class'      => 'btn btn-danger action-item',
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-url'     => 'articles/:id',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_article'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix'       => 'blog-article',
			'class_modifiers' => [
				'danger' => [
					'isPublished()' => false,
				],
			],
		],
	],

	'blog_categories' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.category', 2)]),
		],
		'columns' => [
			[
				'attribute'  => 'id',
				'sort'       => true,
			],
			[
				'attribute'  => 'name',
				'class'      => 'title',
				'sort'       => true,
			],
			[
				'attribute'  => 'slug',
				'sort'       => true,
			],
			[
				'label'      => '# of Articles',
				'method'     => 'getNumberOfArticles()',
				'body_class' => 'text-align-right',
			],
			[
				'label'      => 'Actions',
				'class'      => 'actions',
				'elements'   => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/blogs/categories/:slug/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_category'),
						],
					],
					[
						'icon'       => 'remove',
						'class'      => 'btn btn-danger action-item',
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-url'     => 'articles/:id',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_category'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'blog-category',
		],
	],

	'users' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.user', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'attribute' => 'username',
				'class'     => 'username',
				'sort'      => true,
			],
			[
				'attribute' => 'name',
				'method'    => 'getName()',
				'sort'      => 'last_name',
			],
			[
				'label'     => 'Email',
				'elements'  => [
					[
						'text' => ':email',
						'href' => 'mailto::email',
					],
				],
				'sort'      => 'email',
			],
			[
				'label'     => Fractal::trans('labels.roles'),
				'method'    => 'roles()',
				'attribute' => 'name',
				'type'      => 'list',
			],
			[
				'label'     => 'Activated',
				'method'    => 'isActivated()',
				'type'      => 'boolean',
				'sort'      => true,
			],
			[
				'label'     => 'Banned',
				'method'    => 'isBanned()',
				'type'      => 'boolean',
				'class'     => 'banned',
				'sort'      => true,
			],
			[
				'label'     => 'Last Updated',
				'attribute' => 'updated_at',
				'type'      => 'datetime',
				'sort'      => true,
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/users/:username/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_user'),
						],
					],
					[
						'icon'            => 'ban-circle',
						'class'           => 'btn btn-danger action-item ban-user',
						'class_modifiers' => [
							'hidden' => [
								'isBanned()' => true,
							],
							'invisible' => [
								'id' => 1,
							],
						],
						'attributes' => [
							'data-item-id'         => ':id',
							'data-item-name'       => ':username',
							'data-action-function' => 'actionBanUser',
							'data-action-message'  => 'confirmBanUser',
							'title'                => Fractal::trans('labels.ban_user'),
						],
					],
					[
						'icon'            => 'ok-circle',
						'class'           => 'btn btn-primary action-item unban-user',
						'class_modifiers' => [
							'hidden' => [
								'isBanned()' => false,
							],
							'invisible' => [
								'id' => 1,
							],
						],
						'attributes' => [
							'data-item-id'         => ':id',
							'data-item-name'       => ':username',
							'data-action-function' => 'actionUnbanUser',
							'data-action-message'  => 'confirmUnbanUser',
							'title'                => Fractal::trans('labels.unban_user'),
						],
					],
					[
						'icon'            => 'remove',
						'class'           => 'btn btn-danger action-item',
						'class_modifiers' => [
							'invisible' => [
								'id' => 1,
							],
						],
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':username',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_user'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix'       => 'user',
			'class_modifiers' => [
				'warning' => [
					'isActivated()' => false,
				],
				'danger' => [
					'isBanned()' => true,
				],
			],
		],
	],

	'user_roles' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.role', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'attribute' => 'role',
				'sort'      => true,
				'developer' => true,
			],
			[
				'attribute' => 'name',
				'sort'      => true,
				'class'     => 'name',
			],
			[
				'label'     => 'Last Updated',
				'attribute' => 'updated_at',
				'type'      => 'datetime',
				'sort'      => true,
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/users/roles/:id/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_role'),
						],
					],
					[
						'icon'            => 'remove',
						'class'           => 'btn btn-danger action-item',
						'class_modifiers' => [
							'invisible' => [
								'id' => 1,
							],
						],
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-url'     => 'roles/:id',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_role'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'user-role',
		],
	],

	'user_permissions' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.permission', 2)]),
		],
		'columns' => [
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'attribute' => 'permission',
				'sort'      => true,
			],
			[
				'attribute' => 'name',
				'sort'      => true,
				'class'     => 'name',
			],
			[
				'attribute' => 'description',
				'sort'      => true,
			],
			[
				'label'     => \Illuminate\Support\Str::plural(Fractal::trans('labels.role')),
				'method'    => 'roles()',
				'attribute' => 'name',
				'type'      => 'list',
			],
			[
				'label'     => 'Last Updated',
				'attribute' => 'updated_at',
				'type'      => 'datetime',
				'sort'      => true,
			],
			[
				'label'     => 'Actions',
				'class'     => 'actions',
				'elements'  => [
					[
						'icon'       => 'edit',
						'class'      => 'btn btn-primary',
						'uri'        => config('cms.base_uri').'/users/permissions/:id/edit',
						'attributes' => [
							'title' => Fractal::trans('labels.edit_permission'),
						],
					],
					[
						'icon'            => 'remove',
						'class'           => 'btn btn-danger action-item',
						'class_modifiers' => [
							'invisible' => [
								'id' => 1,
							],
						],
						'attributes' => [
							'data-item-id'        => ':id',
							'data-item-name'      => ':title',
							'data-action'         => 'delete',
							'data-action-type'    => 'delete',
							'data-action-url'     => 'permissions/:id',
							'data-action-message' => 'confirmDelete',
							'title'               => Fractal::trans('labels.delete_permission'),
						],
					],
				],
			],
		],
		'rows' => [
			'id_prefix' => 'user-permission',
		],
	],

	'activities' => [
		'table' => [
			'class'           => 'table-striped table-bordered table-hover table-sortable',
			'no_data_message' => Fractal::trans('messages.no_items', ['items' => Fractal::transLowerChoice('labels.activity', 2)]),
		],
		'columns' => [
			[
				'label'     => '',
				'method'    => 'getIconMarkup()',
			],
			[
				'attribute' => 'id',
				'sort'      => true,
			],
			[
				'label'     => 'Name',
				'method'    => 'getName()',
				'sort'      => 'user_id',
			],
			[
				'attribute' => 'description',
				'sort'      => true,
			],
			[
				'attribute' => 'details',
				'sort'      => true,
			],
			[
				'attribute' => 'developer',
				'type'      => 'boolean',
				'sort'      => true,
				'developer' => true,
			],
			[
				'label'     => 'IP Address',
				'attribute' => 'ip_address',
				'sort'      => true,
				'developer' => true,
			],
			[
				'label'     => 'User Agent',
				'method'    => 'getUserAgentPreview()',
				'sort'      => 'user_agent',
				'class'     => 'small-text',
				'developer' => true,
			],
			[
				'label'     => Fractal::trans('labels.timestamp'),
				'attribute' => 'created_at',
				'type'      => 'datetime',
				'sort'      => true,
			],
		],
		'rows' => [
			'id_prefix' => 'activity',
		],
	],

];