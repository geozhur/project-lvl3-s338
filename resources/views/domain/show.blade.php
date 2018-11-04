@extends('layouts.master')

@section('content')
@include('layouts.form')
<div class="row justify-content-center mt-3">
    <div class="col-10">
        <h2>Domain analyzer</h2>
        <table class="table table-hover ">
        <tbody>
        <tr>
            <td class="w-25">Id</th><td>{{ $domain->id }}</td>
        </tr> 
        <tr >
            <td>Url</td><td>{{ $domain->name }}</td>
        </tr> 
        <tr>
            <td>Content-length</td><td id="content_length">{{ $domain->content_length }}</td>
        </tr>  
        <tr>
            <td>Status Code</td><td id="status_code">{{ $domain->status_code }}</td>
        </tr>
        <tr>
            <td>H1</td><td id="h1">{{ $domain->h1 }}</td>
        </tr>  
        <tr>
            <td>Keywords</td><td id="keywords">{{ $domain->keywords }}</td>
        </tr> 
        <tr>
            <td>Description</td><td id="description">{{ $domain->description }}</td>
        </tr> 
        <tr>
            <td>Crate Date</td><td>{{ $domain->created_at }}</td>
        </tr>      
        </tbody>
        </table>
    </div>
</div>
<script>  
        function show()  
        {  
            $.ajax({  
                type: 'get',
                url: "{{ route('domains.json', ['id' => $domain->id]) }}",
                dataType: 'json',  
                cache: false,  
                success: function(data){  
                    $("#status_code").text(data.status_code); 
                    $("#content_length").text(data.content_length); 
                    $("#h1").text(data.h1); 
                    $("#keywords").text(data.keywords); 
                    $("#description").text(data.description); 
                }  
            });  
        }  
        show();  
        var tt = setInterval(function(){ show() }, 1000);
        setTimeout(function() { clearInterval(tt); }, 15000);
    </script> 
@endsection