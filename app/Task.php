<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RecordsActivity;

class Task extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean',
    ];

    protected static $recordableEvents = ['created', 'deleted'];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/$this->id";
    }

    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('uncompleted_task');
    }
}
