<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use App\Http\Services\AdminService;
use App\Http\Services\ContentService;
use App\Http\Services\DataPermissionService;
use App\Imports\StudentImport;
use App\Models\Language;
use App\Models\UserAction;
use App\Models\Jobs;
use App\Models\Notify;
use App\Models\User_notify;
use Illuminate\Support\Facades\App;
use stdClass;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Part to views for Controller
    protected $viewPart;
    // Route default for Controller
    protected $routeDefault;
    // Data response to view
    protected $responseData = [];

    // Settings
    protected $setting;

    /**
     * @author: ThangNH
     * @created_at: 2025/03/19
     */
    public function __construct()
    {
        // 🔹 Lấy các thiết lập hệ thống
        $options = ContentService::getOption();
        $this->setting = new stdClass();

        if ($options) {
            foreach ($options as $option) {
                $this->setting->{$option->option_name} = $option->option_value;
            }
        }
    }

    /**
     * Xử lý các thông tin hệ thống trước khi đổ ra view
     * @author: ThangNH
     * @created_at: 2021/10/01
     */

    protected function responseView($view)
    {
        $this->responseData['setting'] = $this->setting;
        $this->responseData['admin_auth'] = Auth::guard('admin')->user();
        /**
         * Get all access menu to show in the sidebar by role of current User
         */
        $this->responseData['accessMenus'] = AdminService::getAccessMenu();

        // Set locale to use mutiple languages
        $languages = Language::orderBy('iorder')->get();
        $this->responseData['languages'] = $languages;
        $languageDefault = $languages->first(function ($item, $key) {
            return $item->is_default;
        });
        $this->responseData['languageDefault'] = $languageDefault;
        $locale = request()->cookie('locale_admin') ?? $languageDefault->lang_locale ?? App::getLocale();
        App::setLocale($locale);

        return view($view, $this->responseData);
    }

    protected function sendResponse($data, $message = '')
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response);
    }

    protected function getSetting()
    {
        // Get all global system params
        $options = ContentService::getOption();
        $setting = new stdClass();
        if ($options) {
            foreach ($options as $option) {
                $setting->{$option->option_name} = $option->option_value;
            }
        }

        return $setting;
    }

    protected function checkFileImport($file)
    {
        $spreadsheet = IOFactory::load($file);
        $sheetCount = $spreadsheet->getSheetCount();
        $sheetVisibility = true;
        for ($i = 0; $i < $sheetCount; $i++) {
            $sheet = $spreadsheet->getSheet($i);
            $visibility = $sheet->getSheetState() == Worksheet::SHEETSTATE_VISIBLE;
            if ($visibility == false) {
                $sheetVisibility = false;
            }
        }
        return $sheetVisibility;
    }
}
