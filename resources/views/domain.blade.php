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
<div class="row justify-content-center mt-3">
    <div class="col-6">
         <h2>Page speed optimization</h2>
        <p>Make your pages faster.</p>
        <table class="table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Domain</th>
            <th>Crate Date</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{ $domain->id }}</td>
            <td>{{ $domain->name }}</td>
            <td>{{ $domain->created_at }}</td>
        </tr>      
        </tbody>
        </table>
    </div>
</div>
@endsection