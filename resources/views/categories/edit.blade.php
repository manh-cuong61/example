@extends('layout.app')
@section('main')
         <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Sửa danh mục</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('categories.index')}}">Danh mục</a></li>
              <li class="breadcrumb-item active">Sửa</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="col-md-6">
        <form action="{{route('categories.update', ['id' => $category->id])}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label >Tên danh mục</label>
              <input type="text" class="form-control" name="name" value="{{$category->name}}">
            </div>
            <div class="form-group">
                <label >Chọn danh mục cha</label>
                <select class="form-control" name="parent_id">
                  <option value="0" >Chọn danh mục cha</option>
                  {{!!$htmlSelect!!}}
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
    </div>
    <!-- /.content -->
  </div>
@endsection