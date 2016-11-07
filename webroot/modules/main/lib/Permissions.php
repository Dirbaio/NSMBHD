<?php

class Permissions
{
	public static function canDoStuff($user = null)
	{
		if(!Session::isLoggedIn())
			return false;

		if($user === null) $user = Session::get();
		return $user['powerlevel'] >= 0;
	}

	public static function assertCanDoStuff($user = null)
	{
		if(!self::canDoStuff($user))
			fail(__('You are not allowed to do this.'));
	}


	public static function canViewForum($forum, $user = null)
	{
		if(!Session::isLoggedIn())
			return $forum['minpower'] <= 0;

		if($user === null) $user = Session::get();

		return $forum['minpower'] <= $user['powerlevel'];
	}

	public static function assertCanViewForum($forum, $user = null)
	{
		if(!self::canViewForum($forum, $user))
			fail(__('You are not allowed to browse this forum.'));
	}


	public static function canCreateThread($forum, $user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();

		return $forum['minpowerthread'] <= $user['powerlevel'];
	}

	public static function assertCanCreateThread($forum, $user = null)
	{
		if(!self::canCreateThread($forum, $user))
			fail(__('You are not allowed to create threads in this forum.'));
	}

	
	public static function canMod($forum, $user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();

		return $user['powerlevel'] >= 2;
	}

	public static function assertCanMod($forum, $user = null)
	{
		if(!self::canMod($forum, $user))
			fail(__('You are not allowed to moderate this forum.'));
	}


	public static function canReply($thread, $forum, $user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();

		if($thread['forum'] != $forum['id'])
			throw new Exception('You must pass a thread and its forum to canReply');

		return $forum['minpowerreply'] <= $user['powerlevel'] && 
			(!$thread['closed'] || self::canMod($forum, $user));
	}

	public static function assertCanReply($thread, $forum, $user = null)
	{
		if(!self::canReply($thread, $forum, $user))
			fail(__('You are not allowed to reply in this thread.'));
	}

	
	public static function canEditThread($thread, $forum, $user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();

		if($thread['forum'] != $forum['id'])
			throw new Exception('You must pass a thread and its forum to canReply');

		return self::canMod($forum, $user) || 
			($thread['user'] == $user['id'] && self::canReply($thread, $forum, $user));
	}

	public static function assertCanEditThread($thread, $forum, $user = null)
	{
		if(!self::canEditThread($thread, $forum, $user))
			fail(__('You are not allowed to edit this thread.'));
	}
	

	public static function canEditPost($post, $thread, $forum, $user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();

		if($post['thread'] != $thread['id'])
			throw new Exception('You must pass a post and its thread to canReply');
		if($thread['forum'] != $forum['id'])
			throw new Exception('You must pass a thread and its forum to canReply');

		return self::canMod($forum, $user) || 
			($post['user'] == $user['id'] && self::canReply($thread, $forum, $user) && !$post['deleted']);
	}

	public static function assertCanEditPost($post, $thread, $forum, $user = null)
	{
		if(!self::canEditPost($post, $thread, $forum, $user))
			fail(__('You are not allowed to edit this post.'));
	}

	
	public static function canDeletePost($post, $thread, $forum, $user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();

		if($post['thread'] != $thread['id'])
			throw new Exception('You must pass a post and its thread to canReply');
		if($thread['forum'] != $forum['id'])
			throw new Exception('You must pass a thread and its forum to canReply');

		return self::canMod($forum, $user);
	}

	public static function assertCanDeletePost($post, $thread, $forum, $user = null)
	{
		if(!self::canDeletePost($post, $thread, $forum, $user))
			fail(__('You are not allowed to delete this post.'));
	}


	
	public static function canEditUser($victim, $user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();

		return 
			($victim['id'] == $user['id'] && $user['powerlevel'] >= 0) || 
			$user['powerlevel'] >= 3;
	}

	public static function assertCanEditUser($victim, $user = null)
	{
		if(!self::canEditUser($victim, $user))
			fail(__('You are not allowed to edit this user.'));
	}

	
	public static function canSnoopMessages($user = null)
	{
		if(!Session::isLoggedIn()) return false;
		if($user === null) $user = Session::get();
		return $user['powerlevel'] >= 3;
	}

	public static function assertCanSnoopMessages($user = null)
	{
		if(!self::canSnoopMessages($user))
			fail(__('You are not allowed to read other users\' messages.'));
	}
}