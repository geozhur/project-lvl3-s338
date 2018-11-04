@extends('layouts.master')

@section('content')
@include('layouts.form')
<div class="row justify-content-center mt-3">
    <div class="col-10">
        <h2>Domain analyzer</h2>
        <table class="table table-hover ">
            <tbody>
                <tr>
                    <td class="w-25">Id</th>
                    <td>{{ $domain->id }}</td>
                </tr>
                <tr>
                    <td>Url</td>
                    <td>{{ $domain->name }}</td>
                </tr>
                <tr>
                    <td>Content-length</td>
                    <td id="content_length" class="blink">{{ $domain->content_length }}</td>
                </tr>
                <tr>
                    <td>Status Code</td>
                    <td id="status_code" class="blink">{{ $domain->status_code }}</td>
                </tr>
                <tr>
                    <td>H1</td>
                    <td id="h1" class="blink">{{ $domain->h1 }}</td>
                </tr>
                <tr>
                    <td>Keywords</td>
                    <td id="keywords" class="blink">{{ $domain->keywords }}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td id="description" class="blink">{{ $domain->description }}</td>
                </tr>
                <tr>
                    <td>Crate Date</td>
                    <td>{{ $domain->created_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    var obj = {
        status_code: '#status_code',
        content_length: '#content_length',
        h1: '#h1',
        keywords: '#keywords',
        description: '#description'
    };
    var longTime = 15000;
    if ($('#status_code').text() !== 'loading...') {
        $.each(obj, function (index, value) {
            $(value).removeClass();
        });
        longTime = 1000;
    }

    function show() {
        $.ajax({
            type: 'get',
            url: "{{ route('domains.json', ['id' => $domain->id]) }}",
            dataType: 'json',
            cache: false,
            success: function (data) {
                $.each(obj, function (index, value) {
                    $(value).text(data[index]);
                });
            }
        });
    }
    show();
    var tt = setInterval(function () {
        show();
        if ($('#status_code').text() !== 'loading...') {
            $.each(obj, function (index, value) {
                $(value).removeClass();
            });
            clearInterval(tt);
        }
    }, 3000);

    setTimeout(function () {
        clearInterval(tt);
    }, longTime);
</script>
@endsection
