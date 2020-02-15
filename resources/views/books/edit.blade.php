@extends('template')


@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ __('Edit Book') }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
                    @method('PATCH')
                    @csrf                                
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                        <div class="col-md-6">
                            <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ $book->title }}" required autofocus>
                            @if ($errors->has('title'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="body" class="col-md-4 col-form-label text-md-right">{{ __('description') }}</label>
                        <div class="col-md-6">
                            <textarea id="description" rows="3" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" autofocus>{{ $book->description }}</textarea>
                            @if ($errors->has('description'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Author') }}</label>
                        <div class="col-md-6">
                            <input id="author" type="text" class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }}" name="author" value="{{ $book->author }}" required autofocus>
                            @if ($errors->has('author'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('author') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Release Year') }}</label>
                        <div class="col-md-6">
                            <input id="release_year" type="number" min="0" class="form-control{{ $errors->has('release_year') ? ' is-invalid' : '' }}" name="release_year" value="{{ $book->release_year }}" required autofocus>
                            @if ($errors->has('release_year'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('release_year') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Cover') }}</label>
                        <div class="col-md-6">
                            <img src="{{$book->cover_url}}" height="300px" width="300px" class="mx-auto rounded d-block">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Upload New Cover') }}</label>
                        <div class="col-md-6">
                            <input id="cover" type="file" name="cover">
                            @if ($errors->has('cover'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('cover') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection