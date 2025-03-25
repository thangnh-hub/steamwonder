<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Consts;
use App\Models\BlockContent;
use App\Models\Component;
use App\Models\Page;
use App\Models\Widget;
use App\Models\CmsProduct;
use App\Models\CmsPost;
use App\Models\CmsRelationship;
use App\Models\CmsTaxonomy;
use App\Models\Menu;
use App\Models\Parameter;
use App\Models\Review;
use App\Models\Comment;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;


use stdClass;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get all element to build page and
     * @return \Illuminate\Http\Response
     */
    // Home
    // Page
    // Taxonomy
    // Detail


    public function index(Request $request, $taxonomy = null, $alias = null)
    {

        $seo_title = ($this->responseData['locale'] == $this->responseData['lang_default']) ? $this->responseData['setting']->seo_title : $this->responseData['setting']->{$this->responseData['locale'] . '-seo_title'} ?? '';
        $seo_keyword = ($this->responseData['locale'] == $this->responseData['lang_default']) ? $this->responseData['setting']->seo_keyword : $this->responseData['setting']->{$this->responseData['locale'] . '-seo_keyword'} ?? '';
        $seo_description = ($this->responseData['locale'] == $this->responseData['lang_default']) ? $this->responseData['setting']->seo_description : $this->responseData['setting']->{$this->responseData['locale'] . '-seo_description'} ?? '';
        $seo_image = $this->responseData['setting']->seo_og_image ?? '';

        // home page
        if ($taxonomy == null && $alias == null) {
            $params['route_name'] = Route::getCurrentRoute()->getName();
            $params['status'] = Consts::STATUS['active'];
            $page = Page::getSqlPage($params)->first();
            $this->buildPage($page->json_params);

            $this->responseData['page'] = $page;
            $this->responseData['meta']['seo_title'] = $page->json_params->title->{$this->responseData['locale']} ?? ($page->title ?? $seo_title);
            $this->responseData['meta']['seo_keyword'] = $page->json_params->seo_keyword->{$this->responseData['locale']} ?? ($page->seo_keyword ?? $seo_keyword);
            $this->responseData['meta']['seo_description'] = $page->json_params->seo_description->{$this->responseData['locale']} ?? ($page->seo_description ?? $seo_description);
            $this->responseData['meta']['seo_image'] = $page->image ?? $seo_image;
            if (View::exists('frontend.pages.' . $page->route_name . '.' . $page->json_params->template)) {
                return $this->responseView('frontend.pages.' . $page->route_name . '.' . $page->json_params->template);
            } else {
                return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
            }
        }
        // check page hoặc taxonomy
        if ($taxonomy != null && $alias == null) {
            // Check trong bang page
            $params['alias'] = $taxonomy;
            $params['status'] = Consts::STATUS['active'];
            $page = Page::getSqlPage($params)->first();
            if ($page) {
                $this->buildPage($page->json_params);
                $this->responseData['page'] = $page;
                $this->responseData['meta']['seo_title'] = $page->json_params->title->{$this->responseData['locale']} ?? ($page->title ?? $seo_title);
                $this->responseData['meta']['seo_keyword'] = $page->json_params->seo_keyword->{$this->responseData['locale']} ?? ($page->seo_keyword ?? $seo_keyword);
                $this->responseData['meta']['seo_description'] = $page->json_params->seo_description->{$this->responseData['locale']} ?? ($page->seo_description ?? $seo_description);
                $this->responseData['meta']['seo_image'] = $page->image ?? $seo_image;
                if (View::exists('frontend.pages.' . $page->route_name . '.' . $page->json_params->template)) {
                    return $this->responseView('frontend.pages.' . $page->route_name . '.' . $page->json_params->template);
                } else {
                    return redirect()->route('home')->with('errorMessage', __('Page không tồn tại!'));
                }
            }
            // Check trong bang taxonomy
            $taxonomy = CmsTaxonomy::getSqlTaxonomy($params)->first();
            if ($taxonomy) {
                $this->buildPage($taxonomy->json_params);
                $this->responseData['page'] = $taxonomy;
                $this->responseData['meta']['seo_title'] = $taxonomy->json_params->name->{$this->responseData['locale']} ?? ($taxonomy->name ?? $seo_title);
                $this->responseData['meta']['seo_keyword'] = $taxonomy->json_params->seo_keyword->{$this->responseData['locale']} ?? ($taxonomy->seo_keyword ?? $seo_keyword);
                $this->responseData['meta']['seo_description'] = $taxonomy->json_params->seo_description->{$this->responseData['locale']} ?? ($taxonomy->seo_description ?? $seo_description);
                $this->responseData['meta']['seo_image'] = $taxonomy->json_params->image ?? $seo_image;

                $params_post['status'] = Consts::STATUS['active'];
                $params_post['order_by'] = 'iorder';
                $params_post['is_type'] = $taxonomy->taxonomy;
                // lấy tất cả danh mục
                $data_taxonomy['status'] = Consts::STATUS['active'];
                $data_taxonomy['order_by'] = ['iorder' => 'ASC'];
                $data_taxonomy['count'] = true;
                $this->responseData['taxonomys'] = CmsTaxonomy::getSqlTaxonomy($data_taxonomy)->get();

                // lấy danh mục nổi bật
                $feature_taxonomy = collect($this->responseData['taxonomys'])->filter(function ($item,  $key) {
                    return isset($item->json_params->is_featured) && $item->json_params->is_featured == true && $item->taxonomy == Consts::TAXONOMY['post'] ;
                })->take(Consts::LIMIT_TAXONOMY['post']);
                // lấy bài viết xem nhiều
                $params_visited_post['order_by'] = ['count_visited' => 'DESC'];
                $visited_post = CmsPost::getSqlCmsPost($params_visited_post)->limit(Consts::LIMIT_TAXONOMY['sidebar'])->get();
                // lấy bài viết nổi bật
                $params_featured_post['is_featured'] = true;
                $featured_post = CmsPost::getSqlCmsPost($params_featured_post)->limit(Consts::LIMIT_TAXONOMY['sidebar'])->get();
                $this->responseData['feature_taxonomy'] = $feature_taxonomy;
                $this->responseData['visited_post'] = $visited_post;
                $this->responseData['featured_post'] = $featured_post;

                // check theo từng loại taxonomy
                switch ($taxonomy->taxonomy) {
                    case Consts::TAXONOMY['post']:
                        $params_post = $request->all();
                        $params_post['taxonomy_id'] = explode(',', $taxonomy->sub_taxonomy_id);
                        array_push($params_post['taxonomy_id'], $taxonomy->id);
                        $params_post['order_by'] = ['id' => 'DESC'];
                        $rows = CmsPost::getSqlCmsPost($params_post, $this->responseData['locale'])->paginate(Consts::PAGINATE[$taxonomy->taxonomy]);
                        $this->responseData['rows'] = $rows;
                        $this->responseData['params'] = $params_post;
                        $this->responseData['params'] = $params_post;
                        break;
                    case Consts::TAXONOMY['tag']:
                        $params_post['order_by'] = ['id' => 'DESC'];
                        $params_post['is_type'] = Consts::TAXONOMY['post'];
                        $params_post['tags'] = (string)$taxonomy->id;
                        $rows = CmsPost::getSqlCmsPost($params_post, $this->responseData['locale'])->paginate(Consts::PAGINATE['tag']);
                        $this->responseData['rows'] = $rows;
                        break;
                    default:
                        return redirect()->back()->with('errorMessage', __('Page không tồn tại!'));
                        break;
                }
                if (View::exists('frontend.pages.' . $taxonomy->json_params->route_name . '.' . $taxonomy->json_params->template)) {
                    return $this->responseView('frontend.pages.' . $taxonomy->json_params->route_name . '.' . $taxonomy->json_params->template);
                } else {
                    return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
                }
            }
            return redirect()->back()->with('errorMessage', __('Page không tồn tại!'));
        }

        // lấy chi tiết bài viết hoặc sản phẩm
        if ($alias != null && $taxonomy != '') {
            // Trường hợp này chỉ check trong bảng post
            $params['alias'] = $alias ?? '';
            $params['status'] = Consts::STATUS['active'];
            $detail = CmsPost::getSqlCmsPost($params)->first();
            if (isset($detail) && $detail != null) {
                // Tăng lượt xem
                $detail->count_visited = $detail->count_visited + 1;
                $detail->save();
                // lấy thông tin block
                $this->buildPage($detail->json_params);
                // lấy danh sách danh mục
                $data_taxonomy['status'] = Consts::STATUS['active'];
                $data_taxonomy['order_by'] = ['iorder' => 'ASC'];
                $data_taxonomy['count'] = true;
                $taxonomy = CmsTaxonomy::getSqlTaxonomy($data_taxonomy)->get();
                $this->responseData['taxonomys'] = $taxonomy;
                $this->responseData['detail'] = $detail;

                switch ($detail->is_type) {
                    // trường hợp là bài viết
                    case Consts::TAXONOMY['post']:
                        // lấy danh mục nổi bật
                        $feature_taxonomy = collect($this->responseData['taxonomys'])->filter(function ($item,  $key) {
                            return isset($item->json_params->is_featured) && $item->json_params->is_featured == true;
                        })->take(Consts::LIMIT_TAXONOMY['post']);
                        // lấy bài viết nổi bật
                        $params_post['is_featured'] = true;
                        $featured_post = CmsPost::getSqlCmsPost($params_post)->limit(Consts::LIMIT_TAXONOMY['sidebar'])->get();
                        $this->responseData['feature_taxonomy'] = $feature_taxonomy;
                        $this->responseData['featured_post'] = $featured_post;

                        $taxonomy_detail = collect($this->responseData['taxonomys'])->first(function ($item,  $key) use($detail){
                            return collect(explode(',', $detail->list_taxonomy_id))->contains($item->id);
                        });
                        $this->responseData['taxonomy_detail'] = $taxonomy_detail;
                        break;

                    default:
                    return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
                        break;
                }
                $this->responseData['meta']['seo_title'] = $detail->json_params->name->{$this->responseData['locale']} ?? ($detail->name ?? $seo_title);
                $this->responseData['meta']['seo_keyword'] = $detail->json_params->seo_keyword->{$this->responseData['locale']} ?? ($detail->seo_keyword ?? $seo_keyword);
                $this->responseData['meta']['seo_description'] = $detail->json_params->seo_description->{$this->responseData['locale']} ?? ($detail->seo_description ?? $seo_description);
                $this->responseData['meta']['seo_image'] = $detail->image ?? $seo_image;
                if (View::exists('frontend.pages.' . $detail->json_params->route_name . '.' . $detail->json_params->template)) {
                    return $this->responseView('frontend.pages.' . $detail->json_params->route_name . '.' . $detail->json_params->template);
                } else {
                    return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
                }
            }
        }

        return redirect()->route('home')->with('errorMessage', __('Page không tồn tại'));
    }


    public function buildPageDefault($json_params)
    {
        // Get Block content by page
        $params_page['route_name'] = $json_params->route_name;
        $pages = Page::getSqlPage($params_page)->get();
        $page_curent = $pages->first(function ($item) use ($json_params) {
            return $item->json_params->template = $json_params->template;
        });
        if (isset($page_curent->json_params->block_content)) {
            $params_block['template'] = $json_params->template;
            $params_block['status'] = Consts::STATUS['active'];
            $params_block['order_by'] = [
                'iorder' => 'ASC',
                'id' => 'DESC'
            ];
            $blocks = BlockContent::getSqlBlockContent($params_block)->get();
            // Reorder blockContents setting of this widget
            $block_setting = $page_curent->json_params->block_content ?? [];
            // Filter selected blockContents
            $blocks_selected = $blocks->filter(function ($item) use ($block_setting) {
                return in_array($item->id, $block_setting);
            });
            // Reorder selected blockContents
            $blocks_selected = $blocks_selected->sortBy(function ($item) use ($block_setting) {
                return array_search($item->id, $block_setting);
            });

            $this->responseData['blocks'] = $blocks;
            $this->responseData['blocks_selected'] = $blocks_selected;
            return $this->responseData;
        }

    }

    public function buildPage($json_params)
    {
        $this->responseData['menu'] = Menu::getSqlMenu(['status' => 'active', 'order_by' => ['iorder' => 'ASC']])->get();
        if (isset($json_params->block_content)) {
            $params_block['template'] = $json_params->template;
            $params_block['status'] = Consts::STATUS['active'];
            $params_block['order_by'] = [
                'iorder' => 'ASC',
                'id' => 'DESC'
            ];
            $blocks = BlockContent::getSqlBlockContent($params_block)->get();
            // Reorder blockContents setting of this widget
            $block_setting = $json_params->block_content ?? [];
            // Filter selected blockContents
            $blocks_selected = $blocks->filter(function ($item) use ($block_setting) {
                return in_array($item->id, $block_setting);
            });
            // Reorder selected blockContents
            $blocks_selected = $blocks_selected->sortBy(function ($item) use ($block_setting) {
                return array_search($item->id, $block_setting);
            });

            $this->responseData['blocks'] = $blocks;
            $this->responseData['blocks_selected'] = $blocks_selected;
            return $this->responseData;
        } else {
            $this->buildPageDefault($json_params);
        }
    }
}
