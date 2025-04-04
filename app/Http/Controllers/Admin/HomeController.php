<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\StaffAdmission;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\tbClass;
use App\Models\Course;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->viewPart = 'admin.pages.home';
    $this->responseData['module_name'] = __('Welcome to Admin System!');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if (Auth::guard('admin')->user()->status == 'deactive') {
      return redirect()->route('test_staff.index');
    }

    return $this->responseView($this->viewPart . '.index');
  }
}
