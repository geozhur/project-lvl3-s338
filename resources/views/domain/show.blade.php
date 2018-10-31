@extends('layouts.master')

@section('content')
@include('layouts.form')
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