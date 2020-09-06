<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tasks List</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
    <div class="flex-top">
        <div class="main-panel-body">

            <!-- New Task Form -->
            <form action="{{ url('/') }}" method="POST" class="form-horizontal">
                {{ csrf_field() }}
                <!-- Task Name -->
                <div class="form-group">
                    <label for="task" class="col-sm-3 control-label">New Task Name</label>
                    <div class="col-sm-6">
                        <input type="text" name="name" id="task-name" class="form-control">
                    </div>
                    <!-- Add Task Button -->
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-primary" style="float:right;">
                            Add New Task
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tasks List -->
            @if (count($tasks) > 0)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>Tasks List</b>
                </div>
                <div class="panel-body box">
                    @if (count($tasks) > 0)
                    <ul class="list-unstyled" id="task_list">
                        @foreach ($tasks as $task)
                        <li id='{{$task->id}}'>
                            <!-- Task Show Mode -->
                            <div class="task-form-show-mode" data-task-id="{{ $task->id }}">
                                <div class="col-sm-9 task-name-show">
                                    <div>{{ $task->name }}</div>
                                </div>
                                <!-- Task Edit Button -->
                                <div class="col-sm-2">
                                    <button class="btn btn-success edit-mode-button" data-task-id="{{ $task->id }}">
                                        Edit
                                    </button>
                                </div>
                            </div>
                            <!-- Task Edit Mode -->
                            <div class="task-form-edit-mode" data-task-id="{{ $task->id }}" hidden>
                                <form action="{{ url('/'.$task->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <div class="col-sm-9 task-name">
                                        <input type="text" name="id" id="task-id" value="{{ $task->id }}" hidden>
                                        <input type="text" name="name" id="task-name" class="form-control task-name-edit" value="{{ $task->name }}">
                                    </div>
                                    <!-- Task Edit Button -->
                                    <div class="col-sm-2">
                                        <button class="btn btn-success">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- Task Delete Button -->
                            <div class="task-form-show-mode col-sm-1" data-task-id="{{ $task->id }}">
                                <form action="{{ url('/'.$task->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger delete-cancel-btn">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            <!-- Cancel Edit Mode Button -->
                            <div class="task-form-edit-mode col-sm-1" data-task-id="{{ $task->id }}" hidden>
                                <button class="btn btn-warning cancel-edit-mode-button delete-cancel-btn" data-task-id="{{ $task->id }}">
                                    Cancel
                                </button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
</body>

</html>

<script>
    // function with AJAX request to tasks list reordering method
    $(document).ready(function() {
        $("#task_list").sortable({
            placeholder: "ui-state-highlight",
            update: function(event, ui) {
                var task_id_array = new Array();
                $('#task_list li').each(function() {
                    task_id_array.push($(this).attr("id"));
                });
                $.ajax({
                    url: "tasks/reorder",
                    method: "POST",
                    data: {
                        task_id_array: task_id_array,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(data) {
                        // notify user about result of reordering
                        alert(data);
                    }
                });
            }
        });
    });

    // function for switching from show to edit mode and vice versa
    $(document).ready(function() {
        $(".edit-mode-button").click(function() {
            var id = $(this).attr('data-task-id');
            $(".task-form-edit-mode[data-task-id='" + id + "']").show();
            $(".task-form-show-mode[data-task-id='" + id + "']").hide();
        });
        $(".cancel-edit-mode-button").click(function() {
            var id = $(this).attr('data-task-id');
            $(".task-form-edit-mode[data-task-id='" + id + "']").hide();
            $(".task-form-show-mode[data-task-id='" + id + "']").show();
        });
    });
</script>