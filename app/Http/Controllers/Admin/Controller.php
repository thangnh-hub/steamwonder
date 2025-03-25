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

    /**
     * Xử lý các thông tin hệ thống trước khi đổ ra view
     * @author: ThangNH
     * @created_at: 2021/10/01
     */

    protected function responseView($view)
    {
        $this->responseData['admin_auth'] = Auth::guard('admin')->user();
        /**
         * Get all access menu to show in the sidebar by role of current User
         */
        $this->responseData['accessMenus'] = AdminService::getAccessMenu();

        // get all jobs
        $params_job['status'] = Consts::STATUS['active'];
        $params_job['time_expired'] = date('Y-m-d', time());
        $this->responseData['cms_jobs'] = Jobs::getSqlCmsJobs($params_job)->orderBy('time_expired', 'asc')->get();
        // get number notify (tạm ẩn đi vì nặng truy vấn)
        // $user = Auth::guard('admin')->user();
        // $DataPermissionService = new DataPermissionService;
        // $list_id_student = $DataPermissionService->getPermissionStudents($user->id);
        // $list_id_class = $DataPermissionService->getPermissionClasses($user->id);
        // $data_id = array_merge($list_id_student, $list_id_class);
        // $params_notify['order_by'] = ['created_at'=>'desc'];
        // $params_notify['id_object'] = $data_id;
        // if($user->admin_type == 'admission'){
        // $params_notify['type'] = 'late';
        // }
        // $this->responseData['notify'] = Notify::getNotify($params_notify)->count();
        // $this->responseData['user_notify'] = User_notify::where('id_user',Auth::guard('admin')->user()->id)->pluck('id_notify')->toArray();;
        //end notify
        $params_action['status'] = Consts::STATUS['active'];
        $user_action = UserAction::getSqlUserAction($params_action)->get();
        $this->responseData['user_action_header'] = $user_action;
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
