<?php

namespace BackpackAddons\app\Http\Controllers\Operations;

use Exception;

trait ImageOperation
{
    /**
     * @param $instance
     * @param $inputData
     * @throws Exception
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function saveImageFields($instance, $inputData)
    {
        $this->checkInstanceProperly($instance);

        foreach ($instance->imageable as $column) {
            $instance->$column = $inputData[$column];
        }
    }

    /**
     * @param $instance
     * @throws Exception
     */
    private function checkInstanceProperly($instance)
    {
        if (!is_array($instance->imageable)) {
            $class = get_class($instance);
            throw new Exception("Attribute \$imageable in class $class is not set up correctly");
        }

        foreach ($instance->imageable as $column) {

            $expectedAccessor = 'get' . Str::ucfirst(Str::camel($column)) . 'Attribute';
            $expectedMutator = 'set' . Str::ucfirst(Str::camel($column)) . 'Attribute';

            if (!method_exists($instance, $expectedAccessor)) {
                $class = get_class($instance);
                throw new Exception("Method $expectedAccessor in class $class is not set up correctly");
            }

            if (!method_exists($instance, $expectedMutator)) {
                $class = get_class($instance);
                throw new Exception("Method $expectedMutator in class $class is not set up correctly");
            }
        }
    }

    /**
     * @param $instance
     * @throws Exception
     * @noinspection PhpUnusedPrivateMethodInspection
     */
    private function destroyEntryImages($instance)
    {
        $this->checkInstanceProperly($instance);

        foreach ($instance->imageable as $column) {
            $instance->$column = null;
        }
    }


}
