<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingsModel;

class Settings extends BaseController
{
    protected $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        $data['settings'] = $this->settingsModel->findAll();
        return view('admin/settings/index', $data);
    }

    public function save()
    {
        $settings = $this->request->getPost('settings');
        if (is_array($settings)) {
            foreach ($settings as $key => $value) {
                $this->settingsModel->setSetting($key, $value);
            }
            return redirect()->to('admin/settings')->with('success', lang('Sparks.settings_saved'));
        }
        return redirect()->to('admin/settings')->with('error', 'No settings received');
    }
}
