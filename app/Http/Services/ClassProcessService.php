<?php

namespace App\Http\Services;

use App\Components\Recusive;
use App\Consts;
use App\Models\ClassProcess;
use Illuminate\Support\Facades\DB;

class ClassProcessService
{

    /**
     * Kiểm tra đã cập nhật level trước đó chưa
     */
    public static function checkLevelConditional($id,$level)
    {
        $classProcess = ClassProcess::find($id);

        switch (true) {
            case $level == "a11":
                return true;

            case $level == "a12":
                $result=$classProcess->a11;
                if($result !="") return true;
                else return false;

            case $level == "a21":
                $result=$classProcess->a12;
                if($result !="") return true;
                else return false;

            case $level == "a22":
                $result=$classProcess->a21;
                if($result !="") return true;
                else return false;  
                
            case $level == "b11":
                $result=$classProcess->a21;
                if($result !="") return true;
                else return false;    

            case $level == "b12":
                $result=$classProcess->b11;
                if($result !="") return true;
                else return false;  
                  
            case $level == "otcs":
                $result=$classProcess->b12;
                if($result !="") return true;
                else return false;    
        }
    }
   
}
