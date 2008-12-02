
CREATE TABLE `auth` (
		`id` int(11) NOT NULL auto_increment,
		`hash` varchar(255) NOT NULL,
		`cookie` text NOT NULL,
		`email` varchar(255) NOT NULL default '',
		`password` varchar(255) NOT NULL,
		PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1144 DEFAULT CHARSET=utf8;


CREATE TABLE `reblogs` (
		`id` int(11) NOT NULL auto_increment,
		`uniqkey` varchar(255) default NULL,
		`postid` int(10) unsigned default NULL,
		`status` varchar(64) default 'waiting',
		`created_at` datetime default NULL,
		`code` int(11) default NULL,
		`complete_at` datetime default NULL,
		`sessionkey` varchar(255) default NULL,
		`permalink` text,
		`token` varchar(32) default NULL,
		PRIMARY KEY  (`id`),
		UNIQUE KEY `u_uniqkey` (`uniqkey`),
		KEY `i_sessionkey` (`sessionkey`)
	) ENGINE=MyISAM AUTO_INCREMENT=10329 DEFAULT CHARSET=utf8;

