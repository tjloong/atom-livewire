<?php

namespace Jiannius\Atom\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
	protected $guarded = [];

	/**
	 * Check enabled for user
	 * 
	 * @return boolean
	 */
	public function isEnabledForUser($user)
	{
		return $user->can($this->module . '.' . $this->name);
	}
}
