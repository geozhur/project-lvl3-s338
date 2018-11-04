<div class="row justify-content-center">
    <div class="col-6">
        <form action="{{ route('domains.store') }}" method="post">
            <div class="input-group">
                <input type="hidden" name="_token" value="{{ app('session')->token() }}">
                <input name="name" type="text" class="form-control" placeholder="Test site" aria-label="Test term"
                    aria-describedby="basic-addon">
                <div class="input-group-append">
                    <button class="btn btn-secondary" type="submit">Test</button>
                </div>
            </div>
        </form>
        @if(app('request')->session()->has('error'))
        <div class="mt-3">
            <p class="alert alert-danger">{{ app('request')->session()->get('error') }}</p>
        </div>
        @endif
    </div>
</div>