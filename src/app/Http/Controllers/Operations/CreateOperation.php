<?php

namespace Wymanliu01\BackpackAddons\app\Http\Controllers\Operations;

use Exception;
use Illuminate\Http\RedirectResponse;

/**
 * Trait CreateOperation
 * @package Wymanliu01\BackpackAddons\app\Http\Controllers\Operations
 */
trait CreateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as public _store;
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(): RedirectResponse
    {
        $this->beforeStore();

        $return = $this->_store();

        $this->afterStoreOperation();

        return $return;
    }

    /**
     * @throws Exception
     */
    private function beforeStore()
    {
        if (!method_exists($this->crud->model, 'checkModelProperly')) {
            $class = get_class($this->crud->model);
            throw new Exception("Interface WithBackpackOperation in class $class is not set up correctly");
        }

        $this->crud->model->checkModelProperly();
    }

    /**
     *
     */
    private function afterStoreOperation()
    {
        if (in_array(ImageOperation::class, class_uses($this))) {
            $this->saveImageFields($this->data['entry'], $this->crud->getStrippedSaveRequest());
        }
    }
}
