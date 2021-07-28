<?php

namespace Wymanliu01\BackpackAddons\app\Http\Controllers\Operations;

/**
 * Trait ImageOperation
 * @package Wymanliu01\BackpackAddons\app\Http\Controllers\Operations
 */
trait ImageOperation
{
    /**
     * @param $instance
     * @param $inputData
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function saveImageFields($instance, $inputData)
    {
        if (empty($instance->imageable)){
            return;
        }

        foreach ($instance->imageable as $column) {
            $instance->$column = $inputData[$column];
        }
    }

    /**
     * @param $instance
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function destroyEntryImages($instance)
    {
        foreach ($instance->imageable as $column) {
            $instance->$column = null;
        }
    }
}
