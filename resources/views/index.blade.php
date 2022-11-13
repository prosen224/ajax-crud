<!DOCTYPE html>
    <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>



{{-- Add modal start --}}
<div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="add_contact_form" action="#" method="POST">
                @csrf
                <div class="modal-body p-4 pt-3 bg-light">

                <div class="mb-2">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control">
                    <div class="text-danger error_name error_remove"></div>
                </div>

                <div class="my-2">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" class="form-control">
                    <div class="text-danger error_phone error_remove"></div>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add_contact_btn" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Add modal end --}}

{{-- Edit modal start --}}
<div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit_contact_form" action="#" method="POST">
                @csrf
                <div class="modal-body p-4 pt-3 bg-light">

                <input type="hidden" name="contact_id" id="contact_id">

                <div class="mb-2">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                    <div class="text-danger error2_name error_remove2"></div>
                </div>

                <div class="my-2">
                    <label for="phone">Phone</label>
                    <input type="text" name="phone" id="phone" class="form-control">
                    <div class="text-danger error2_phone error_remove2"></div>
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit_contact_btn" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Edit modal end --}}



<body class="bg-light">

    <div class="container">
        <div class="row my-5">
        <div class="col-lg-12">
            <div class="card shadow">
            <div class="card-header bg-success d-flex justify-content-between align-items-center">
                <h3 class="text-light">Manage Contacts</h3>
                <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addContactModal"><i class="bi-plus-circle me-2"></i>Add New Contact</button>
            </div>
            <div class="card-body" id="show_all_contacts">
                <h3 class="text-center text-secondary my-5">Loading...</h3>
            </div>
            </div>
        </div>
        </div>
    </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Store Data
        $("#add_contact_form").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{{ route('store') }}',
                data: $('#add_contact_form').serialize(),
                dataType: 'json',
                success: function (response) {
                    if(response.status == 200){
                        Swal.fire(
                            'Added!',
                            'Contact Added Successfully!',
                            'success'
                        )
                        fetchALL();
                        $('#add_contact_form')[0].reset();
                        $('.error_remove').html("");
                        $('#addContactModal').modal('hide');
                    }else{
                        $.each(response.errors, function(key, value){
                            $('.error_'+key).html(value);
                        });
                    }
                }
            });
        });

        // Edit Data
        $(document).on('click', '.editIcon', function(e){
            let id = $(this).attr('id');
            $.ajax({
                type: "get",
                url: "{{ route('edit') }}",
                data: {
                    id:id,
                    _token: '{{ csrf_token() }}'
                },

                success: function (response) {
                    $('#contact_id').val(response.id);
                    $('#name').val(response.name);
                    $('#phone').val(response.phone);
                }

            });
        })

        // Update Data
        $("#edit_contact_form").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{{route('update')}}',
                data: $('#edit_contact_form').serialize(),
                dataType: 'json',
                success: function (response) {
                    if(response.status == 200){
                        Swal.fire(
                            'Updated!',
                            'Contact Updated Successfully!',
                            'success'
                        )
                        fetchALL();
                        $('.error_remove2').html("");
                        $('#edit_contact_form')[0].reset();
                        $('#editContactModal').modal('hide');

                    }else{
                        $.each(response.errors, function(key, value){
                            $('.error2_'+key).html(value);
                        });
                    }
                }
            });
        });

        // Delete Data
        $(document).on('click', '.deleteIcon', function(e){
            e.preventDefault();
            let id = $(this).attr('id');
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: '{{ route('delete') }}',
                        type: 'get',
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            console.log(response)
                            Swal.fire(
                                'Deleted!',
                                'Contact Deleted Successfully!',
                                'success'
                            )
                            fetchALL();
                        }
                    });
                }
            })
        });

        // Fetch All Contacts
        fetchALL();
        function fetchALL(){
            $.ajax({
                type: 'get',
                url: '{{ route('fetchAll') }}',
                success: function (response){
                    $('#show_all_contacts').html(response);
                    $("table").DataTable({
                        order: [0, 'desc']
                    });

                }
            });
        }

    });
</script>

</body>
</html>
