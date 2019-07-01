<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use Tests\Setup\ProjectFactory;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => ''])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);
        
        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        // using real time facades
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        
        $this->patch($project->tasks->first()->path(), [
            'body' => 'Updated task body',
        ]);

        $this->assertDatabaseHas('tasks', ['body' => 'Updated task body']);
    }

    /** @test */
    public function a_task_can_be_completed()
    {
        // using real time facades
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        
        $this->patch($project->tasks->first()->path(), [
            'completed' => true,
            'body' => 'Updated',
        ]);

        $this->assertDatabaseHas('tasks', ['completed' => true]);
    }

    /** @test */
    public function a_task_can_be_marked_as_incomplete()
    {
        // using real time facades
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();
        
        $this->patch($project->tasks->first()->path(), [
            'completed' => true,
            'body' => 'Updated',
        ]);

        $this->patch($project->tasks->first()->path(), [
            'completed' => false,
            'body' => 'Updated',
        ]);

        $this->assertDatabaseHas('tasks', ['completed' => false]);
    }


    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks->first()->path(), [
            'body' => 'changed task text',
            'completed' => true,
        ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed task text', 'completed' => true]);
    }
}
