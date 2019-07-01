
        <div class="form-group">
            <label class="label" for="title">Title</label>
            <input type="text" class="input form-control" name="title" placeholder="Title" value="{{ $project->title }}" required>
        </div>

        <div class="form-group">
            <label class="label" for="description">Description</label>
            <textarea class="input form-control" name="description" required>{{ $project->description }}</textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
            <a href="{{ $project->path() }}">Cancel</a>
        </div>

        @csrf
        @if ($errors->any())
        <div class="field">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </form>
