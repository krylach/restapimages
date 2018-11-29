<?
/*
 * (c) forka
 *
 */
ini_set('file_uploads', 1);

return (object)[
	'url' => 'http://rest.api',
	'dir' => __DIR__."\\",
	'dbase' => (object)[
		'host' => 'localhost',
		'user' => 'forka',
		'pass' => '',
		'base' => ''
	],
	'validation_image' =>  ['jpg', 'jpeg']
];