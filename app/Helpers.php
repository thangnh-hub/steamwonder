<?php

namespace App;

use Illuminate\Support\Str;
use Hashids\Hashids;


class Helpers
{
    public static function generateRoute($route, $title, $id, $is_type = null, $taxonomy_title = null)
    {
        if ($is_type) {
            if (isset(Consts::ROUTE_POST[$route])) {
                $alias = Str::slug($title);
                $taxonomy_title = Str::slug($taxonomy_title);
                $route = route(Consts::ROUTE_POST[$route], ['alias' => $alias, 'alias_category' => $taxonomy_title]);
            }
        } else {
            if (isset(Consts::ROUTE_TAXONOMY[$route])) {
                $alias = Str::slug($title);
                $route = route(Consts::ROUTE_TAXONOMY[$route], ['alias_category' => $alias]);
            }
        }
        return $route;
    }

    public static function getIdFromAlias($slug)
    {
        $id = null;

        if (Str::contains($slug, '.html')) {
            $slug = Str::afterLast(Str::before($slug, '.html'), '-');

            $id = Str::afterLast($slug, '-');
        } else {
            $id = Str::afterLast($slug, '-');
        }

        return $id;
    }

    public static function getRouteLessonDetail($syllabus_title, $syllabus_id, $id, $tab = '')
    {
        $alias = Str::slug($syllabus_title) . '-' . $syllabus_id;
        $hashids = new Hashids('', 6);
        $lesson = $hashids->encode($id);
        $tab = $tab != '' ? $tab : 'learning';
        $route = route('frontend.lesson.detail', [
            'alias' => $alias,
            'lesson' => $lesson,
            'tab' => $tab,
        ]);
        return $route;
    }
}
