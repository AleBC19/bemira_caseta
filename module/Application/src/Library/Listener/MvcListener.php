<?php
namespace Application\Library\Listener;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\Mvc\MvcEvent;
use Laminas\EventManager\EventManagerInterface;
use Application\Library\Api\Token;

/**
 * It manages all actions in a MVC cycle.
 * @author workstation2
 *
 */
class MvcListener extends AbstractListenerAggregate
{
    
    /**
     * Attach the listeners.
     * {@inheritDoc}
     * @see \Laminas\EventManager\ListenerAggregateInterface::attach()
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'authorize']);
    }
    
    /**
     * Validates if request has access to a resource in the API.
     * @param MvcEvent $event
     */
    public function authorize(MvcEvent $event)
    {
        try {
            
            // ServiceManager and route are obtained.
            $sm = $event->getApplication()->getServiceManager();
            $route = $sm->get('application')->getMvcEvent()->getRouteMatch()->getMatchedRouteName();
            
            // Endpoints where authorization is not needed.
            $noAuth = [
                'api.rest.authentication',
                'api.rest.refresh'
            ];
            
            // Validates if request is to an endpoint that does not require authorization.
            if(in_array($route, $noAuth)) {
                return;
            }
            
            // Checks if we are in development mode.
            $developmentModeEnabled = file_exists(__DIR__.'/../../../../../config/development.config.php');
            if(strpos($route, 'api-tools') !== false && $developmentModeEnabled) {
                return;
            }
            
            // Tries to extract payload.
            $payload = Token::payload();
            if(!$payload) {
                throw new \Exception('Could not extract payload from access token');
            }
            
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode([
                'error' => [
                    'code' => 401,
                    'message' => $e->getMessage()
                ]
            ]);
            exit();
        }
    }
    
}