<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Exception;
use App\Consts;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|max:255',
                'comment' => 'required|max:255'
            ]);

            $params = $request->except('_token', '_method');
            $params['status'] = Consts::STATUS['active'];
            $insert = Comment::insert($params);
            return redirect()->back()->with('successMessage', 'Gửi thành công');

        } catch (Exception $ex) {
            // throw $ex;
            abort(422, __($ex->getMessage()));
        }
    }
}
