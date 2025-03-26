<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\AccountingDebt;
use App\Models\Student;
use App\Models\tbClass;
use App\Models\Admin;
use App\Models\Course;
use App\Models\StatusStudent;
use App\Models\Field;
use App\Models\Area;
use App\Models\Level;
use App\Http\Services\DataPermissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AccountingDebtExport;
use App\Imports\AccountingDebtImport;



class AccountingDebtController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'accounting_debt';
        $this->viewPart = 'admin.pages.accounting_debt';
        $this->responseData['module_name'] = 'Công nợ kế toán';
    }
    
    
}
