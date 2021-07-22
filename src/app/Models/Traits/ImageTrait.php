<?php

namespace Wymanliu01\BackpackAddons\app\Models\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Wymanliu01\BackpackAddons\app\Models\Image;

trait ImageTrait
{
    /**
     * @param $column
     * @param $value
     */
    public function setImage($column, $value)
    {
        // 1.
        // when $value is a url, means $value passed the original url from cms, do nothing
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return;
        }

        $disk = config('filesystems.default');
        $thisImage = Str::after($this->getImage($column), config("filesystems.disks.$disk.url") . '/');

        // 2.
        // when $value is a base64 string, cms update or create a new image,
        // do upload file and update/create record, then delete the original file
        if (!is_null(config("filesystems.disks.$disk.root")) && Str::before($value, '/') === 'data:image') {

            $extension = Str::after(Str::before($value, ';'), '/');
            $filename = md5($value . time()) . ".$extension";
            $filePath = "images/$this->table/$column/$filename";

            $imageBase64 = base64_decode(Str::after($value, ','));

            Storage::disk($disk)->put($filePath, $imageBase64);

            if (!empty($thisImage)) {
                Storage::disk($disk)->delete($thisImage);
            }

            if (!empty($this->id)) {
                Image::updateOrCreate(
                    ['source_type' => self::class, 'source_id' => $this->id, 'column_name' => $column],
                    ['url' => $filePath]
                );
            }
        }

        // 3.
        // if $value is empty or is null, means cms didnt pass the photo or photo has been removed
        // do delete file and record if the original file and record are found
        if (empty($value)) {
            if (!empty($thisImage)) {
                Storage::disk($disk)->delete($thisImage);
            }

            Image::whereSourceType(self::class)
                ->whereSourceId($this->id)
                ->whereColumnName($column)
                ->delete();
        }
    }

    /**
     * @return string
     * @noinspection PhpUnused
     */
    public function getImage($column): ?string
    {
        if (!empty($this->id)) {

            $image = Image::whereSourceType(self::class)
                ->whereSourceId($this->id)
                ->whereColumnName($column)
                ->first();

            if (is_null($image)) {
                return null;
            }

            $disk = config('filesystems.default');
            return config("filesystems.disks.$disk.url") . "/$image->url";

        } else {
            return null;
        }
    }
}
