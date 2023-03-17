@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create Link') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('link.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('Token') }}</label>

                                <div class="col-md-6">
                                    <input id="token" type="text" class="form-control" name="token" value="{{ $token }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="max_clicks" class="col-md-4 col-form-label text-md-right">{{ __('Max Clicks') }}</label>

                                <div class="col-md-6">
                                    <input id="max_clicks" type="number" class="form-control @error('max_clicks') is-invalid @enderror" name="max_clicks" value="0" min="0" max="999999">

                                    @error('max_clicks')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="expires_in" class="col-md-4 col-form-label text-md-right">{{ __('Expires In (minutes)') }}</label>

                                <div class="col-md-6">
                                    <input id="expires_in" type="number" class="form-control @error('expires_in') is-invalid @enderror" name="expires_in" value="0" min="0" max="1440">

                                    @error('expires_in')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
