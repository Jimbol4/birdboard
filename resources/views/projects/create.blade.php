@extends('layouts.app')
@section('content')
    <h1>Create New Project</h1>
    <form action="/projects" method="POST">
    @include('projects.form', ['project' => new App\Project, 'buttonText' => 'Create Project'])
@endsection