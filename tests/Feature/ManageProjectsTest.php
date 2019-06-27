<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General note here.',
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();
        
        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->user()->id]);

        $this->patch($project->path(), ['notes' => 'Updated notes for project'])
            ->assertRedirect($project->path());

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
}
