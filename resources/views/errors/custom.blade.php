@extends('layout.app')

@section('content')
<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Error</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h4>Terjadi Kesalahan</h4>
                        <p>{{ $message }}</p>
                        @if(config('app.debug'))
                        <hr>
                        <pre>{{ $trace }}</pre>
                        @endif
                    </div>
                    <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection