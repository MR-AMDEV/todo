@extends('layouts.master.app')

@section('page_css')
    <style>
        .pointer {
            cursor: pointer;
        }

        .form-check-input {
            margin-top: 1px;
        }
    </style>
@endsection

@section('content')
    <section
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </section>

    <section>
        <ul id="todo-items" class="list-group">
            @if(isset($todos) && $todos->isNotEmpty())
                @foreach($todos as $todo)
                    <li id="{{ $todo->id }}" class="list-group-item">
                        <div class="task fw-bold">{{ $todo->task }}</div>
                        <div class="deadline text-muted small">Deadline: {{ Carbon\Carbon::parse($todo->deadline)->format('g:i A, d M Y') }}</div>
                    </li>
                @endforeach
            @else
                <li class="task-empty list-group-item text-muted text-center">
                    <i class="far fa-frown me-1"></i> No task found!
                </li>
            @endif
        </ul>

        @if(isset($todos) && $todos->isNotEmpty())
            {{ $todos->links() }}
        @endif
    </section>
@endsection

@section('page_js')
@endsection
