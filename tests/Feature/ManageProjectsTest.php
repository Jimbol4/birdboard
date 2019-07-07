<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->signIn();
        $this->get('/projects/create')->assertStatus(200);
        $this->followingRedirects()
            ->post('/projects', $attributes = factory(Project::class)->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->patch($project->path(), ['title' => 'Changed title', 'description' => 'Updated description'])
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', ['title' => 'Changed title', 'description' => 'Updated description']);

        $this->get($project->path())
            ->assertSee('Changed title')
            ->assertSee('Updated description');
    }

    /** @test */
    public function a_user_can_update_a_projects_general_notes()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->patch($project->path(), ['notes' => 'Updated notes for project'])
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', ['notes' => 'Updated notes for project']);

        $this->get($project->path())
            ->assertSee('Updated notes for project');
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->be(factory('App\User')->create());

        $this->post('/projects', factory('App\Project')->raw(['title' => '']))
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function guests_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();

        $this->post('/projects', $project->toArray())
            ->assertRedirect('login');

        $this->get('/projects')
            ->assertRedirect('login');

        $this->get('/projects/create')
            ->assertRedirect('login');

        $this->get($project->path() . '/edit')
            ->assertRedirect('login');

        $this->get($project->path())
            ->assertRedirect('login');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();
        $this->post('/projects', factory('App\Project')->raw(['description' => '']))
            ->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(str_limit($project->description, 100));
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $project = tap(ProjectFactory::create())->invite($this->signIn());

        $this->get('/projects')
            ->assertSee($project->title);
    }

    /** @test */
    public function a_user_cannot_view_other_projects()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_cannot_update_other_projects()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path(), ['notes' => 'I should not be able to update this'])
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function users_cannot_delete_other_projects()
    {
        $this->signIn();
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_delete_other_projects()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $this->assertDatabaseHas('projects', $project->only('id'));
    }

    /** @test */
    public function invited_users_cannot_delete_other_projects()
    {
        $user = $this->signIn();
        $project = ProjectFactory::create();

        $project->invite($user);

        $this->delete($project->path())
            ->assertStatus(403);
    }
}
