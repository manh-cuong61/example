@extends('layout.app')
@section('main')
      <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Menus</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Menu</a></li>
              <li class="breadcrumb-item active">List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="col-md-12">
        <a href="{{route('menus.create')}}" class="btn btn-success m-2">Add</a>
    </div>
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Tên danh mục</th>
                <th scope="col">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menus as $menu)
                <tr>
                    <th scope="row">{{$menu->id}}</th>
                    <td>{{$menu->name}}</td>
                    <td class="d-flex p-2">
                        <a href="{{route('menus.edit', ['menu' => $menu->id])}}" class="btn btn-default">Edit</a>
                        <form action="{{route('menus.destroy', ['menu' => $menu->id])}}" method="post" class="ml-1">
                          @csrf
                          @method('DELETE')
                          <input type="submit" class="btn btn-danger" value="Delete">
                        </form>
                        
                    </td>
                </tr>
                @endforeach               
            </tbody>
        </table>
    </div>
    <!-- /.content -->
  </div>
@endsection