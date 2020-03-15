<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = factory('App\Task')->create();

        $this->assertInstanceOf('App\Project', $task->project);
    }

    /** @test */
    public function it_has_a_path()
    {
        $task = factory('App\Task')->create();

        $this->assertEquals($task->project->path() . '/tasks/' . $task->id, $task->path());
    }

    /** @test */
    public function it_can_be_completed()
    {
        $task = factory(Task::class)->create();
    
        $this->assertFalse($task->fresh()->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }

    /** @test */
    public function it_can_be_marked_as_incomplete()
    {
        $task = factory(Task::class)->create(['completed' => true]);
    
        $this->assertTrue($task->fresh()->completed);

        $task->incomplete();

        $this->assertFalse($task->fresh()->completed);
    }
}