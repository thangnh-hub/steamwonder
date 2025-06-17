<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Http\Services\GoogleDriveService;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    protected $rootFolderId;

    public function __construct()
    {
        parent::__construct();

        $this->rootFolderId = '1VZYsxj3HwbmpYdigZn0LE7KbBRPgTcjy'; // Shared Drive hoặc thư mục "Ảnh điểm danh"
        $this->routeDefault  = 'google_drive';
        $this->viewPart = 'admin.pages.google_drive';
        $this->responseData['module_name'] = __('Google Drive Management');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $file = $request->file('file');
        $path = $file->getRealPath();
        $name = $file->getClientOriginalName();
        $mime = $file->getMimeType();
        // Kiểm tra xem tệp có hợp lệ không

        $drive = new GoogleDriveService();
        // Ví dụ ngày hiện tại: 2025-06-12
        $year = date('Y');      // 2025
        $monthDay = date('m-d'); // 06-12

        // Tạo hoặc lấy thư mục con theo cấu trúc Năm / Tháng-Ngày
        $yearFolderId = $drive->createOrGetFolder($year, $this->rootFolderId);
        $dayFolderId = $drive->createOrGetFolder($monthDay, $yearFolderId);

        $uploaded = $drive->upload($path, $name, $mime, $dayFolderId);

        return response()->json([
            'id' => $uploaded->id,
            'link' => $uploaded->webViewLink,
        ]);
    }

    public function uploadMultipleFiles(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:102400', // 100MB mỗi file
        ]);

        $drive = new GoogleDriveService();

        $year = date('Y');
        $monthDay = date('m-d');

        // Tạo thư mục năm/tháng-ngày
        $yearFolderId = $drive->createOrGetFolder($year, $this->rootFolderId);
        $dayFolderId = $drive->createOrGetFolder($monthDay, $yearFolderId);

        $results = [];

        foreach ($request->file('files') as $file) {
            $path = $file->getRealPath();
            $name = $file->getClientOriginalName();
            $mime = $file->getMimeType();

            $upload = $drive->upload($path, $name, $mime, $dayFolderId);

            $results[] = [
                'name' => $name,
                'id' => $upload->id,
                'link' => $upload->webViewLink,
            ];
        }

        return response()->json(['files' => $results]);
    }
}
