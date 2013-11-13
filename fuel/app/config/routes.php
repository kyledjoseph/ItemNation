<?php
return array(
	'_root_'                              => 'home/index',
	'_404_'                               => 'error/404',

	'bookmark'                            => 'bookmark/view',

	'login/(:any)'                        => 'user/auth/login/$1',
	'authenticate/(:any)'                 => 'user/auth/authenticate/$1',
	'logout'                              => 'user/auth/logout',
	'authenticate'                        => 'user/auth/authenticate',
	// 'register'                         => 'user/register',
	// 'forgot'                           => 'user/forgot',
	// 'reset/(:segment)'                 => 'user/reset/$1',

	'try'                                 => 'home/try',
	'account/(:any)'                      => 'user/$1',
	'account'                             => 'user/account',

	'quest/create'                        => 'quests/create',
	'quest/(:segment)/remove/(:segment)'  => 'quests/remove/$1/$2',
	'quest/(:segment)/like/(:segment)'    => 'quests/like/$1/$2',
	'quest/(:segment)/dislike/(:segment)' => 'quests/dislike/$1/$2',
	'quest/(:segment)/comment/(:segment)' => 'quests/comment/$1/$2',
	'quest/(:segment)/access/(:segment)'  => 'quests/access/$1/$2',
	'quest/(:segment)/message'            => 'quests/message/$1',
	'quest/(:segment)/within'             => 'quests/within/$1',
	'quest/(:segment)/invite/email'       => 'quests/invite_email/$1',
	'quest/(:segment)/invite/friends'     => 'quests/invite_friends/$1',
	'quest/(:segment)/edit'               => 'quests/edit/$1',
	'quest/(:segment)/delete'             => 'quests/delete/$1',
	'quest/(:segment)'                    => 'quests/view/$1',
	'quest'                               => 'quests/index',

	'privacy' => 'legal/privacy',
	'terms'   => 'legal/terms',
);