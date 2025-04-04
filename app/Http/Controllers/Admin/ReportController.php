<?php

namespace App\Http\Controllers\Admin;

use App\Consts;

class ReportController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'reports';
    $this->viewPart = 'admin.pages.reports';
    $this->responseData['module_name'] = 'Report Management';
  }
}
