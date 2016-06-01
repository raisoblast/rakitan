<?php
namespace Lib;

use Model;

class MyAuth
{
    private $db;
    private $uid;
    private $nama;
    private $role;
    private $hakAkses;

    public function __construct(\Illuminate\Database\Connection $db)
    {
        $this->db = $db;
    }

    public function init($uid)
    {
        $user = Model\User::find($uid);
        if ($user) {
            $this->uid = $uid;
            $this->nama = $user->nama;
            $this->role = $user->role;
            $grup = Model\Grup::where('nama', $user->role)->first();
            if ($grup) {
                $this->hakAkses = json_decode($grup->hak_akses, true);
            }
        }
    }

    public function isAuthenticated()
    {
        return $this->uid != null;
    }

    public function getUserId()
    {
        return $this->uid;
    }

    public function getNama()
    {
        return $this->nama;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function isAdmin()
    {
        return $this->role == 'administrator';
    }


    public function getHakAkses()
    {
        return $this->hakAkses;
    }

    public function hasAccess($permissions)
    {
        if ($this->isAdmin()) {
            return true;
        }
        if (!is_array($this->hakAkses)) {
            return false;
        }
        if (is_string($permissions)) {
            $permissions = func_get_args();
        }
        foreach ($permissions as $permission) {
            if (!(array_key_exists($permission, $this->hakAkses) && $this->hakAkses[$permission] === true)) {
                return false;
            } 
        }
        return true;
    }

    public function hasAnyAccess($permissions)
    {
        if ($this->isAdmin()) {
            return true;
        }
        if (!is_array($this->hakAkses)) {
            return false;
        }
        if (is_string($permissions)) {
            $permissions = func_get_args();
        }
        foreach ($permissions as $permission) {
            if (array_key_exists($permission, $this->hakAkses) && $this->hakAkses[$permission] === true) {
                return true;
            } 
        }
        return false;
    }
}
