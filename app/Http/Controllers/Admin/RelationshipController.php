<?php

namespace App\Http\Controllers\Admin;

use App\Models\Relationship;
use Illuminate\Support\Facades\Auth;
use App\Consts;
use App\Models\Admin;
use Illuminate\Http\Request;

class RelationshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function __construct()
    {
        parent::__construct();
        $this->routeDefault = 'relationships';
        $this->viewPart = 'admin.pages.relationships';
        $this->responseData['module_name'] = 'Quản lý mối quan hệ';
    }


    public function index(Request $request)
    {
        $params = $request->all();
        $rows = Relationship::getSqlRelationship($params)->paginate(Consts::DEFAULT_PAGINATE_LIMIT);

        $this->responseData['rows'] = $rows;
        $this->responseData['params'] = $params;
        $this->responseData['list_status'] = Consts::STATUS;

        return $this->responseView($this->viewPart . '.index');
    }

    public function create()
    {
        $this->responseData['list_status'] = Consts::STATUS;
        return $this->responseView($this->viewPart . '.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'iorder' => 'nullable|integer',
        ]);

        $params = $request->all();
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;

        Relationship::create($params);
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    public function edit(Relationship $relationship)
    {
        $this->responseData['detail'] = $relationship;
        $this->responseData['list_status'] = Consts::STATUS;
        return $this->responseView($this->viewPart . '.edit');
    }

    public function update(Request $request, Relationship $relationship)
    {
        $request->validate([
            'title' => 'required|max:255',
            'iorder' => 'nullable|integer',
        ]);

        $params = $request->all();
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $relationship->update($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Update successfully!'));
    }

    public function destroy(Relationship $relationship)
    {
        $relationship->delete();
        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
    }
}
