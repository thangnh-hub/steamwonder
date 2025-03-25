<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Exception;
use App\Models\Cv;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'profiles';
        $this->viewPart = 'admin.pages.profiles';
        $this->responseData['module_name'] = __('Profiles Management');
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $rows = Cv::getSqlCv($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] =  $rows;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['type_profile'] = Consts::CV_TYPE;
        $this->responseData['params'] = $params;
        return $this->responseView($this->viewPart . '.index');
    }
    public function show($id)
    {
        $cv = Cv::find($id);
        $this->responseData['detail'] = $cv;
        return $this->responseView($this->viewPart . '.show');
    }
    public function edit($id)
    {
        $cv = Cv::find($id);
        $this->responseData['detail'] = $cv;
        $this->responseData['info_user'] = Auth::user();
        $this->responseData['marital'] = Consts::CV_MARITAL;
        $this->responseData['company_position'] = Consts::CV_COMPANY_POSITION;
        $this->responseData['germany_level'] = Consts::CV_GERMANY_LEVEL;
        $this->responseData['language'] = Consts::CV_LANGUAGE;
        $this->responseData['quality'] = Consts::CV_QUALITY;
        $this->responseData['hobby'] = Consts::CV_HOBBY;
        return $this->responseView($this->viewPart . '.edit');
    }
    public function update(Request $request, $id)
    {
        $detail = Cv::find($id);
        if(!$detail) return redirect()->back()->with('errorMessage', __('Không tìm thấy CV!'));

        try {
            if(isset($request->json_params['profile'])){
                $detail->update([
                    'cv_title' => $request->cv_title,
                    'json_params->profile' => $request->json_params['profile'],
                ]);
            }
            if(isset($request->json_params['learning_process'])){
                $detail->update([
                    'json_params->learning_process' => $request->json_params['learning_process'],
                ]);
            }
            if(isset($request->json_params['experience'])){
                $detail->update([
                    'json_params->experience' => $request->json_params['experience'],
                ]);
            }
            if(isset($request->json_params['qualification'])){
                $detail->update([
                    'json_params->qualification' => $request->json_params['qualification'],
                ]);
            }
            if(isset($request->json_params['hobby'])){
                $detail->update([
                    'json_params->hobby' => $request->json_params['hobby'],
                ]);
            }
            if(isset($request->json_params['upload_image'])){
                $url_hocvien=Consts::URL_HOCVIEN;
                // Xử lý ảnh
                $avatar = $request->file('avatar');
                if($avatar){
                    $pathAvatar = isset($avatar) ? $avatar->store('avatars/' . $detail->user_id, 'cv') : Auth::user()->avatar;
                    $absolutePath = $url_hocvien.$pathAvatar;
                    $detail->update([
                        'json_params->upload_image->avatar' => $absolutePath,
                    ]);
                }
                
                //Hộ chiếu
                if ($request->has('deleted_files')) {
                    $deletedFiles = json_decode($request->deleted_files, true);
                    $paths = $detail->json_params->upload_image->passport_images ?? [];
                    $paths = array_values(array_diff($paths, $deletedFiles));
                    $detail->update([
                        'json_params->upload_image->passport_images' => $paths,
                    ]);
                }
                
                if ($request->hasFile('passport_images')) {
                    $passportImages = $request->file('passport_images');
                    $paths = $detail->json_params->upload_image->passport_images ?? []; // Lấy danh sách file cũ
                    foreach ($passportImages as $index => $passport) {
                        if ($passport) { 
                            $path = $passport->store('passports/' . $detail->user_id, 'cv');
                            $paths[$index] = $url_hocvien. $path ;// Cập nhật file tại vị trí tương ứng
                        }
                    }
                    // Cập nhật DB với danh sách file mới
                    $detail->update([
                        'json_params->upload_image->passport_images' => $paths,
                    ]);
                }

                // Xử lý chữ ký
                $signature_image = $request->file('signature_image');
                if($signature_image){
                    $path_signature_image = isset($signature_image) ? $signature_image->store('signature_image/' . $detail->user_id, 'cv') :"";
                    $detail->update([
                        'json_params->upload_image->signature_image' => $url_hocvien.$path_signature_image,
                    ]);
                }

                // Xử lý bằng c3
                $diploma_image = $request->file('diploma_image');
                if($diploma_image){
                    $path_diploma_image = isset($diploma_image) ? $diploma_image->store('diploma_image/' . $detail->user_id, 'cv') :"";
                    $detail->update([
                        'json_params->upload_image->diploma_image' => $url_hocvien.$path_diploma_image,
                    ]);
                }
                // Xử lý bằng Đức
                if ($request->has('deleted_files_germany')) {
                    $deletedFiles = json_decode($request->deleted_files_germany, true);
                    $paths = $detail->json_params->upload_image->germany_images ?? [];
                    $paths = array_values(array_diff($paths, $deletedFiles));
                    $detail->update([
                        'json_params->upload_image->germany_images' => $paths,
                    ]);
                }
                
                if ($request->hasFile('germany_images')) {
                    $passportImages = $request->file('germany_images');
                    $paths = $detail->json_params->upload_image->germany_images ?? []; // Lấy danh sách file cũ
                    foreach ($passportImages as $index => $passport) {
                        if ($passport) { 
                            $path = $passport->store('passports/' . $detail->user_id, 'cv');
                            $paths[$index] = $url_hocvien. $path ;// Cập nhật file tại vị trí tương ứng
                        }
                    }
                    // Cập nhật DB với danh sách file mới
                    $detail->update([
                        'json_params->upload_image->germany_images' => $paths,
                    ]);
                }
                
                // Xử lý file khác
                if ($request->has('deleted_files_other')) {
                    $deletedFiles = json_decode($request->deleted_files_other, true);
                    $paths = $detail->json_params->upload_image->other_file ?? [];
                    $paths = array_values(array_diff($paths, $deletedFiles));
                    $detail->update([
                        'json_params->upload_image->other_file' => $paths,
                    ]);
                }
                
                if ($request->hasFile('other_file')) {
                    $passportImages = $request->file('other_file');
                    $paths = $detail->json_params->upload_image->other_file ?? []; // Lấy danh sách file cũ
                    foreach ($passportImages as $index => $passport) {
                        if ($passport) { 
                            $path = $passport->store('passports/' . $detail->user_id, 'cv');
                            $paths[$index] = $url_hocvien. $path ;// Cập nhật file tại vị trí tương ứng
                        }
                    }
                    // Cập nhật DB với danh sách file mới
                    $detail->update([
                        'json_params->upload_image->other_file' => $paths,
                    ]);
                }
                
            }
        } catch (Exception $ex) {
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
        return redirect()->back()->with('successMessage', __('Cập nhật thành công!'));
    }
}
