@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-6">


@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    </div>
</div>
@endsection