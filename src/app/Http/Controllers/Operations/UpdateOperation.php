<?php

namespace Wymanliu01\BackpackAddons\app\Http\Controllers\Operations;

use Exception;
use Illuminate\Http\Response;

/**
 * Trait UpdateOperation
 * @package Wymanliu01\BackpackAddons\app\Http\Controllers\Operations
 */
trait UpdateOperation
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as public _update;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function update(): Response
    {
        $this->beforeUpdate();
        $return = $this->_update();
        $this->afterUpdate();

        return $return;
    }

    /**
     * @throws Exception
     */
    private function beforeUpdate()
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
    private function afterUpdate()
    {
        if (in_array(ImageOperation::class, class_uses($this))) {
            $this->saveImageFields($this->data['entry'], $this->crud->getStrippedSaveRequest());
        }
    }
}
