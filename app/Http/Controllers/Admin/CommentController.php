<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Models\CmsPost;
use Illuminate\Http\Request;
use App\Consts;

class CommentController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->routeDefault  = 'comments';
    $this->viewPart = 'admin.pages.comments';
    $this->responseData['module_name'] = __('Comments setting');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $rows = Comment::orderBy('id')->paginate(Consts::DEFAULT_PAGINATE_LIMIT);
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
    return redirect()->back();
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    return redirect()->back();
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function show(Comment $comment)
  {
    return redirect()->back();
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function edit(Comment $comment)
  {
    $this->responseData['detail'] = $comment;
    if ($comment->id_post > 0) {
      $param['id'] = $comment->id_post;
      $param['is_type'] = Consts::TAXONOMY['post'];
      $this->responseData['posts'] = CmsPost::getsqlCmsPost($param)->first();
    }
    return $this->responseView($this->viewPart . '.edit');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Comment $comment)
  {
    $params = $request->all();
    $comment->fill($params);
    $comment->save();
    return redirect()->back()->with('successMessage', __('Successfully updated!'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Comment  $comment
   * @return \Illuminate\Http\Response
   */
  public function destroy(Comment $comment)
  {
    $comment->status = Consts::STATUS_DELETE;
    $comment->save();
    return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Delete record successfully!'));
  }
}
