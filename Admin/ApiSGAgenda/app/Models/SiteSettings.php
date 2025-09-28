<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'site_setting_code',
        'owner_code',
        'theme_color',
        'site_color',
        'bg_card_color',
        'bg_btn_color',
        'contact_phone',
        'slogan',
    ];
}
