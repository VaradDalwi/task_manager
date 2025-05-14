<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .accordion-collapse {
            transition: all 0.3s ease-out;
        }
        .accordion-collapse.collapse:not(.show) {
            display: block;
            height: 0;
            overflow: hidden;
        }
        .accordion-collapse.collapsing {
            height: 0;
            overflow: hidden;
        }
        .accordion-collapse.collapse.show {
            height: auto;
            overflow: visible;
        }
        .accordion-item{
            box-shadow: 4px 4px 10px 0px #00000026 !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#13293d">
        <div class="container">
            <a class="navbar-brand" href=""><b>TASK MANAGER</b></a>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle d-flex align-items-center" style="background-color:#006494 !important" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="dropdown-item p-0">
                                @csrf
                                <button type="submit" class="btn w-100 text-start px-3 py-2">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Add this alert container after the navbar -->
        <div id="alertContainer" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1050;width:600px;"></div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card" style="background-color: #006494; color: white;">
                    <div class="card-body">
                        <h5 class="card-title">User Location</h5>
                        <p class="card-text" id="location-info">Loading location...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color: #006494; color: white;">
                    <div class="card-body">
                        <h5 class="card-title">Total Projects</h5>
                        <p class="card-text" id="total-projects">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color: #006494; color: white;">
                    <div class="card-body">
                        <h5 class="card-title">Total Tasks</h5>
                        <p class="card-text" id="total-tasks">0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h3><b>All Projects</b></h3>
                <button class="btn btn-primary d-flex align-items-center gap-2" style="background-color:#13293d !important" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                    <i class="bi bi-plus-lg"></i>
                    Add New Project
                </button>
            </div>
        </div>

        <div class="accordion" id="projectsAccordion" style="gap: 1rem; display: flex; flex-direction: column;">
            <!-- Projects will be loaded here -->
        </div>
    </div>

    <!-- Add/Edit Project Modal -->
    <div class="modal fade" id="addProjectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectModalTitle">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="projectForm">
                        <input type="hidden" id="projectId" name="project_id">
                        <div class="mb-3">
                            <label class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="name" id="projectName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="projectDescription"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveProject">Save Project</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalTitle">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <input type="hidden" name="project_id" id="taskProjectId">
                        <input type="hidden" name="task_id" id="taskId">
                        <div class="mb-3">
                            <label class="form-label">Task Title</label>
                            <input type="text" class="form-control" name="title" id="taskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="taskDescription"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" id="taskStatus">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveTask">Save Task</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function loadProjects() {
            $.get('/projects', function(projects) {
                let html = '';
                projects.forEach((project, index) => {
                    html += `
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button ${index === 0 ? '' : 'collapsed'}" style="font-weight:500;background-color: #006494; color: white;" type="button" 
                                    data-bs-toggle="collapse" data-bs-target="#project-${project.id}">
                                    ${project.name}
                                </button>
                            </h2>
                            <div id="project-${project.id}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}">
                                <div class="accordion-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <p class="mb-0">${project.description || ''}</p>
                                        <div>
                                            <button class="btn btn-sm btn-primary add-task" style="background-color:#13293d !important" data-project-id="${project.id}">
                                                <i class="bi bi-plus-lg"></i>
                                                Add Task
                                            </button>
                                            <button class="btn btn-sm btn-warning edit-project" data-project-id="${project.id}" 
                                                data-project-name="${project.name}" data-project-description="${project.description || ''}">
                                                <i class="bi bi-pencil"></i>
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-project" data-project-id="${project.id}">
                                                <i class="bi bi-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                    <div class="tasks-container" id="tasks-${project.id}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#projectsAccordion').html(html);
                projects.forEach(project => loadTasks(project.id));
                updateStatistics();
            });
        }

        function loadTasks(projectId) {
            $.get(`/projects/${projectId}/tasks`, function(tasks) {
                let html = '';
                tasks.forEach(task => {
                    html += `
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-1">${task.title}</h6>
                                        <p class="card-text small mb-1">${task.description || ''}</p>
                                        <span class="badge bg-${getStatusColor(task.status)}">${task.status}</span>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-warning edit-task" 
                                            data-task-id="${task.id}"
                                            data-project-id="${projectId}"
                                            data-title="${task.title}"
                                            data-description="${task.description || ''}"
                                            data-status="${task.status}">
                                            <i class="bi bi-pencil"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-task" data-task-id="${task.id}">
                                            <i class="bi bi-trash"></i>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $(`#tasks-${projectId}`).html(html);
            });
        }

        function getStatusColor(status) {
            switch(status) {
                case 'completed': return 'success';
                case 'in_progress': return 'warning';
                default: return 'secondary';
            }
        }

        // Load location
        $.get('/user-location', function(data) {
            $('#location-info').text(`Location: ${data.city}, ${data.country}`);
        });

        // Project Event Handlers
        function showAlert(message, type = 'success') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.insertAdjacentHTML('beforeend', alertHtml);

            // Auto dismiss after 3 seconds
            setTimeout(() => {
                const alerts = alertContainer.getElementsByClassName('alert');
                if (alerts.length > 0) {
                    const alert = alerts[0];
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 3000);
        }

        // Update project handlers
        $('#saveProject').click(function() {
            const projectId = $('#projectId').val();
            const formData = $('#projectForm').serialize();
            const url = projectId ? `/projects/${projectId}` : '/projects';
            const method = projectId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: formData,
                success: function() {
                    $('#addProjectModal').modal('hide');
                    loadProjects();
                    $('#projectForm')[0].reset();
                    $('#projectId').val('');
                    showAlert(projectId ? 'Project updated successfully!' : 'Project created successfully!');
                },
                error: function() {
                    showAlert('Error saving project. Please try again.', 'danger');
                }
            });
        });

        // Update delete project handler
        $(document).on('click', '.delete-project', function() {
            const projectId = $(this).data('project-id');
            if(confirm('Are you sure you want to delete this project?')) {
                $.ajax({
                    url: `/projects/${projectId}`,
                    type: 'DELETE',
                    success: function() {
                        loadProjects();
                        showAlert('Project deleted successfully!');
                    },
                    error: function() {
                        showAlert('Error deleting project. Please try again.', 'danger');
                    }
                });
            }
        });

        // Update task handlers
        $('#saveTask').click(function () {
            const taskId = $('#taskId').val();
            const isUpdate = !!taskId;
            let formData = $('#taskForm').serialize();

            if (isUpdate) {
                formData += '&_method=PUT'; // Add PUT override for Laravel
            }

            const url = isUpdate ? `/tasks/${taskId}` : '/tasks';

            $.ajax({
                url: url,
                method: 'POST', // Always use POST, Laravel will see _method for override
                data: formData,
                success: function () {
                    $('#taskModal').modal('hide');
                    loadProjects();
                    $('#taskForm')[0].reset();
                    $('#taskId').val('');
                    showAlert(isUpdate ? 'Task updated successfully!' : 'Task created successfully!');
                },
                error: function () {
                    showAlert('Error saving task. Please try again.', 'danger');
                }
            });

            updateStatistics();
        });

        // Update delete task handler
        $(document).on('click', '.delete-task', function() {
            const taskId = $(this).data('task-id');
            if(confirm('Are you sure you want to delete this task?')) {
                $.ajax({
                    url: `/tasks/${taskId}`,
                    type: 'DELETE',
                    success: function() {
                        loadProjects();
                        showAlert('Task deleted successfully!');
                    },
                    error: function() {
                        showAlert('Error deleting task. Please try again.', 'danger');
                    }
                });
            }
        });
        $(document).on('click', '.edit-project', function() {
            const projectId = $(this).data('project-id');
            const projectName = $(this).data('project-name');
            const projectDescription = $(this).data('project-description');

            $('#projectModalTitle').text('Edit Project');
            $('#projectId').val(projectId);
            $('#projectName').val(projectName);
            $('#projectDescription').val(projectDescription);
            $('#addProjectModal').modal('show');
        });

        $('#addProjectModal').on('hidden.bs.modal', function() {
            $('#projectModalTitle').text('Add New Project');
            $('#projectForm')[0].reset();
            $('#projectId').val('');
        });

        // Task Event Handlers
        $(document).on('click', '.add-task', function() {
            $('#taskModalTitle').text('Add New Task');
            $('#taskProjectId').val($(this).data('project-id'));
            $('#taskId').val('');
            $('#taskForm')[0].reset();
            $('#taskModal').modal('show');
        });

        $(document).on('click', '.edit-task', function() {
            const taskId = $(this).data('task-id');
            const projectId = $(this).data('project-id');
            const title = $(this).data('title');
            const description = $(this).data('description');
            const status = $(this).data('status');

            $('#taskModalTitle').text('Edit Task');
            $('#taskId').val(taskId);
            $('#taskProjectId').val(projectId);
            $('#taskTitle').val(title);
            $('#taskDescription').val(description);
            $('#taskStatus').val(status);
            $('#taskModal').modal('show');
        });

        // Update statistics
        function updateStatistics() {
            $.get('/projects', function(projects) {
                $('#total-projects').text(projects.length);
                let totalTasks = 0;
                const promises = projects.map(project => {
                    return $.get(`/projects/${project.id}/tasks`).then(tasks => {
                        totalTasks += tasks.length;
                    });
                });
                
                Promise.all(promises).then(() => {
                    $('#total-tasks').text(totalTasks);
                });
            });
        }

        // Initial load
        loadProjects();
    </script>
</body>
</html>