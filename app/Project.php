<?php

namespace App;

use App\Activity;
use Illuminate\Database\Eloquent\Model;
use App\RecordsActivity;
use App\User;

class Project extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $with = ['tasks'];

    public function path()
    {
        return "/projects/{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }
}
