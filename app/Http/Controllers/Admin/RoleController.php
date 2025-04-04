<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Consts;
use App\Models\AdminMenu;
use App\Models\ModuleFunction;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'roles';
    $this->viewPart = 'admin.pages.roles';
    $this->responseData['module_name'] = __('Roles and Permissions');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $rows = Role::orderByRaw('status ASC, iorder ASC, id DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

    $this->responseData['rows'] = $rows;

    return $this->responseView($this->viewPart . '.index');
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // $activeMenus = AdminMenu::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $activeMenus = DB::table('tb_admin_menus AS a')
      ->selectRaw('a.*, count(b.id) AS submenu')
      ->leftJoin('tb_admin_menus AS b', 'a.id', '=', 'b.parent_id')
      ->where('a.status', Consts::USER_STATUS['active'])
      ->groupBy('a.id')
      ->orderByRaw('a.iorder ASC, a.id DESC')->get();
    $activeModules = Module::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $activeFunctions = ModuleFunction::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();

    $this->responseData['activeModules'] = $activeModules;
    $this->responseData['activeFunctions'] = $activeFunctions;
    $this->responseData['activeMenus'] = $activeMenus;

    return $this->responseView($this->viewPart . '.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|max:255',
    ]);

    $params = $request->only([
      'name',
      'description',
      'iorder',
      'status',
      'json_access',
    ]);

    $params['admin_created_id'] = Auth::guard('admin')->user()->id;
    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

    Role::create($params);

    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function show(Role $role)
  {
    // Do not use this function
    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function edit(Role $role)
  {
    // $activeMenus = AdminMenu::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $activeMenus = DB::table('tb_admin_menus AS a')
      ->selectRaw('a.*, count(b.id) AS submenu')
      ->leftJoin('tb_admin_menus AS b', 'a.id', '=', 'b.parent_id')
      ->where('a.status', Consts::USER_STATUS['active'])
      ->groupBy('a.id')
      ->orderByRaw('a.iorder ASC, a.id DESC')->get();
    $activeModules = Module::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
    $activeFunctions = ModuleFunction::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();

    $this->responseData['activeModules'] = $activeModules;
    $this->responseData['activeFunctions'] = $activeFunctions;
    $this->responseData['activeMenus'] = $activeMenus;
    $this->responseData['detail'] = $role;

    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Role $role)
  {
    $request->validate([
      'name' => 'required|max:255',
    ]);

    $params = $request->only([
      'name',
      'description',
      'iorder',
      'status',
      'json_access',
    ]);

    $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

    $role->fill($params);
    $role->save();

    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Role  $role
   * @return \Illuminate\Http\Response
   */
  public function destroy(Role $role)
  {
    $role->delete();

    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }
}
