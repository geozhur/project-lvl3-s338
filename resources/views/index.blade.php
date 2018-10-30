@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-6">
        <form action="/domain" method="post">
        <div class="input-group">
            <input name="name" type="text" class="form-control" placeholder="Test site" aria-label="Test term" aria-describedby="basic-addon">
            <div class="input-group-append">
                <button class="btn btn-secondary" type="submit">Test</button>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection
