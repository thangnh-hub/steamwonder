<?php

namespace App\Http\Services;

use App\Consts;
use App\Models\User;
use App\Models\Course;
use App\Models\LessonSylabu;
use App\Models\LessonUser;
use App\Models\tbClass;
use App\Models\Area;
use App\Models\Syllabus;
use App\Models\UserClass;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class UserService
{

    public static function createClassOnline($user, $courses, $syllabus)
    {
        DB::beginTransaction();
        try {
            // kiểm tra lớp online của chương trình này đã có chưa
            $params_class['course_id'] = $courses->id ?? '';
            $params_class['syllabus_id'] = $syllabus->id ?? '';
            $params_class['type'] = Consts::SYLLABUS_TYPE['elearning'];
            $class = tbClass::getSqlClass($params_class)->first();
            // lấy khu vực Online
            $area_online = Area::getSqlArea(['code' => 'O'])->first();
            $room = $area_online->rooms->first();
            // Chưa có thì tạo lớp mới
            if (empty($class)) {
                $params_class['name'] = 'Lớp Online-' . ($courses->name ?? '');
                $params_class['level_id'] = $courses->level_id ?? '';
                $params_class['syllabus_id'] = $syllabus->id;
                $params_class['course_id'] = $courses->id ?? '';
                $params_class['start_date'] = date('Y-m-d', time());
                $params_class['period_id'] = '1';
                $params_class['assistant_teacher'] = '["0"]';
                $params_class['status'] = 'dang_hoc';
                $params_class['area_id'] = $area_online->id;
                $params_class['room_id'] = $room->id;
                $params_class['json_params']['teacher'] = '0';
                $params_class['json_params']['day_repeat'] = ['0'];
                $class = tbClass::create($params_class);
            }
            DB::commit();
            return $class;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public static function createCourseOnline($syllabus)
    {
        DB::beginTransaction();
        try {
            $params_course['type'] = Consts::SYLLABUS_TYPE['elearning'];
            $params_course['syllabus_id'] = $syllabus->id;
            $params_course['status'] = Consts::STATUS['active'];
            $courses = Course::getSqlCourse($params_course)->first();
            if(empty($courses)){
                $params_course['name'] = 'Khóa Online-' . ($syllabus->level->name ?? '');
                $params_course['level_id'] = $syllabus->level_id ?? '';
                $courses = Course::create($params_course);
            }
            DB::commit();
            return $courses;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public static function addStudentClass($id_user, $class_id)
    {
        // kiểm tra hs có trong lớp chưa
        $params_hv['class_id'] = $class_id;
        $params_hv['user_id'] = $id_user;
        $params_hv['status'] = 'hocmoi';
        $user_class = UserClass::getSqlUserClass($params_hv)->first();
        if (empty($user_class)) {
            $user_class = UserClass::create($params_hv);
        }
        return $user_class;
    }

    public function getUsers($params = [], $isPaginate = false)
    {
        $query = User::select('users.*')
            ->selectRaw('tb_countrys.name AS country, tb_citys.name AS city')

            ->leftJoin('tb_countrys', 'tb_countrys.id', '=', 'users.country_id')
            ->leftJoin('tb_citys', 'tb_citys.id', '=', 'users.city_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('users.email', 'like', '%' . $params['keyword'] . '%')
                        ->orWhere('users.name', 'like', '%' . $params['keyword'] . '%');
                });
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('users.status', $params['status']);
            })
            ->when(!empty($params['user_type']), function ($query) use ($params) {
                return $query->where('users.user_type', $params['user_type']);
            })
            ->when(!empty($params['country_id']), function ($query) use ($params) {
                return $query->where('users.country_id', $params['country_id']);
            });
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('users.' . $key, $value);
                }
            } else {
                $query->orderByRaw('users.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('users.id desc');
        }

        if ($isPaginate) {
            $limit = Arr::get($params, 'limit', Consts::DEFAULT_PAGINATE_LIMIT);
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public static function getPermisisonSyllabus($id)
    {
        $params['customer_id'] = $id;
        $params['status'] = true;
        $arr_id_sylalbus = [];
        $get_syllabus = Order::getOrderCourses($params)->get();
        if ($get_syllabus) {
            foreach ($get_syllabus as $item) {
                $arr_id_sylalbus[] = $item->syllabus_id;
            }
        }
        return $arr_id_sylalbus;
    }

    public static function getPermisisonLesson($id_lesson)
    {
        $return = false;
        $syllabus = LessonSylabu::where('id', $id_lesson)->first();
        if ($syllabus) {
            // lấy thông tin bài học trước đó
            $prevLesson = LessonSylabu::where('id', '<', $syllabus->id)->where('syllabus_id', $syllabus->syllabus_id)->orderBy('id', 'DESC')->first();
            // check đã đủ điều kiện học khóa này chưa
            $getAllLessonUser = new UserService;
            $all_lesson_user = $getAllLessonUser->getAllLessonUser();
            if ($prevLesson == null || in_array($prevLesson->id, $all_lesson_user)) {
                $return = true;
            }
        }
        return $return;
    }

    // (K có chỗ nào gọi hàm này)Thêm tiến trình học của từng bài học vào json_params
    // public function AddProgress($id, $tab)
    // {
    //     $lesson_user = LessonUser::find($id);
    //     if ($lesson_user) {
    //         $lessonUserParams = (array) $lesson_user->json_params;
    //         $lesson_user->json_params = array_merge($lessonUserParams, [
    //             'progress' => $tab
    //         ]);
    //         $lesson_user->save();
    //     }
    // }
    /**
     *  check buổi học đàu tiên
     */
    public static function CheckLessonFirst($id_lesson)
    {
        $return = false;
        $syllabus = LessonSylabu::find($id_lesson);
        if ($syllabus) {
            $lesson = LessonSylabu::where('syllabus_id', $syllabus->syllabus_id)->orderBy('id', 'ASC')->first();
            if ($lesson->id == $id_lesson) {
                $return = true;
            }
        }
        return $return;
    }

    public static function getAllLessonUser()
    {
        $arr_id_lesson = [];
        if (Auth::guard('web')->check() == true) {
            $lesson_user = LessonUser::where('user_id', Auth::guard('web')->user()->id)->where('percent_point', '>', Consts::PERCENT_PASS)->get();
            if ($lesson_user) {
                foreach ($lesson_user as $item) {
                    $arr_id_lesson[] = $item->lesson_id;
                }
            }
            return $arr_id_lesson;
        }
    }

    public static function getPreviousNextLesson($list_lesson, $id_lesson, $tab)
    {
        // Lấy index của buổi học hiện tại và tab hiện tại
        $tab_index = array_search($tab, Consts::TAB_LESSON);
        $lesson_index = $list_lesson->search(function ($item) use ($id_lesson) {
            return $item->id == $id_lesson;
        });
        // Lấy buổi học trước và tab trước
        $previousLesson = $lesson_index > 0 ? $list_lesson[$lesson_index - 1] : null;
        $data['previous_lesson'] = $previousLesson;
        $data['previous_tab'] =  $tab_index > 0 ? Consts::TAB_LESSON[$tab_index - 1] : null;
        // Lấy buổi học sau và tab sau
        $nextLesson = $lesson_index < $list_lesson->count() - 1 ? $list_lesson[$lesson_index + 1] : null;
        $data['next_lesson'] = $nextLesson;
        $data['next_tab'] =  $tab_index < count(Consts::TAB_LESSON) - 1 ? Consts::TAB_LESSON[$tab_index + 1] : null;
        $data['z_index'] =  $lesson_index;

        // Buổi học hiện tại
        $data['default'] =  $list_lesson[$lesson_index];

        return $data;
    }

    public static function updateTabAction($lesson_id, $tab)
    {
        $user = Auth::guard('web')->user();
        $lesson_user = self::createLessonUsser($user->id, $lesson_id);
        if ($lesson_user && $tab != '') {
            $json_params = (array)$lesson_user->json_params;
            $tab_active = $json_params['tab_active'] ?? [];
            if (!in_array($tab, $tab_active)) {
                $tab_active[] = $tab;
            }
            $json_params['tab_active'] = $tab_active;
            $lesson_user->json_params = $json_params;
            $lesson_user->save();
            return $lesson_user;
        }
        return false;
    }

    public static function createLessonUsser($user_id, $lesson_id)
    {
        $lesson_user = LessonUser::where('lesson_id', $lesson_id)->where('user_id', $user_id)->first();
        if (empty($lesson_user)) {
            // chưa có thì tạo mới
            $params_create['lesson_id'] = $lesson_id;
            $params_create['user_id'] = $user_id;
            $params_create['percent_point'] = 0;
            $params_create['json_params']['tab_active'] = [];
            $lesson_user = LessonUser::create($params_create);
        }
        return $lesson_user;
    }
}
