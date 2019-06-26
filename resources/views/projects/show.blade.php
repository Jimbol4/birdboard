@extends('layouts.app')
@section('content')

    <header class="flex items-center mb-3 py-4">
            <div class="flex justify-between w-full items-center">
                <p class="text-gray-500 text-sm font-normal">
                    <a href="/projects">My projects</a> / {{ $project->title }}
                </p>
                <a href="/projects/create" class="button">Add Project</a>
            </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-500 text-lg font-normal mb-3">Tasks</h2>
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">{{ $task->body }}</div>
                    @endforeach
                </div>
                {{-- tasks --}}

                <div>
                    <h2 class="text-lg text-gray-500 font-normal mb-3">General Notes</h2>
                    {{-- general notes --}}
                    <textarea class="card w-full" style="min-height: 200px;">Lorem ipsum.</textarea>
                </div>
            </div>
                <div class="lg:w-1/4 px-3 pb-6">
                    @include('projects.card')
                </div>
            </div>
        </div>
    </main>

    
@endsection