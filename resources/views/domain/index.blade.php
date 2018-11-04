@extends('layouts.master')

@section('content')
@include('layouts.form')
<div class="row justify-content-center mt-3">
    <div class="col-10">
        <h2>Domain list</h2>
        <p>Make your pages faster.</p>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Url</th>
                    <th>Content-length</th>
                    <th>Status Code</th>
                    <th>Crate Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($domains as $domain)
                <tr>
                    <td><a href="{{ route('domains.show', ['id' => $domain->id]) }}">{{ $domain->id }}</a></td>
                    <td><a href="{{ route('domains.show', ['id' => $domain->id]) }}">{{ $domain->name }}</a></td>
                    <th>{{ $domain->content_length }}</th>
                    <th>{{ $domain->status_code }}</th>
                    <td>{{ $domain->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row justify-content-center mt-3">
            {{ $domains->links() }}
        </div>
    </div>
</div>
@endsection