<?php

namespace App\Http\Controllers\Admin;

use App\Consts;
use App\Models\Field;
use App\Models\Major;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function PHPSTORM_META\type;

class PartnerController extends Controller
{
    protected $user_type;

    public function __construct()
    {
        $this->user_type = Consts::USER_TYPE['partner'];
        $this->routeDefault  = 'partners';
        $this->viewPart = 'admin.pages.partners';
        $this->responseData['module_name'] = __('Quản lý đối tác');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $params['user_type'] = $this->user_type;
        $rows = User::getSqlUser($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        // $rows = User::getSqlUser($params)->toSql();
        $this->responseData['fields'] = Field::getSqlField(['status' => Consts::STATUS['active']])->get();
        $this->responseData['rows'] =  $rows;
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['params'] = $params;
        $this->responseData['target_search'] = Consts::TARGET_SEARCH;
        return $this->responseView($this->viewPart . '.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['target_search'] = Consts::TARGET_SEARCH;
        $this->responseData['fields'] = Field::getSqlField(['status' => Consts::STATUS['active']])->get();
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
            'name' => 'required',
            'email' => "required|email|max:255|unique:users",
        ]);
        DB::beginTransaction();
        try {
            $params = $request->all();

            $params['user_type'] = $this->user_type;
            $params['status'] = Consts::STATUS['active'];
            $params['password'] = '12345679';
            $user = User::create($params);
            if (empty($params['user_code'])) {
                $user->user_code = $user->id;
                $user->save();
            }

            DB::commit();
            return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $this->responseData['user'] = User::find($id);
        $this->responseData['status'] = Consts::STATUS;
        $this->responseData['target_search'] = Consts::TARGET_SEARCH;
        $this->responseData['fields'] = Field::getSqlField(['status' => Consts::STATUS['active']])->get();
        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:users,email," . $id,
        ]);
        DB::beginTransaction();
        try {
            $params = $request->only([
                'name',
                'email',
                'phone',
                'password',
                'status',
                'user_type',
                'user_code',
                'avatar'
            ]);
            /**
             * Xử lý để update json cũ và cập nhật những json mới
             */
            $user = User::find($id);
            $arr_data = [];
            foreach ($params as $key => $value) {
                $arr_data[$key] = $value;
            }
            $arr_data['json_params'] = (array) $user->json_params;

            foreach ($request['json_params'] as $key => $value) {
                $arr_data['json_params'][$key] = $value;
            }

            $user->update($arr_data);
            /**
             * End cập nhật
             */
            DB::commit();
            return redirect()->back()->with('successMessage', __('Successfully updated!'));
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::find($id)->delete();

            return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
        } catch (Exception $ex) {
            return redirect()->back()->with('errorMessage', __($ex->getMessage()));
        }
    }
}
