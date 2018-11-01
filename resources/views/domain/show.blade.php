@extends('layouts.master')

@section('content')
@include('layouts.form')
<div class="row justify-content-center mt-3">
    <div class="col-10">
        <h2>Domain analyzer</h2>
        <table class="table table-hover">
        <tbody>
        <tr>
            <td class="w-25">Id</th><td>{{ $domain->id }}</td>
        </tr> 
        <tr >
            <td>Url</td><td>{{ $domain->name }}</td>
        </tr> 
        <tr>
            <td>Content-length</td><td>{{ $domain->content_length }}</td>
        </tr>  
        <tr>
            <td>Status Code</td><td>{{ $domain->status_code }}</td>
        </tr>
        <tr>
            <td>H1</td><td>{{ $domain->h1 }}</td>
        </tr>  
        <tr>
            <td>Keywords</td><td>{{ $domain->keywords }}</td>
        </tr> 
        <tr>
            <td>Description</td><td>{{ $domain->description }}</td>
        </tr> 
        <tr>
            <td>Crate Date</td><td>{{ $domain->created_at }}</td>
        </tr>      
        </tbody>
        </table>
    </div>
</div>
@endsection