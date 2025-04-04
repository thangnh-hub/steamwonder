<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'users';
    $this->viewPart = 'admin.pages.users';
    $this->responseData['module_name'] = __('User management');
  }

  public function index(Request $request)
  {
    $params = $request->all();
    $this->responseData['params'] = $params;

    $rows = User::getSqlUser($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
    $this->responseData['rows'] = $rows;
    $this->responseData['area'] = Area::getsqlArea(['status' => Consts::STATUS['active']])->get();
    $this->responseData['status'] = Consts::USER_STATUS;

    return $this->responseView($this->viewPart . '.index');
  }

  public function create()
  {

    return $this->responseView($this->viewPart . '.create');
  }

  public function store()
  {

    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  public function edit(User $user)
  {

    return $this->responseView($this->viewPart . '.edit');
  }

  public function update(User $user)
  {

    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update new successfully!'));
  }

  public function destroy(User $user)
  {

    return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
  }
}
