@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Your Complaints') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(count($complaints))
                        <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Created AT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($complaints as $complaint)
                                <tr>
                                    <td>{{ $complaint->id }}</td>
                                    <td>{{ $complaint->title }}</td>
                                    <td>{{ $complaint->description }}</td>
                                    <td>{{ $complaint->status }}</td>
                                    <td>{{ $complaint->created_at->format('F j, Y, g:i a') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $complaints->links() !!}
                    @else
                        <p class="text-center">No Complaint to show yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
