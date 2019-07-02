@extends('layouts.app')
@section('content')

    <header class="flex items-center mb-3 py-4">
            <div class="flex justify-between w-full items-center">
                <p class="text-gray-500 text-sm font-normal">
                    <a href="/projects">My projects</a> / {{ $project->title }}
                </p>
                <a href="{{ $project->path() . '/edit' }}" class="button">Edit Project</a>
            </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-500 text-lg font-normal mb-3">Tasks</h2>
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form method="POST" action="{{ $task->path() }}">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input name="body" value="{{ $task->body }}" class="w-full {{ $task->completed ? 'text-gray-500' : '' }}" />
                                    <input type="checkbox" name="completed" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}/>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3">
                        <form action="{{ $project->path() . '/tasks' }}" method="POST">
                            @csrf
                            <input name="body" placeholder="Add a new task..." class="w-full"/>
                        </form>
                    </div>
                </div>
                {{-- tasks --}}

                <div>
                    <h2 class="text-lg text-gray-500 font-normal mb-3">General Notes</h2>
                    {{-- general notes --}}
                    <form action="{{ $project->path() }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <textarea class="card w-full mb-4" style="min-height: 200px;" name="notes" placeholder="Anything special that you want to make a note of?">{{ $project->notes }}</textarea>

                        <button type="submit" class="button">Save</button>
                    </form>

                    @if ($errors->any())
                    <div class="field">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
                <div class="lg:w-1/4 px-3 pb-6">
                    @include('projects.card')
                </div>

                @include('projects.activity.card')
            </div>
        </div>
    </main>

    
@endsection