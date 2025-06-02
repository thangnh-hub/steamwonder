<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $rows = User::getSqlUser($params)->orderBy('users.id', 'DESC')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['area'] = Area::getsqlArea(['status' => Consts::STATUS['active']])->get();
        $this->responseData['status'] = Consts::USER_STATUS;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {

        return $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $request->validate([
            'username' => 'required|unique:users|max:255',
            'password' => "required|min:8|max:255",
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => "required",
        ]);
        $params = $request->only([
            'username',
            'password',
            'first_name',
            'last_name',
            'phone',
            'address',
            'email',
            'avatar',
            'status'
        ]);
        $params['email'] = (!empty($params['email'])) ? $params['email'] : $params['username'] . '@example.com';
        $params['admin_created_id'] = $admin->id;
        $params['admin_updated_id'] = $admin->id;

        User::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit(User $user)
    {
        $this->responseData['detail'] = $user;
        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => "required",
            'password_new' => 'nullable|min:8',
        ]);
        DB::beginTransaction();
        try {
            $params = $request->only([
                'first_name',
                'last_name',
                'phone',
                'address',
                'email',
                'avatar',
                'status'
            ]);

            if ($request->filled('password_new')) {
                $params['password'] = $request->input('password_new');
            }
            $params['admin_updated_id'] = Auth::guard('admin')->user()->id;
            $user->update($params);
            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update new successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __('Có lỗi xảy ra, vui lòng thử lại!'));
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
