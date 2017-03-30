@extends('admin.layout.index')

@section('content')
<!-- Page Content -->

<div class="col-md-12">
    <h1 class="page-header">Giáo viên
        <small>chi tiết</small>
    </h1>
	
	<div class="col-md-9" style="padding-bottom:120px">
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
	    
	    
	    <div class="row">
		    <div class="col-lg-7">
		    	<div class="panel panel-default">
			    	<div class="panel-heading">
			    		<label>Thông tin giáo viên</label>
			    	</div>
			    	<div class="panel-body">
			    		<table class="table-show-data" style="border: none;width: 100%;">
			    			<tr>
		    					<td>Mã giáo viên</td>
		    					<td id="MaGV">{{$giaovien->MaGV}}</td>
			    			</tr>
			    			<tr>
			    				<td>Họ và tên</td>
			    				<td>
			    					{{$giaovien->HoGV}}
			    					{{$giaovien->TenGV}}
			    				</td>
			    			</tr>
			    			<tr>
		    					<td>Ngày sinh</td>
		    					<td>{{$giaovien->NgaySinh}}</td>
			    			</tr>
			    			<tr>
		    					<td>Giới tính</td>
		    					<td>{{$giaovien->GioiTinh}}</td>
			    			</tr>
			    			<tr>
		    					<td>Số điện thoại</td>
		    					<td>{{$giaovien->SDT}}</td>
			    			</tr>
			    			<tr>
		    					<td>Bộ môn</td>
		    					<td>
		    						@foreach ($bomon as $bm)
		    							@if ($bm->id == $giaovien->idBoMon)
		    							{{$bm->TenBM}}
		    							@endif
		    						@endforeach
		    					</td>
			    			</tr>
			    		</table>
			    	</div>
			    </div>
		    </div>


		    <div class="col-lg-5">
		    	<div class="panel panel-default">
				  	<div class="panel-heading">
				  		<label>Các quyền hạn của người dùng</label>
			  		</div>
			  		<ul class="list-group">
				  		<li class="list-group-item">
		                    Người dùng bình thường
		                    <div class="material-switch pull-right">
		                    	@if ($normal_user == true)
		                        <input checked id="normal-user" name="normal-user" type="checkbox"/>
		                        @else
		                        <input id="normal-user" name="normal-user" type="checkbox"/>
		                        @endif
		                        <label for="normal-user" class="label-success"></label>
		                    </div>
		                </li>
		                <li class="list-group-item">
		                    Người dùng quản lí
		                    <div class="material-switch pull-right">
		                    	@if ($manager == true)
		                        <input checked id="manager" name="manager" type="checkbox"/>
		                        @else
		                        <input id="manager" name="manager" type="checkbox"/>
		                        @endif
		                        <label for="manager" class="label-success"></label>
		                    </div>
		                </li>
		                <li class="list-group-item">
		                    Administrator
		                    <div class="material-switch pull-right">
	                   		 	@if ($admin == true)
		                        <input checked id="admin" name="admin" type="checkbox"/>
		                        @else
		                        <input id="admin" name="admin" type="checkbox"/>
		                        @endif
		                        <label for="admin" class="label-success"></label>
		                    </div>
		                </li>
		            </ul>
				</div>
		    </div>
		</div>
	    
	</div>
</div>

@endsection

@section ('script')
<script type="text/javascript">
	$(document).ready (function (){
		$("input[type='checkbox']").change(function() {
		    if (this.checked) 
		    {
		    	var roleName = this.id;
		    	var magv = $('#MaGV').text();
    	       	//alert(magv + roleId);
    	       	$.ajax({
		            type: "get",
		            url: "ajax/addRole/" +magv+ "/" + roleName,
		            success: function (data) {
		                console.log(data);
		            	//showLich(data);	               
		            },
		            error: function (data) {
		                console.log('Error:', data);
		            }
		        });	

		    } 
		    else 
	    	{
    			var roleName = this.id;
		    	var magv = $('#MaGV').text();
    	       	//alert(magv + roleId);
    	       	$.ajax({
		            type: "get",
		            url: "ajax/removeRole/" +magv+ "/" + roleName,
		            success: function (data) {
		                console.log(data);
		            	//showLich(data);	               
		            },
		            error: function (data) {
		                console.log('Error:', data);
		            }
		        });	
    		}		    
		});
	});
</script>
@endsection