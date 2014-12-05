<?php
//--------------------------------------------------------------//
//              FRONT
//--------------------------------------------------------------//
$routes[] = array(
	"route" 		=> '/',
	'type' 			=> 'get',
	"controller" 	=> 'Frontend',
	"method" 		=> "index",
);

$routes[] = array(
	"route" 		=> '/{slug}-{id}.html',
	'type' 			=> 'get',
	"controller" 	=> 'Frontend',
	"method" 		=> "page",
	"bind"			=> 'front.page'
);

$routes[] = array(
	"route" 		=> '/{slug}/',
	'type' 			=> 'get',
	"controller" 	=> 'Frontend',
	"method" 		=> "category",
	"bind"			=> 'front.category'
);

//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              ADMIN
//--------------------------------------------------------------//
$routes[] = array(
	"route" 		=> '/admin/',
	'type' 			=> 'get',
	"controller" 	=> 'Admin',
	"method" 		=> "index",
);

$routes[] = array(
	"route" 		=> '/login',
	'type' 			=> 'get',
	"controller" 	=> 'Admin',
	"method" 		=> "login",
);


//--------------------------------------------------------------//
//              ADMIN PAGES
//--------------------------------------------------------------//
$routes[] = array(
	"route" 		=> '/admin/pages/',
	'type' 			=> 'get',
	"controller" 	=> 'AdminPage',
	"method" 		=> "index",
	"bind"			=> 'admin.page.index'
);

$routes[] = array(
	"route" 		=> '/admin/pages/create',
	'type' 			=> 'get',
	"controller" 	=> 'AdminPage',
	"method" 		=> "edit",
	"bind"			=> 'admin.page.create'
);

$routes[] = array(
	"route" 		=> '/admin/pages/{id}',
	'type' 			=> 'get',
	"controller" 	=> 'AdminPage',
	"method" 		=> "edit",
	"bind"			=> 'admin.page.edit'
);

$routes[] = array(
	"route" 		=> '/admin/pages/save',
	'type' 			=> 'POST',
	"controller" 	=> 'AdminPage',
	"method" 		=> "save",
	"bind"			=> 'admin.page.save'
);
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              ADMIN CATEGORIES
//--------------------------------------------------------------//
$routes[] = array(
	"route" 		=> '/admin/categories/',
	'type' 			=> 'get',
	"controller" 	=> 'AdminCategory',
	"method" 		=> "index",
	"bind"			=> 'admin.categories.index'
);

$routes[] = array(
	"route" 		=> '/admin/categories/create',
	'type' 			=> 'get',
	"controller" 	=> 'AdminCategory',
	"method" 		=> "edit",
	"bind"			=> 'admin.categories.create'
);

$routes[] = array(
	"route" 		=> '/admin/categories/{id}',
	'type' 			=> 'get',
	"controller" 	=> 'AdminCategory',
	"method" 		=> "edit",
	"bind"			=> 'admin.categories.edit'
);

$routes[] = array(
	"route" 		=> '/admin/categories/save',
	'type' 			=> 'POST',
	"controller" 	=> 'AdminCategory',
	"method" 		=> "save",
	"bind"			=> 'admin.categories.save'
);
//--------------------------------------------------------------//



//--------------------------------------------------------------//
//              ADMIN USERS
//--------------------------------------------------------------//
$routes[] = array(
	"route" 		=> '/admin/users/',
	'type' 			=> 'get',
	"controller" 	=> 'AdminUser',
	"method" 		=> "index",
	"bind"			=> 'admin.users.index'
);

$routes[] = array(
	"route" 		=> '/admin/users/create',
	'type' 			=> 'get',
	"controller" 	=> 'AdminUser',
	"method" 		=> "edit",
	"bind"			=> 'admin.users.create'
);

$routes[] = array(
	"route" 		=> '/admin/users/{id}',
	'type' 			=> 'get',
	"controller" 	=> 'AdminUser',
	"method" 		=> "edit",
	"bind"			=> 'admin.users.edit'
);


$routes[] = array(
	"route" 		=> '/admin/users/delete/{id}',
	'type' 			=> 'get',
	"controller" 	=> 'AdminUser',
	"method" 		=> "delete",
	"bind"			=> 'admin.users.delete'
);

$routes[] = array(
	"route" 		=> '/admin/users/save',
	'type' 			=> 'POST',
	"controller" 	=> 'AdminUser',
	"method" 		=> "save",
	"bind"			=> 'admin.users.save'
);
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              ADMIN MEDIAS
//--------------------------------------------------------------//
$routes[] = array(
	"route" 		=> '/admin/medias/',
	'type' 			=> 'get',
	"controller" 	=> 'AdminMedia',
	"method" 		=> "index",
	"bind"			=> 'admin.medias.index'
);

$routes[] = array(
	"route" 		=> '/admin/medias/create',
	'type' 			=> 'get',
	"controller" 	=> 'AdminMedia',
	"method" 		=> "edit",
	"bind"			=> 'admin.medias.create'
);

$routes[] = array(
	"route" 		=> '/admin/medias/{id}',
	'type' 			=> 'get',
	"controller" 	=> 'AdminMedia',
	"method" 		=> "edit",
	"bind"			=> 'admin.medias.edit'
);


$routes[] = array(
	"route" 		=> '/admin/medias/delete/{id}',
	'type' 			=> 'get',
	"controller" 	=> 'AdminMedia',
	"method" 		=> "delete",
	"bind"			=> 'admin.medias.delete'
);

$routes[] = array(
	"route" 		=> '/admin/medias/save',
	'type' 			=> 'POST',
	"controller" 	=> 'AdminMedia',
	"method" 		=> "save",
	"bind"			=> 'admin.medias.save'
);
//--------------------------------------------------------------//


//--------------------------------------------------------------//
//              ADMIN MENU
//--------------------------------------------------------------//
$routes[] = array(
	"route" 		=> '/admin/menu/',
	'type' 			=> 'get',
	"controller" 	=> 'AdminMenu',
	"method" 		=> "index",
	"bind"			=> 'admin.menu.index'
);

$routes[] = array(
	"route" 		=> '/admin/menu/save',
	'type' 			=> 'post',
	"controller" 	=> 'AdminMenu',
	"method" 		=> "save",
	"bind"			=> 'admin.menu.save'
);
//--------------------------------------------------------------//
?>