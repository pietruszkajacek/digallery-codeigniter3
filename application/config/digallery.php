<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['meta']['title']			= 'Fotograficzny Portal Społecznościowy - digallery';
$config['meta']['site_description']		= 'Fotograficzny Portal Społecznościowy. Pokaż światu swoje prace niezależnie czy jesteś amatorem czy profesjonalistą.';
$config['meta']['site_keywords']			= 'fotografia cyfrowa, zdjęcia';
$config['meta']['site_author']			= 'Jacek Pietruszka';

$config['uploads']['path']			= 'uploads/';
$config['uploads']['file_type']		= 'gif|jpg|jpeg|png';
$config['uploads']['max_size']		= '8192';
$config['uploads']['max_filename']	= '100';

$config['avatar']['path']			= 'uploads/avatars/';
$config['avatar']['default']		= 'default_avatar.png';
$config['avatar']['file_type']		= 'gif|jpg|png';
$config['avatar']['max_size']		= '100';
$config['avatar']['max_width']		= '50';
$config['avatar']['max_height']		= '50';

$config['picture']['path']			= 'uploads/pictures/';
$config['picture']['default']		= 'default_picture.png';
$config['picture']['file_type']		= 'gif|jpg|jpeg|png';
$config['picture']['max_size']		= '150';
$config['picture']['max_width']		= '150';
$config['picture']['max_height']	= '150';

$config['thumb_mini']['path']		= 'uploads/thumbs_mini/';
$config['thumb_mini']['width']		= '45';
$config['thumb_mini']['height']		= '56';

$config['thumb_small']['path']		= 'uploads/thumbs_small/';
$config['thumb_small']['width']		= '108';
$config['thumb_small']['height']	= '100';

$config['thumb_preview']['path']	= 'uploads/thumbs_preview/';
$config['thumb_preview']['width']	= '675';
$config['thumb_preview']['height']	= '550';

$config['thumb_gallery']['path']	= 'uploads/thumbs_preview/';
$config['thumb_gallery']['width']	= '108';
$config['thumb_gallery']['height']	= '100';

$config['who_add_favorites']['page_size'] = '50';
$config['image_comments']['page_size'] = '20';

$config['gallery_comments']['page_size'] = '20';

$config['profile_comments']['page_size'] = '20';

$config['name_of_ratings'][0] = array('name' => '', 'rate' => 0);
$config['name_of_ratings'][8] = array('name' => 'rewelacja', 'rate' => 8);
$config['name_of_ratings'][7] = array('name' => 'bardzo dobre', 'rate' => 7);
$config['name_of_ratings'][6] = array('name' => 'dobre', 'rate' => 6);
$config['name_of_ratings'][5] = array('name' => 'powyżej przeciętnej', 'rate' => 5);
$config['name_of_ratings'][4] = array('name' => 'przeciętne', 'rate' => 4);
$config['name_of_ratings'][3] = array('name' => 'poniżej przeciętnej', 'rate' => 3);
$config['name_of_ratings'][2] = array('name' => 'słabe', 'rate' => 2);
$config['name_of_ratings'][1] = array('name' => 'poniżej krytyki', 'rate' => 1);

$config['thumbs_add_gallery']['page_size'] = 20;

$config['browse']['filter'] = array(
	10 => array('desc' => '8 godz.', 'sec' => 28800),
	11 => array('desc' => '24 godz.', 'sec' => 86400),
	12 => array('desc' => '3 dni', 'sec' => 259200),
	13 => array('desc' => '1 tydzień', 'sec' => 604800),
	14 => array('desc' => '1 miesiąc', 'sec' => 2592000),
	 0 => array('desc' => 'wszystkie'),
);

$config['browse']['sort'] = array(
	'dd' => 'data dodania',
	'oc' => 'ocena',
	'ul' => 'ulubione'
);