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
        return $user->is($project->owner) || $project->members->contains($user);
    }

    public function show(User $user, Project $project)
    {
        return $user->is($project->owner) || $project->members->contains($user);
    }

    public function delete(User $user, Project $project)
    {
        return $user->is($project->owner);
    }

    public function owner(User $user, Project $project)
    {
        return $user->is($project->owner);
    }

    public function manage(User $user, Project $project)
    {
        return $user->is($project->owner);
    }
}
