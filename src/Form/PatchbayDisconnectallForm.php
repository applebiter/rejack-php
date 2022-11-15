<?php
namespace Rejack\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Rejack\Patchbay;

/**
 * class PatchbayDisconnectallForm
 * @package Rejack
 */
class PatchbayDisconnectallForm extends Form
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
        return $schema->addField('disconnectall', ['type' => 'string']);
    }

    /**
     * 
     * @param Validator $validator 
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator->notBlank("disconnectall");
        return $validator;
    }

    /**
     * 
     * @param array $data 
     * @return bool 
     */
    protected function _execute(array $data): bool
    {
        $this->patchbay->disconnectall();
        return true;
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