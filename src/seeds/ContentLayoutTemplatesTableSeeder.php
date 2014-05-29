<?php

class ContentLayoutTemplatesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('content_layout_templates')->truncate();

		$timestamp = date('Y-m-d H:i:s');

		$templates = array(
			array(
				'name'       => 'Standard',
				'layout'     => "<div class=\"row\">\n\t<div class=\"col-md-12\">{{main}}</div>\n</div>",
				'created_at' => $timestamp,
				'updated_at' => $timestamp,
			),
			array(
				'name'       => 'Standard with Side',
				'layout'     => "<div class=\"row\">\n\t<div class=\"col-md-9\">{{main}}</div>\n\t<div class=\"col-md-3\"><div class=\"content-side\">{{side}}</div></div>\n</div>",
				'created_at' => $timestamp,
				'updated_at' => $timestamp,
			),
			array(
				'name'       => '2 Columns',
				'layout'     => "<div class=\"row\">\n\t<div class=\"col-md-6\">{{column-1}}</div>\n\t<div class=\"col-md-6\">{{column-2}}</div>\n</div>",
				'created_at' => $timestamp,
				'updated_at' => $timestamp,
			),
			array(
				'name'       => '3 Columns',
				'layout'     => "<div class=\"row\">\n\t<div class=\"col-md-4\">{{column-1}}</div>\n\t<div class=\"col-md-4\">{{column-2}}</div>\n\t<div class=\"col-md-4\">{{column-3}}</div>\n</div>",
				'created_at' => $timestamp,
				'updated_at' => $timestamp,
			),
			array(
				'name'       => '4 Columns',
				'layout'     => "<div class=\"row\">\n\t<div class=\"col-md-3\">{{column-1}}</div>\n\t<div class=\"col-md-3\">{{column-2}}</div>\n\t<div class=\"col-md-3\">{{column-3}}</div>\n\t<div class=\"col-md-3\">{{column-4}}</div>\n</div>",
				'created_at' => $timestamp,
				'updated_at' => $timestamp,
			),
		);

		foreach ($templates as $template) {
			DB::table('content_layout_templates')->insert($template);
		}
	}

}