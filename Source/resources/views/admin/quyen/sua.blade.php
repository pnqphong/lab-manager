@extends('admin.layout.index')

@section('content')
<!-- Page Content -->

<div class="col-md-12">
    <h1 class="page-header">Chỉnh sửa Giáo viên
        <small>{{$giaovien->TenGV}}</small>
    </h1>

    <div class="col-lg-7" style="padding-bottom:120px">
    	@if(count($errors)>0)
    		<div class="alert alert-danger">
    			@foreach($errors->all() as $err)
    				{{$err}}<br>
    			@endforeach
    		</div>
    	@endif

    	@if(session('thongbao'))
    		<div class="alert alert-success">
    			{{session('thongbao')}}
    		</div>
    	@endif

	    <form action="admin/giaovien/sua/{{$giaovien->id}}" method="POST">
	        <input type="hidden" name="_token" value="{{csrf_token()}}" />
	        <div class="col-md-6">
	        	<div class="form-group">
		            <label>Mã giáo viên</label>
		            <input class="form-control" name="MaGV" value="{{$giaovien->MaGV}}" placeholder="Nhập mã giáo viên" />
		        </div>
		        <div class="form-group">
		            <label>Họ</label>
		            <input class="form-control" name="HoGV" value="{{$giaovien->HoGV}}" placeholder="Nhập học giáo viên" />
		        </div>
		        <div class="form-group">
		            <label>Tên</label>
		            <input class="form-control" name="TenGV" placeholder="Nhập tên giáo viên" value="{{$giaovien->TenGV}}"/>
		        </div>
		        <div class="form-group">
		            <label>Ngày sinh</label>
		            <input type="date" class="form-control" name="NgaySinh" value="{{$giaovien->NgaySinh}}"/>
		        </div>
		        <div class="form-group">
		            <label>Giới tính</label>
		            <select name="GioiTinh" class="form-control">
		                @if($giaovien->GioiTinh == 0)
		                	<option selected value="0">Nam</option>
		                	<option value="1">Nữ</option>
		                @elseif($giaovien->GioiTinh == 1)
		                	<option selected value="1">Nữ</option>
		                	<option value="0">Nam</option>
		                @endif
		            </select>
		        </div>
	        </div>
		    <div class="col-md-6">
		    	<div class="form-group">
		            <label>Số điện thoại</label>
		            <input type="number" class="form-control" name="SDT" placeholder="Nhập Số điện thoại" value="{{$giaovien->SDT}}"/>
		        </div>
		        <div class="form-group">
		            <label>Chức vụ</label>
		            <select name="idChucVu" class="form-control">
		                @foreach($chucvu as $cv)
		                    <option
		                    @if($cv->id == $giaovien->idChucVu)
		                        {{"selected"}}
		                    @endif
		                     value="{{$cv->id}}">{{$cv->TenCV}}</option>
		                @endforeach
		            </select>
		        </div>
		        <div class="form-group">
		            <label>Bộ môn</label>
		            <select class="form-control" name="idBoMon">
		            	@foreach($bomon as $bm)
		                    <option
		                    @if($bm->id == $giaovien->idBoMon)
		                        {{"selected"}}
		                    @endif
		                     value="{{$bm->id}}">{{$bm->TenBM}}</option>
		                @endforeach
		            </select>
		        </div>
		        <div class="form-group">
		            <label>Mật khẩu</label>
		            <input type="text" class="form-control" name="password" value="{{$giaovien->password}}" placeholder="Nhập mật khẩu"/>
		        </div>
		        <div class="form-group">
		            <label>Kích hoạt</label>
		            <select class="form-control" name="KichHoat">
		            	@if($giaovien->KichHoat == 0)
		                	<option selected value="0">Không</option>
		                	<option value="1">Có</option>
		                @elseif($giaovien->KichHoat == 1)
		                	<option selected value="1">Có</option>
		                	<option value="0">Không</option>
		                @endif
		            </select>
		        </div>
		    </div>

	        <button type="submit" class="btn btn-primary">Thêm</button>
	        <button type="reset" class="btn btn-default">Reset</button>
	    <form>
	</div>
</div>

@endsection