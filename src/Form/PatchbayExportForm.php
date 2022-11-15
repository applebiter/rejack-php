<?php
namespace Rejack\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Rejack\Patchbay;

/**
 * class PatchbayConnectForm
 * @package Rejack
 */
class PatchbayExportForm extends Form
{
    /**
     * 
     * @var Rejack\Patchbay
     */
    private $patchbay;
    
    /**
     * 
     * @param Schema $schema 
     * @return Schema 
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('name', ['type' => 'string'])
                      ->addField('overwrite', ['type' => 'bool']);
    }

    /**
     * 
     * @param Validator $validator 
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator->scalar("name")
                  ->minLength('name', 1)
                  ->maxLength("name", 64);
        $validator->boolean("overwrite");
        return $validator;
    }

    /**
     * 
     * @param array $data 
     * @return bool 
     */
    protected function _execute(array $data): bool
    {
        $result = (bool) $this->patchbay->export($data["name"], $data["overwrite"]);
        return $result;
    }

    /**
     * 
     * @param Patchbay $patchbay 
     * @return void 
     */
    public function setPatchbay(Patchbay $patchbay)
    {
        $this->patchbay = $patchbay;
    }
}