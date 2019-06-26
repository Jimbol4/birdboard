@extends('layouts.app')
@section('content')
    <h1>Create a Project</h1>
    <form action="/projects" method="POST">

        <div class="form-group">
            <label class="label" for="title">Title</label>
            <input type="text" class="input form-control" name="title" placeholder="Title">
        </div>

        <div class="form-group">
            <label class="label" for="description">Description</label>
            <textarea class="input form-control" name="description"></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Create Project</button>
            <a href="/projects">Cancel</a>
        </div>

        @csrf
    </form>
@endsection