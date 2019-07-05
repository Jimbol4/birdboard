<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectsPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Project $project)
    {
        return $user->is($project->owner);
    }

    public function show(User $user, Project $project)
    {
        return $user->is($project->owner);
    }

    public function delete(User $user, Project $project)
    {
        return $user->is($project->owner);
    }
}
