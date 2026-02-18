<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Setting;

class SettingsModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Setting::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['key', 'value', 'type'];

    protected $useTimestamps = false;

    private static $cache = [];

    public function getSetting(string $key)
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $setting = $this->where('key', $key)->first();
        if ($setting) {
            self::$cache[$key] = $setting->getValue();
            return self::$cache[$key];
        }

        return null;
    }

    public function setSetting(string $key, $value)
    {
        $setting = $this->where('key', $key)->first();
        
        $data = [
            'key'   => $key,
            'value' => is_array($value) ? json_encode($value) : (string)$value,
        ];

        if ($setting) {
            $this->update($setting->id, $data);
        } else {
            $this->insert($data);
        }

        unset(self::$cache[$key]);
    }
}
