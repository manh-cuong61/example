@extends('layout.app')
@section('main')
         <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Thêm menu</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('menus.index')}}">Menus</a></li>
              <li class="breadcrumb-item active">Thêm</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="col-md-6">
        <form action="{{route('menus.store')}}" method="post">
            @csrf
            <div class="form-group">
              <label >Tên danh mục</label>
              <input type="text" class="form-control" name="name" placeholder="Nhập tên danh mục">
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