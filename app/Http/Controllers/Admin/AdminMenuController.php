<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminMenuController extends Controller
{

    public function __construct()
    {
        $this->routeDefault  = 'admin_menus';
        $this->viewPart = 'admin.pages.admin_menus';
        $this->responseData['module_name'] = __('Admin Menu Management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $rows = AdminMenu::orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $rows = DB::table('tb_admin_menus AS a')
            ->selectRaw('a.*, count(b.id) AS submenu')
            ->leftJoin('tb_admin_menus AS b', 'a.id', '=', 'b.parent_id')
            ->groupBy('a.id')
            ->orderByRaw('a.iorder ASC, a.id DESC')->get();

        $this->responseData['rows'] =  $rows;

        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get all menu which have parent_id is null or = 0
        // $rootMenus = AdminMenu::where('parent_id', null)->orWhere('parent_id', 0)->orderBy('iorder')->get();
        $rootMenus = DB::table('tb_admin_menus AS a')
            ->selectRaw('a.*, count(b.id) AS submenu')
            ->leftJoin('tb_admin_menus AS b', 'a.id', '=', 'b.parent_id')
            ->where('a.status', Consts::USER_STATUS['active'])
            ->groupBy('a.id')
            ->orderByRaw('a.iorder ASC, a.id DESC')->get();

        $this->responseData['rootMenus'] = $rootMenus;

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
        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        AdminMenu::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function show(AdminMenu $adminMenu)
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminMenu $adminMenu)
    {
        // Get all menu which have parent_id is null or = 0
        // $rootMenus = AdminMenu::where('status', 'active')->where('id', '!=', $adminMenu->id)->orderBy('iorder')->get();
        $rootMenus = DB::table('tb_admin_menus AS a')
            ->selectRaw('a.*, count(b.id) AS submenu')
            ->leftJoin('tb_admin_menus AS b', 'a.id', '=', 'b.parent_id')
            ->where('a.status', Consts::USER_STATUS['active'])
            ->where('a.id', '!=', $adminMenu->id)
            ->groupBy('a.id')
            ->orderByRaw('a.iorder ASC, a.id DESC')->get();

        $this->responseData['rootMenus'] = $rootMenus;
        $this->responseData['adminMenu'] = $adminMenu;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminMenu $adminMenu)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $adminMenu->fill($params);
        $adminMenu->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminMenu  $adminMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminMenu $adminMenu)
    {
        $adminMenu->delete();
        // Delete sub
        DB::table('tb_admin_menus')->where('parent_id', '=', $adminMenu->id)->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
