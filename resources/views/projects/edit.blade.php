@extends('layouts.app')
@section('content')
    <h1>Edit Your Project</h1>
    <form action="{{ $project->path() }}" method="POST">
    @method('PATCH')
    @include('projects.form', ['buttonText' => 'Update Project'])
@endsection