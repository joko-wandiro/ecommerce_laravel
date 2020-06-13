<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Illuminate\Http\JsonResponse;

class CategoriesController extends BackEndController
{

    protected $table = 'categories';

    /**
     * Build categories page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $Scaffolding = clone $this->scaffolding;
        // Set columns properties
        $parameters = array(
            array(
                'name' => 'name',
                'width' => '40%',
            ),
            array(
                'name' => 'image',
                'width' => '40%',
            ),
            // Add Actions custom column
            array(
                'name' => 'xaction',
                'label' => '&nbsp;',
                'width' => '20%',
                'callback' => array($this, 'actionColumn'),
            ),
        );
        $Scaffolding->setColumnProperties($parameters);
        // Upload file to temporary folder and set it into parameters
        $Scaffolding->addHooks("insertModifyRequest", array($this, "setImage"));
        $Scaffolding->addHooks("updateModifyRequest", array($this, "setImage"));
        // Upload file to permanent folder
        $Scaffolding->addHooks("insertAfterInsert", array($this, "moveImage"));
        $Scaffolding->addHooks("updateAfterUpdate", array($this, "moveImage"));
        // Modify image form input
        $Scaffolding->setFormInput('image', array($this, 'getFormInputImage'));
        // Modify validation rules
        $Scaffolding->addHooks("insertModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("updateModifyValidationRules", array($this, "modifyValidation"));
        $Scaffolding->addHooks("modifyValidationRulesJS", array($this, "modifyValidationRulesJS"));
        // Set formatter for image column
        $Scaffolding->addFormatterColumn('image', array($this, 'formatterImage'));
        // Hooks Action for delete operation ( AJAX Request )
        $Scaffolding->addHooks("deleteModifyResponse", array($this, "deleteModifyResponse"));
        $content = $Scaffolding->render();
        $parameters = array(
            'scaffolding' => $content,
        );
        return $this->render($parameters);
    }

    /**
     * Formatter for image column
     * 
     * @param  \App\Libraries\Scaffolding\Model $model
     * 
     * @return  void
     */
    public function formatterImage($model)
    {
        if ($model['image']) {
            echo '<img src="' . categoryImageUrl($model['image']) . '" width="200" />';
        } else {
            echo '-';
        }
    }

    /**
     * Modify validation
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidation($rules)
    {
        $rules['image'] = 'file|mimetypes:image/png,image/jpeg|nullable';
        return $rules;
    }

    /**
     * Modify javascript validation rules
     * 
     * @param  array $rules
     * 
     * @return  array
     */
    public function modifyValidationRulesJS($rules)
    {
        unset($rules['image']['maxlength']);
        $rules['image']['accept'] = "image/png,image/jpeg";
        return $rules;
    }

    /**
     * Set image column
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function setImage($parameters)
    {
        $Request = request();
        // Upload photo file
        $filename = null;
        $hasImage = $Request->hasFile('image');
        if ($hasImage) {
            // Upload new file
            $destinationPath = categoryImageTemporaryPath();
            $file = $Request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $filename = getUniqueFilename() . '.' . $fileExtension;
            $fullPath = $destinationPath . $filename;
            while (file_exists($fullPath)) {
                $filename = getUniqueFilename() . '.' . $fileExtension;
                $fullPath = $destinationPath . $filename;
            }
            $status = $file->move($destinationPath, $filename); // uploading file to given path
            $Request->files->remove('image');
            $parameters['image'] = $filename;
        }
        return $parameters;
    }

    /**
     * Move image
     * 
     * @param array $parameters
     * 
     * @return array
     */
    public function moveImage($Model)
    {
        $Request = request();
        $old = unserialize($Request['idx_old']);
        $filename = null;
        $hasImage = $Request->hasFile('image');
        if ($hasImage) {
            // Move image file to permanent directory
            rename(categoryImageTemporaryPath($Model->image), categoryImagePath($Model->image));
            // Delete previous file
            if ($old['image']) {
                unlink(productImagePath($old['image']));
            }
        }
    }

    /**
     * Get form input image
     * 
     * @param  array $column
     * @param  \App\Libraries\Scaffolding\ScaffoldingTable $Scaffolding
     * 
     * @return  string
     */
    public function getFormInputImage($column, $Scaffolding)
    {
        $Request = request();
        $httpVerb = $Request->getMethod();
        $model = $Scaffolding->getModel();
        if ($httpVerb == "GET" && $Request->action == "edit") {
            echo '<div><img src="' . categoryImageUrl($model['image']) . '" width="200" /><div>';
        }
        echo Form::file($column['name'], $column['attributes']);
    }

}
