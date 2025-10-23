<?php

namespace Almas\Permission\Http\Controllers;

class DashboardController
{
    public function index()
    {
        return view('Permission::backend.pages.dashboard');
    }
}
