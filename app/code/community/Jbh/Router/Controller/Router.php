<?php
/**
 * This file is part of Jbh_Router for Magento.
 *
 * @license MIT (https://raw.github.com/jacquesbh/jbh_router/master/LICENSE)
 * @category Jbh
 * @package Jbh_Router
 * @copyright Copyright (c) 2012 Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 */

/**
 * Jbh Router.
 *
 * @category    Jbh
 * @package     Jbh_Router
 * @author      Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 */
class Jbh_Router_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{

    /**
     * Configuration tag who contains custom routes
     * @const ROUTER_NAME string
     */
    const ROUTER_NAME = 'jbh_router';

    /**
     * Route types matching Zend_Controller_Router_Route_Static
     * @const ROUTE_STYPE_STATIC string
     */
    const ROUTE_TYPE_STATIC = 'static';

    /**
     * Route types matching Zend_Controller_Router_Route_Regex
     * @const ROUTE_STYPE_REGEX string
     */
    const ROUTE_TYPE_REGEX = 'regex';

    /**
     * Route type "Custom" for custom controller router.
     * @const ROUTE_TYPE_CUSTOM string
     */
    const ROUTE_TYPE_CUSTOM = 'custom';

    /**
     * The registry key for keep the router
     * @const REG_KEY string
     */
    const REG_KEY = 'jbh_router_controller_router';

    /**
     * All custom routers
     * @var array
     * @access protected
     */
    protected $_routers = null;

    /**
     * Init routers via an observer like "controller_front_init_routers"
     * @param Varien_Event_Observer $observer The observer
     * @access public
     * @return void
     */
    public function initControllerRouters(Varien_Event_Observer $observer)
    {
        // Set the front controller
        $this->setFront($observer->getFront());

        // Set the router in registry
        Mage::register(self::ROUTER_NAME, $this);

        // Add the router to the front controller
        $observer->getEvent()->getFront()->addRouter('jbh_router', $this);
    }

    /**
     * Check the current URL for any matching with all custom routes.
     * @param Zend_Controller_Request_Http $request
     * @return bool False if doesn't match.
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        // Check if Magento is installed or not
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
            ->setRedirect(Mage::getUrl('install'))
            ->sendResponse();
            exit;
        }

        // Dispatch event before matching routes
        Mage::dispatchEvent('jbh_router_before', array(
            'request' => $request,
            'controller_router' => $this
        ));

        // Try match with all routers
        $routers = $this->getAllRouters();
        if (!empty($routers)) {
            $router = null;
            $path = $request->getPathInfo();
            foreach ($routers as $routerName => $router) {
                if (false !== $params = $router->getRouterObject()->match($path)) {
                    break;
                }
            }
            if ($params !== false) {
                // Dispatch event indicating that route match
                Mage::dispatchEvent('jbh_router_match_' . $routerName, array(
                    'request' => $request,
                    'controller_router' => $this,
                    'router' => $router)
                );

                // The route is set for match and routers are added
                $router->setMatch(true)->addFrontendRouters($router);

                // Set module, controller, action and params for redirect
                $request->setModuleName($router->getModule())
                    ->setControllerName($router->getController())
                    ->setActionName($router->getAction())
                    ->setParams($params);
                return true;
            }
        }

        // Dispatch event after matching routes
        Mage::dispatchEvent('jbh_router_after', array(
            'request' => $request,
            'controller_router' => $this
        ));

        return false;
    }

    /**
     * Get all routers objects.
     * All routers are Zend_Controller_Router_Route_* or custom objects.
     * @access public
     * @return array
     */
    public function getAllRouters()
    {
        if ($this->_routers === null) {
            if ($routes = $this->getRoutes()) {

                $this->_routers = array();

                foreach ($routes as $routeName => $route) {

                    if ($routeName == 'routers') continue;

                    /**
                     * Check integrality
                     */
                    // Type
                    if (!isset($route['type'])) {
                        Mage::throwException('No type defined for the "' . $routeName . '" route.');
                    }

                    // Module
                    if (!isset($route['module'])) {
                        Mage::throwException('No module defined for the "' . $routeName . '" route.');
                    }

                    // Controller
                    if (!isset($route['controller'])) {
                        $route['controller'] = 'index';
                    }

                    // Action
                    if (!isset($route['action'])) {
                        $route['action'] = 'index';
                    }

                    $route['front'] = $this->getFront();
                    $route['route_name'] = $routeName;

                    /**
                     * Append new router
                     */
                    switch ($route['type']) {
                        case self::ROUTE_TYPE_CUSTOM:
                            if (!isset($route['class'])) {
                                Mage::throwException('No class defined for the "' . $routeName . '" route.');
                            }
                            $this->_routers[$routeName] = Mage::getModel($route['class'])->setData($route)->configure();
                        break;
                        case self::ROUTE_TYPE_STATIC:
                            $this->_routers[$routeName] = Mage::getModel('jbh_router/route_static')->setData($route)->configure();
                        break;
                        case self::ROUTE_TYPE_REGEX:
                            $this->_routers[$routeName] = Mage::getModel('jbh_router/route_regex')->setData($route)->configure();
                        break;
                        default:
                            Mage::throwException('Incorrect type defined for the "' . $routeName . '" route.');
                        break;
                    }
                }
            }
        }
        return $this->_routers;
    }

    /**
     * Get all the routes in configuration files.
     * @access public
     * @return mixed FALSE if no routes.
     */
    public function getRoutes()
    {
        $routes = Mage::getConfig()->getNode(self::ROUTER_NAME);
        if ($routes !== false) {
            return $routes->asArray();
        }
        return false;
    }

    /**
     * Get a specific router
     * Can returns custom object.
     * @param string $routerName
     * @throws Mage_Core_Exception If router doesn't exist
     * @access public
     * @return Zend_Controller_Router_Route_Abstract The asked router
     */
    public function getRouter($routerName)
    {
        $routers = $this->getAllRouters();
        if (!isset($routers[$routerName])) {
            Mage::throwException('Bad router name');
        }
        return $routers[$routerName];
    }
}
