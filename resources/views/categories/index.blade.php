@extends('layout.app')
@section('main')
      <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Danh mục sản phẩm</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Danh mục</a></li>
              <li class="breadcrumb-item active">Danh sách</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="col-md-12">
        <a href="{{route('categories.create')}}" class="btn btn-success m-2">Add</a>
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
                @foreach($categories as $category)
                <tr>
                    <th scope="row">{{$category->id}}</th>
                    <td>{{$category->name}}</td>
                    <td class="d-flex p-2">
                        <a href="{{route('categories.edit', ['id' => $category->id])}}" class="btn btn-default">Edit</a>
                        <form action="{{route('categories.destroy', ['id' => $category->id])}}" method="post" class="ml-1">
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