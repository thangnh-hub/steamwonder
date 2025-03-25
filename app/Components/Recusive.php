<?php

namespace App\Components;

use Illuminate\Support\Facades\Log;

class Recusive
{
    private $arrID;
    private $arrParentID;
    public function __construct()
    {
        $this->arrID = [];
        $this->arrParentID = [];
    }
    public function staffAdmissionAllChild($data, $id)
    {
        foreach ($data as $value) {
            if ($value['parent_id'] == $id && !in_array($value['id'], $this->arrID)) {
                $this->arrID[] = $value['id'];
                $this->staffAdmissionAllChild($data, $value["id"]);
            }
        }
        return $this->arrID;
    }
    public function staffAdmissionAllParent($data, $id)
    {
        foreach ($data as $value) {
            if ($value['id'] == $id && $value['parent_id'] !== null && !in_array($value['parent_id'], $this->arrParentID)) {
                $this->arrParentID[] = $value['parent_id'];
                $this->staffAdmissionAllParent($data, $value['parent_id']);
            }
        }
        return $this->arrParentID;
    }
}
