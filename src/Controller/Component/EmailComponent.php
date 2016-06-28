<?php
namespace Platform\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Mailer\Email;
use EmailQueue\EmailQueue;


/**
 * Email component
 */
class EmailComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'transport' => 'default',
        'emailFormat' => 'both',
    ];

    public function send($to, $variables = [], $options = []){
        $result = false;
        if(!isset($options['transport'])){
            $options['transport'] = $this->config('transport');
        }
        if(!isset($options['emailFormat'])){
            $options['emailFormat'] = $this->config('emailFormat');
        }
        if(Configure::read('Email.queue')){
            if(isset($options['from'])){
                $options['from_name'] = reset($options['from']);
                $options['from_email'] = key($options['from']);
                unset($options['from']);
            }
            EmailQueue::enqueue($to, $variables, $options);
            $result = true;
        } else {
            $options['to'] = $to;
            $options['viewVars'] = $variables;
            $email = new Email();
            $email->profile($options);
            $result = $email->send();
        }
        return $result;
    }

}
