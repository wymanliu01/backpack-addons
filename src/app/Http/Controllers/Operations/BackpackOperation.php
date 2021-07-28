<?php

namespace Wymanliu01\BackpackAddons\app\Http\Controllers\Operations;

use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Exception;
use Prologue\Alerts\Facades\Alert;

/**
 * Trait BackpackOperation
 * @package Wymanliu01\BackpackAddons\app\Http\Controllers\Operations
 */
trait BackpackOperation
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;

    /**
     * @return mixed
     * @throws Exception
     */
    public function store()
    {
        $this->processCheckModelProperly();

        $this->crud->hasAccessOrFail('create');

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        $this->processExtraStoreOperation();

        // show a success message
        Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function update()
    {
        $this->processCheckModelProperly();

        $this->crud->hasAccessOrFail('update');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();
        // update the row in the db
        $item = $this->crud->update($request->get($this->crud->model->getKeyName()),
            $this->crud->getStrippedSaveRequest());
        $this->data['entry'] = $this->crud->entry = $item;

        $this->processExtraStoreOperation();

        // show a success message
        Alert::success(trans('backpack::crud.update_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function destroy($id)
    {
        $this->processCheckModelProperly();

        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $this->processExtraDestroyOperation($id);

        return $this->crud->delete($id);
    }

    /**
     *
     */
    private function processExtraStoreOperation()
    {
        if (in_array(ImageOperation::class, class_uses($this))) {
            $this->saveImageFields($this->data['entry'], $this->crud->getStrippedSaveRequest());
        }
    }

    /**
     * @param $id
     */
    private function processExtraDestroyOperation($id)
    {
        if (in_array(ImageOperation::class, class_uses($this))) {
            $this->destroyEntryImages($this->crud->getEntry($id));
        }
    }

    /**
     * @throws Exception
     */
    private function processCheckModelProperly()
    {
        if (!method_exists($this->crud->model, 'checkModelProperly')) {
            $class = get_class($this->crud->model);
            throw new Exception("Interface WithBackpackOperation in class $class is not set up correctly");
        }

        $this->crud->model->checkModelProperly();
    }
}
