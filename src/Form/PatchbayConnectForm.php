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
class PatchbayConnectForm extends Form
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
        return $schema->addField('port1', ['type' => 'string'])
                      ->addField('port2', ['type' => 'string']);
    }

    /**
     * 
     * @param Validator $validator 
     * @return Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator->minLength('port1', 3)
                  ->maxLength("port1", 255)
                  ->inList("port1", $this->patchbay->getAudioPortsList())
                  ->minLength('port2', 3)
                  ->maxLength("port2", 255)
                  ->inList("port2", $this->patchbay->getAudioPortsList());
        return $validator;
    }

    /**
     * 
     * @param array $data 
     * @return bool 
     */
    protected function _execute(array $data): bool
    {
        $this->patchbay->connect($data["port1"], $data["port2"]);
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