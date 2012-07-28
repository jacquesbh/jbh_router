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
 * Abstract route model for new route
 *
 * @category    Jbh
 * @package     Jbh_Router
 * @author      Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 */
abstract class Jbh_Router_Model_Route_Abstract extends Varien_Object
{
    /**
     * The router used for matching path
     * @var Zend_Controller_Router_Route_Abstract
     */
    protected $_routerObject = null;

    /**
     * Indicate if it's a matching route
     * @var bool
     */
    protected $_match = false;

    /**
     * Returns the router
     * @return Zend_Controller_Router_Route_Abstract
     */
    public function getRouterObject()
    {
        return $this->_routerObject;
    }

    /**
     * Set if this route is matching path
     * @param bool $match
     * @return Jbh_Router_Model_Route_Abstract
     */
    public function setMatch($match)
    {
        $this->_match = (bool) $match;
        return $this;
    }

    /**
     * Get if this route match the path
     * @return bool
     */
    public function isMatch()
    {
        return $this->_match;
    }

    /**
     * Get data
     * @return mixed
     */
    public function getData($key = '', $index = null)
    {
        $data = parent::getData($key, $index);
        if (is_array($data) || $data instanceof ArrayAccess) {
            if (isset($data['@'])) {
                if (isset($data['@']['config'])) {
                    $data = Mage::app()->getStore()->getConfig($data['@']['config']);
                } elseif (isset($data['@']['helper'])) {
                    $helper = explode('::', $data['@']['helper']);
                    $data = Mage::helper($helper[0])->{$helper[1]}();
                    unset($helper);
                }
            }
        }
        return $data;
    }

    /**
     * Append config routers in the standard router
     * @return Jbh_Router_Model_Route_Abstract
     */
    public function addFrontendRouters()
    {
        if ($routers = $this->getRouters()) {
            /* @var $router Mage_Core_Controller_Varien_Router_Standard */
            $router = $this->getFront()->getRouter('standard');
            $router->collectRoutes(Jbh_Router_Controller_Router::ROUTER_NAME . '/' . $this->getRouteName(), 'standard');
        }
        return $this;
    }

    /**
     * Set/Get attribute wrapper Or Router's methods wrapper
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        try {
            $result = parent::__call($method, $args);
            return $result;
        } catch (Varien_Exception $e) {
            $router = $this->getRouterObject();
            return call_user_func_array(array($router, $method), $args);
        }
    }
}
