
CREATE TABLE IF NOT EXISTS {redirectsTable} (
    `id` bigint(30) NOT NULL auto_increment,
    `url` varchar(512) NOT NULL,
    `status` bigint(20) NOT NULL,
    `type` bigint(20) NOT NULL,
    `final_dest` varchar(512) NOT NULL,
    `code` bigint(20) NOT NULL,
    `disabled` int(10) NOT NULL default '0',
    `timestamp` bigint(30) NOT NULL,
    PRIMARY KEY  (`id`),
    KEY `status` (`status`),
    KEY `type` (`type`),
    KEY `code` (`code`),
    KEY `timestamp` (`timestamp`),
    KEY `disabled` (`disabled`),
    KEY `url` (`url`) USING BTREE,
    KEY `final_dest` (`final_dest`) USING BTREE
) ENGINE=MyISAM character set utf8 COMMENT='404 Solution Plugin Redirects Table' AUTO_INCREMENT=1
