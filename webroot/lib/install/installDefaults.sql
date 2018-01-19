INSERT INTO `{$dbpref}categories` VALUES(1, 'Staff', 0);
INSERT INTO `{$dbpref}categories` VALUES(2, 'General', 1);
INSERT INTO `{$dbpref}categories` VALUES(3, 'Janitorial Services', 2);

INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`, `minpower`, `minpowerthread`, `minpowerreply`) VALUES(1, 'Admin room', 'Staff discussion forum', 1, 1, 1, 1);
INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`) VALUES(2, 'General chat', 'Talk about serious stuff', 2);
INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`) VALUES(3, 'Off-Topic', 'Talk about other stuff', 2);
INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`, `minpowerthread`, `minpowerreply`) VALUES(4, 'Trash', 'Trashed threads go here.', 3, 3, 3);
INSERT INTO `{$dbpref}forums` (`id`, `title`, `description`, `catid`, `minpower`, `minpowerthread`, `minpowerreply`) VALUES(5, 'Hidden trash', 'Only visible to admins. Deleted threads go here.', 3, 3, 3, 3);

INSERT INTO `{$dbpref}settings` (`plugin`, `name`, `value`) VALUES('main', 'trashForum', 4);
INSERT INTO `{$dbpref}settings` (`plugin`, `name`, `value`) VALUES('main', 'hiddenTrashForum', 5);

