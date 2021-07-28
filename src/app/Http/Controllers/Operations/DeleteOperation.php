<?php

namespace Wymanliu01\BackpackAddons\app\Http\Controllers\Operations;

use Exception;

/**
 * Trait DeleteOperation
 * @package Wymanliu01\BackpackAddons\app\Http\Controllers\Operations
 */
trait DeleteOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as public _destroy;
    }

    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    public function destroy($id): string
    {
        $this->beforeDestroy($id);
        $return = $this->_destroy($id);
        $this->afterDestroy($id);

        return $return;
    }

    /**
     * @param $id
     * @throws Exception
     */
    private function beforeDestroy($id)
    {
        if (!method_exists($this->crud->model, 'checkModelProperly')) {
            $class = get_class($this->crud->model);
            throw new Exception("Interface WithBackpackOperation in class $class is not set up correctly");
        }

        if (in_array(ImageOperation::class, class_uses($this))) {
            $this->destroyEntryImages($this->crud->getEntry($id));
        }

        $this->crud->model->checkModelProperly();
    }

    /**
     * @param $id
     */
    private function afterDestroy($id)
    {

    }
}
