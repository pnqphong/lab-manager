<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MonHoc;
use App\MonHoc_PhanMem;
use DB;

class MonHocController extends Controller
{
    //
    public function getDanhSach()
    {
        $monhoc = MonHoc::all();
        return view('admin.monhoc.danhsach', ['monhoc'=>$monhoc]);
    }

    public function getThem()
    {
        return view('admin.monhoc.them');
    }

    public function postThem(Request $request)
    {
        $this->validate($request,
            [
                'TenMH'=>'required|min:3|max:255',
                'MaMH'=>'required|min:5|max:5',
                //'MaMH'=>'unique: monhoc, MaMH',
                'SoTinChi'=>'required'
            ],
            [
                'TenMH.required'=>'Bạn chưa nhập tên môn học',
                'TenMH.min'=>'Tên môn học có ít nhất 3 ký tự',
                'TenMH.max'=>'Tên môn học có nhiều nhất 255 ký tự',
                'MaMH.required'=>'Bạn chưa nhập mã môn học',
                'MaMH.min'=>'Mã môn học có độ dài 5 ký tự',
                'MaMH.max'=>'Mã môn học có độ dài 5 ký tự',
                //'MaMH.unique'=>'Mã môn học không được trùng',
                'SoTinChi.required'=>'Bạn chưa nhập số tín chỉ'
            ]);

        $monhoc = new MonHoc();
        $monhoc->MaMH =$request->MaMH;
        $monhoc->TenMH =$request->TenMH;
        $monhoc->SoTinChi =$request->SoTinChi;
        $monhoc->save();

        return redirect('admin/monhoc/them')->with('thongbao','Thêm thành công');        
    }

    public function getSua($id)
    {
        $monhoc = MonHoc::find($id);
        return view('admin.monhoc.sua', ['monhoc'=>$monhoc]);
    }

    public function postSua(Request $request, $id)
    {
        $monhoc = MonHoc::find($id);
        $this->validate($request,
            [
                'TenMH'=>'required|min:3|max:255',
                'MaMH'=>'required|min:5|max:5',
                'MaMH'=>'unique: monhoc, MaMH',
                'SoTinChi'=>'required'
            ],
            [
                'TenMH.required'=>'Bạn chưa nhập tên môn học',
                'TenMH.min'=>'Tên môn học có ít nhất 3 ký tự',
                'TenMH.max'=>'Tên môn học có nhiều nhất 255 ký tự',
                'MaMH.required'=>'Bạn chưa nhập mã môn học',
                'MaMH.min'=>'Mã môn học có độ dài 5 ký tự',
                'MaMH.max'=>'Mã môn học có độ dài 5 ký tự',
                'MaMH.unique'=>'Mã môn học không được trùng',
                'SoTinChi.required'=>'Bạn chưa nhập số tín chỉ'
            ]);

        $monhoc->MaMH = $request->MaMH;
        $monhoc->TenMH = $request->TenMH;
        $monhoc->SoTinChi = $request->SoTinChi;
        $monhoc->save();        

        return redirect('admin/monhoc/sua/'.$id)->with('thongbao','Sửa thành công');
    }

    public function getXoa($id)
    {
        $mhpm = DB::table('monhoc_phanmem')->where('idMonHoc',$id)->get();
        foreach ($mhpm as $mhpm) {
            unset($mhpm);
        }
        $monhoc = MonHoc::find($id);
        $monhoc->delete();
        
        return redirect('admin/monhoc/danhsach')->with('thongbao','Xóa thành công');
    }

}
