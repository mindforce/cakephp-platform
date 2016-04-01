<?php
namespace Platform\Model\Behavior;

use ArrayObject;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * Stateable behavior
 */
class StateableBehavior extends Behavior
{

    protected $_defaultConfig = [
        'field' => 'state',
        'states' => [
            -1 => 'deleted',
            0 => 'unpublished',
            1 => 'published',
        ],
        'defaultState' => 0
    ];

    public function initialize(array $config = [])
    {
        parent::initialize($config);
        if(isset($config['states'])&&is_array($config['states'])){
            $this->config('states', $config['states'], false);
        }
    }

    public function getStates()
    {
        return $this->config('states');
    }

    public function getStatesFlipped()
    {
        return array_flip($this->config('states'));
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $states = $this->getStatesFlipped('states');
        if(!isset($data[$this->config('field')])
            ||!array_key_exists($data[$this->config('field')], $states)){
            $data[$this->config('field')] = 0;
        } else {
            $data[$this->config('field')] = $states[$data[$this->config('field')]];
        }
    }

    public function beforeFind(Event $event, Query $query)
    {
        $query->formatResults(function ($results) {
            return $results->map(function ($row) {
                if (!$row instanceOf Entity) {
                    return $row;
                }
                $this->transformState($row);
                return $row;
            });
        });
    }

    public function transformState(Entity $entity) {
        $states = $this->getStates();
        $field = $this->config('field');
        $state = $this->config('defaultState');
        if(array_key_exists($entity->$field, $states)){
            $state = $entity->$field;
        }
        $state = $states[$state];
		$entity->set($this->config('field'), $state);
	}

    /*
    public function findConcept($query, $options)
    {
        $query->where([
            $this->config('field') => $this->config('states.concept'),
        ]);

        return $query;
    }
    */
}
