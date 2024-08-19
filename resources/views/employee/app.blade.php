<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <nav class="navbar navbar-light" style="background-color: #2c77c3;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand mb-0 h1">Dashboard</span>
            <div class="d-flex">
                <a href="#" id="search" style="color:white;"><i data-feather="log-out"></i></a>
            </div>
        </div>
    </nav>

    <style>
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .btn {
            font-size: 0.75rem;
        }
        @media (max-width: 576px) {
            .table-responsive {
                overflow-x: auto;
            }
            .btn-group {
                flex-direction: column;
                gap: 2px;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
    
</head>
<body>

<div class="container mt-5">
    <h4>Data karyawan</h4>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Bagian Tambah Data dan Pencarian -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button id="toggleFormButton" class="btn btn-primary">Tambahkan Data</button>
        <form id="employee-form" action="{{ route('employee') }}" method="get">
            <div class="relative w-full d-flex justify-content-end">
                <div class="input-group input-group-sm" style="width: 250px;">  
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" type="submit" style="padding: 0px 10px;">
                            <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                            </svg>                  
                        </button>
                    </div>
                </div>
            </div>
        </form>        
    </div>

    <!-- Form Tambah Data -->
    <div id="employeeForm" style="display:none;" class="mb-3">
        <form id="employee-form" action="{{ route('employee.post') }}" method="post">
            @csrf
            @method('POST')
            <input type="hidden" id="employeeId" name="employee_id">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" id="position" name="position" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="office">Office</label>
                <input type="text" id="office" name="office" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="text" id="start_date" name="start_date" class="form-control datepicker" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <button type="button" id="cancelButton" class="btn btn-secondary">Cancel</button>
        </form>
    </div>

    <!-- Tabel data, edit & delete -->
    <table id="employeeTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Position</th>
                <th>Office</th>
                <th>Start Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->first_name }}</td>
                    <td>{{ $item->last_name }}</td>
                    <td>{{ $item->age }}</td>
                    <td>{{ $item->position }}</td>
                    <td>{{ $item->office }}</td>
                    <td>{{ $item->start_date }}</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="btn btn-primary btn-edit" data-id="{{ $item->id }}" data-toggle="collapse" data-target="#collapse-{{ $item->id }}" aria-expanded="false">
                                ✎
                            </button>
                            
                            <form action="{{ route('employee.delete', ['id' => $item->id]) }}" method="POST" onsubmit="return confirm('Yakin akan menghapus?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>                         
                </tr>

                <!-- Form Pengeditan (dalam collapse) -->
                <tr>
                    <td colspan="7">
                        <div class="collapse" id="collapse-{{ $item->id }}">
                            <form action="{{ route('employee.update', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="{{ $item->first_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="{{ $item->last_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="number" class="form-control" name="age" value="{{ $item->age }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control" name="position" value="{{ $item->position }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="office">Office</label>
                                    <input type="text" class="form-control" name="office" value="{{ $item->office }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="text" class="form-control datepicker" name="start_date" value="{{ $item->start_date }}" required>
                                </div>
                                <button class="btn btn-success" type="submit">Submit</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div>
        {{ $data->links() }}
    </div>

</div>

<script src="https://unpkg.com/feather-icons"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    
        // Toggle form visibility
        $('#toggleFormButton').click(function() {
            $('#employeeForm').toggle();
            $('#employee-form').trigger("reset");
            $('#employeeId').val('');
        });
    
        $('#cancelButton').click(function() {
            $('#employeeForm').hide();
        });
    
        // Event listener untuk tombol edit
        $(document).on('click', '.btn-edit', function(event) {
            event.preventDefault();
            var employeeId = $(this).data('id');
            console.log('Edit button clicked. Employee ID:', employeeId);
    
            // Toggle collapse to show the edit form
            $('#collapse-' + employeeId).collapse('toggle');
        });
    
        // Initialize DataTables
        var table = $('#employeeTable').DataTable();
    
        // Custom search functionality
        $('#tableSearch').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
    </script>

<script>
    feather.replace();
  </script>

</body>
</html>
