@extends('backend.layouts.master')

@section('main-content')
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="row">
        <div class="col-md-12">
            @include('backend.layouts.notification')
        </div>
    </div>
    <div class="card-header py-3">
        <div class="row">
            <div class="col-md-6">
                <h6 class="m-0 font-weight-bold text-primary float-left">Users List</h6>
            </div>
            <div class="col-md-6">
                <a href="{{route('users.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User">
                    <i class="fas fa-plus"></i> Add User
                </a>
                <form action="{{ route('users.index') }}" method="GET" id="perPageForm" class="float-right mr-3">
                    <select name="perPage" class="form-control form-control-sm" onchange="document.getElementById('perPageForm').submit()">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="user-dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Photo</th>
                        <th>Join Date</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)   
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                @if($user->photo)
                                    <img src="{{$user->photo}}" class="img-fluid rounded-circle" style="max-width:50px" alt="{{$user->photo}}">
                                @else
                                    <img src="{{asset('backend/img/avatar.png')}}" class="img-fluid rounded-circle" style="max-width:50px" alt="avatar.png">
                                @endif
                            </td>
                            <td>
                                {{ optional($user->created_at)->diffForHumans() ?? 'N/A' }}
                            </td>
                            <td>{{ucfirst($user->role)}}</td>
                            <td>
                                @if($user->status=='active')
                                    <span class="badge badge-success">{{$user->status}}</span>
                                @else
                                    <span class="badge badge-warning">{{$user->status}}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('users.edit',$user->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{route('users.destroy',[$user->id])}}">
                                    @csrf 
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm dltBtn" data-id={{$user->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>  
                    @endforeach
                </tbody>
            </table>
            <div class="float-right">
                {{ $users->appends(['perPage' => request('perPage')])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .w-5{
          width: 25px;
      }
      .hidden{
          display: none;
      }
      .pagination {
          margin-top: 20px;
      }
      .pagination li {
          display: inline-block;
          margin-right: 5px;
      }
      .pagination li a {
          padding: 5px 10px;
          border: 1px solid #ddd;
          border-radius: 4px;
      }
      .pagination li.active span {
          background: #007bff;
          color: white;
          border-color: #007bff;
          padding: 5px 10px;
          border-radius: 4px;
      }
  </style>
@endpush

@push('scripts')
  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <script>
      // Initialize DataTable with search but without pagination
      $('#user-dataTable').DataTable({
          "paging": false,
          "searching": true,
          "info": false,
          "columnDefs": [
              { "orderable": false, "targets": [6,7] }
          ]
      });

      // Sweet alert for delete confirmation
      $('.dltBtn').click(function(e){
          e.preventDefault();
          var form = $(this).closest('form');
          var dataID = $(this).data('id');
          
          swal({
              title: "Are you sure?",
              text: "Once deleted, you will not be able to recover this data!",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
              if (willDelete) {
                  form.submit();
              } else {
                  swal("Your data is safe!");
              }
          });
      });
  </script>
@endpush