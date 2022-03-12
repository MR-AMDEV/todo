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
        <div class="d-flex justify-content-between mb-3">
            <div>
                <button class="btn-toggleSelect btn btn-sm btn-outline-primary me-1"><i class="fas fa-check"></i>
                </button>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#create-modal">
                    <i class="fas fa-plus-circle me-1"></i> Create Todo Task
                </button>
            </div>
            <button class="btn-deleteSelected btn btn-sm btn-danger">Delete Selected</button>
        </div>

        <ul id="todo-items" class="list-group">
            @if(isset($todos) && $todos->isNotEmpty())
                @foreach($todos as $todo)
                    <li id="{{ $todo->id }}" class="list-group-item d-flex align-items-center">
                        <label class="d-flex align-items-center pointer">
                            <input class="form-check-input me-3" type="checkbox">
                            <div>
                                <div class="task fw-bold">{{ $todo->task }}</div>
                                <div class="deadline text-muted small">Deadline: {{ Carbon\Carbon::parse($todo->deadline)->format('g:i A, d M Y') }}</div>
                            </div>
                        </label>
                        <div class="ms-auto">
                            <button class="btn btn-sm btn-outline-primary px-3 me-1" data-bs-toggle="modal"
                                    data-bs-target="#edit-modal"><i class="fas fa-pencil fa-sm"></i></button>
                            <button class="btn-delete btn btn-sm btn-outline-danger px-3"><i class="fas fa-times"></i>
                            </button>
                        </div>
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

    <div id="create-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <form id="create-modal-form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <fieldset class="mb-3">
                        <label class="form-control-label">Task</label>
                        <input type="text" class="form-control" name="task" placeholder="Enter task title" required>
                    </fieldset>
                    <fieldset>
                        <label class="form-control-label">Deadline</label>
                        <input type="datetime-local" class="form-control" name="deadline"
                               placeholder="Select a datetime" required>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <form id="edit-modal-form" class="modal-content">
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <fieldset class="mb-3">
                        <label class="form-control-label">Task</label>
                        <input type="text" class="form-control" name="task" placeholder="Enter task title" required>
                    </fieldset>
                    <fieldset>
                        <label class="form-control-label">Deadline</label>
                        <input type="datetime-local" class="form-control" name="deadline"
                               placeholder="Select a datetime" required>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page_js')
    <script>
        // Toolbar actions
        $('.btn-toggleSelect').on('click', (e) => {
            const btn = $(e.currentTarget);
            const checkboxes = $('.form-check-input');

            btn.toggleClass('active');
            checkboxes.prop('checked', !checkboxes.prop('checked'));
        });
        $('.btn-deleteSelected').on('click', (e) => {
            const btn = $(e.currentTarget);
            const checkboxes = $('.form-check-input:checked');

            if (checkboxes.length) {
                if (confirm('Are you sure you wanna do this?')) {
                    const ids = checkboxes.map((i, checkbox) => { return $(checkbox).parents('li:first').attr('id'); }).get();

                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        method: 'POST',
                        dataType: 'json',
                        url: '{{ route('admin.dashboard.bulk-destroy') }}',
                        data: { ids: ids},
                        beforeSend: () => {
                            btn.addClass('disabled').prop('disabled', true);
                        },
                        success: (response) => {
                            btn.removeClass('disabled').prop('disabled', false);

                            checkboxes.map((i, checkbox) => {
                                $(checkbox).parents('li:first').hide('slow');
                                $(checkbox).parents('li:first').remove();
                            });

                            if(!$('#todo-items').children('li').length){
                                const emptyIndicator =
                                    `<li class="task-empty list-group-item text-muted text-center">
                                        <i class="far fa-frown me-1"></i> No task found!
                                    </li>`;

                                $('#todo-items').append(emptyIndicator);
                            }
                        },
                    });
                    console.log(checkboxes, ids);
                }
            }
        });

        // C - Create
        $('#create-modal-form').on('submit', (e) => {
            const modal = $('#create-modal');
            const form = modal.find('form');

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: 'POST',
                dataType: 'json',
                url: '{{ route('admin.dashboard.store') }}',
                data: form.serializeArray(),
                beforeSend: () => {
                    form.find('button:submit').addClass('disabled').prop('disabled', true);
                },
                success: (response) => {
                    form.find('button:submit').removeClass('disabled').prop('disabled', false);

                    const li =
                        `<li id="${response.data.id}" class="list-group-item d-flex align-items-center">
                            <label class="d-flex align-items-center pointer">
                                <input class="form-check-input me-3" type="checkbox">
                                <div>
                                    <div class="task fw-bold">${response.data.task}</div>
                                    <div class="deadline text-muted small">Deadline: ${moment(response.data.deadline).format('h:mm A, DD MMM Y')}</div>
                                </div>
                            </label>
                            <div class="ms-auto">
                                <button class="btn btn-sm btn-outline-primary px-3 me-1" data-bs-toggle="modal" data-bs-target="#edit-modal"><i class="fas fa-pencil fa-sm"></i></button>
                                <button class="btn-delete btn btn-sm btn-outline-danger px-3"><i class="fas fa-times"></i></button>
                            </div>
                        </li>`;

                    $('.task-empty').remove();
                    $('#todo-items').prepend(li);
                    form.find('input').val(null);
                    modal.modal('hide');
                },
            });

            return false;
        });

        // R - Read
        $('#edit-modal').on('show.bs.modal', (e) => {
            const btn = $(e.relatedTarget);
            const modal = $('#edit-modal');
            const form = modal.find('form');
            const id = btn.parents('li:first').attr('id');

            $.ajax({
                method: 'GET',
                dataType: 'json',
                url: '{{ route('admin.dashboard.show', 'id') }}'.replace('id', id),
                success: (response) => {
                    form.attr('data-id', id);
                    form.find('[name="task"]').val(response.data.task);
                    form.find('[name="deadline"]').val(response.data.deadline);
                },
            });
        });

        // U - Update
        $('#edit-modal-form').on('submit', (e) => {
            const modal = $('#edit-modal');
            const form = modal.find('form');
            const id = form.attr('data-id');
            const data = form.serializeArray();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: 'POST',
                dataType: 'json',
                url: '{{ route('admin.dashboard.update', 'id') }}'.replace('id', id),
                data: data,
                beforeSend: () => {
                    form.find('button:submit').addClass('disabled').prop('disabled', true);
                },
                success: (response) => {
                    form.find('button:submit').removeClass('disabled').prop('disabled', false);

                    console.log(data);
                    const li = $('#todo-items').find('li#' + id);

                    li.find('.task').text(data[1].value);
                    li.find('.deadline').text('Deadline: ' + moment(data[2].value).format('h:mm A, DD MMM Y'));

                    form.find('input:not(:hidden)').val(null);
                    modal.modal('hide');
                },
            });

            return false;
        });

        // D - Delete
        $(document).on('click', '.btn-delete', (e) => {
            const btn = $(e.currentTarget);
            const li = btn.parents('li:first');
            const id = li.attr('id');

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: 'POST',
                dataType: 'json',
                url: '{{ route('admin.dashboard.destroy', 'id') }}'.replace('id', id),
                data: { _method: 'DELETE' },
                beforeSend: () => {
                    btn.addClass('disabled').prop('disabled', true);
                },
                success: (response) => {
                    btn.removeClass('disabled').prop('disabled', false);

                    li.hide('slow');
                    li.remove();

                    if(!$('#todo-items').children('li').length){
                        const emptyIndicator =
                                    `<li class="task-empty list-group-item text-muted text-center">
                                        <i class="far fa-frown me-1"></i> No task found!
                                    </li>`;

                        $('#todo-items').append(emptyIndicator);
                    }
                },
            });
        });
    </script>
@endsection
