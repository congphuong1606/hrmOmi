<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Intervention\Image\ImageManager;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function resize($image, $dir, $name, $defaultWidth, $defaultHeight)
    {
        list($width, $height) = getimagesize($image);
        if ($width < $defaultWidth && $height < $defaultHeight) {
            $resizeWidth = $width;
            $resizeHeight = $height;
        } else {
            if ($width > $height) {
                $scale = $defaultWidth / $width;
                $resizeWidth = $defaultWidth;
                $resizeHeight = $height * $scale;
            } else {
                $scale = $defaultHeight / $height;
                $resizeHeight = $defaultHeight;
                $resizeWidth = $width * $scale;
            }
        }
        $manager = new ImageManager();
        $imageResize = $manager->make($image);
        if (!is_dir(public_path($dir))) {
            mkdir(public_path($dir), 0777, true);
        }
        $path = public_path($dir . $name . '.' . RESIZE_IMAGE_FORMAT);
        $imageResize->resize($resizeWidth, $resizeHeight)->save($path);
    }

    public function resizeAvatar($image, $dir, $name)
    {
        $this->resize($image, $dir, $name, DEFAULT_AVATAR_WIDTH, DEFAULT_AVATAR_HEIGHT);
    }

    public function resizeImage($image, $dir, $name)
    {
        $this->resize($image, $dir, $name, DEFAULT_IMAGE_WIDTH, DEFAULT_IMAGE_HEIGHT);
    }

    public function getUniqueImageName()
    {
        return uniqid(PREFIX_IMAGE_NAME, true) . microtime(true);
    }

    public function normalizeLimit($limit)
    {
        return $limit === null ? DEFAULT_PAGINATION_LIMIT : (int)$limit;
    }

    public function normalizePage($page)
    {
        return $page === null ? DEFAULT_PAGINATION_PAGE : (int)$page;
    }

    public function save_employee_attach_file($dir, $file, $name)
    {
        if (!is_dir(storage_path($dir))) {
            mkdir(storage_path($dir), 0777, true);
        }
        $file->storeAs($dir, $name);
    }

    public function getEmployeeAttachFileName($file) {
        $extension = $file->getClientOriginalExtension();
        $base_name = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
        $name = $this->str_file_filter($base_name) . uniqid('_') . '.' . $extension;
        return $name;
    }

    public function getEmployeeExcelFileName($file) {
        $extension = $file->getClientOriginalExtension();
        $base_name = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
        $name = $this->str_file_filter($base_name) . uniqid('_') . '.' . $extension;
        return $name;
    }

    public function getTimeOnExcelFileName($file) {
//        $extension = $file->getClientOriginalExtension();
        $base_name = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
//        $name = $this->str_file_filter($base_name) . uniqid('_') . '.' . $extension;
//        return $name;
        return $base_name;
    }

    public function getCourseScoreExcelFileName($file) {
        $extension = $file->getClientOriginalExtension();
        $base_name = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
        $name = $this->str_file_filter($base_name) . uniqid('_') . '.' . $extension;
        return $name;
    }

    public function isAdmin() {
        return User::query()->where('id', '=', \Auth::id())
            ->where('email', '=', ADMIN_EMAIL)
            ->first() !== null;
    }

    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }

// Returns filesystem-safe string after cleaning, filtering, and trimming input
    public  function str_file_filter(
        $str,
        $sep = '_',
        $strict = false,
        $trim = 248) {

        $str = strip_tags(htmlspecialchars_decode($str)); // decode -> strip tags
        $str = str_replace("%20", ' ', $str); // convert rogue %20s into spaces
        $str = preg_replace("/%[a-z0-9]{1,2}/i", '_', $str); // remove hexy things
        $str = str_replace("&nbsp;", ' ', $str); // convert all nbsp into space
        $str = preg_replace("/&#?[a-z0-9]{2,8};/i", '_', $str); // remove the other non-tag things
//        $str = preg_replace("/\s+/", $sep, $str); // filter multiple spaces
//        $str = preg_replace("/\.+/", '.', $str); // filter multiple periods
        $str = preg_replace("/^\.+/", '_', $str); // trim leading period

        if ($strict) {
            $str = preg_replace("/([^\w\d\\" . $sep . ".])/", '', $str); // only allow words and digits
        } else {
            $str = preg_replace("/([^\w\d\\" . $sep . "\[\]\(\).])/", '', $str); // allow words, digits, [], and ()
        }

        $str = preg_replace("/\\" . $sep . "+/", $sep, $str); // filter multiple separators
        $str = substr($str, 0, $trim); // trim filename to desired length, note 255 char limit on windows

        return $str;
    }

}
