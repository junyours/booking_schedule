<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Design extends Model
{
    protected $table = 'designs'; // This table doesn't exist but is used for abstraction

    // Define methods to fetch designs based on type
    public static function getDesignsByType($type)
    {
        $table = '';

        switch ($type) {
            case 'landscape':
                $table = 'landscapes';
                break;
            case 'swimmingpool':
                $table = 'swimmingpools';
                break;
            case 'renovation':
                $table = 'renovations';
                break;
            default:
                throw new \Exception('Invalid design type.');
        }

        return DB::table($table)->get();
    }
}
