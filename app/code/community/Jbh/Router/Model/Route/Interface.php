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
 * Interface for route model.
 *
 * @category    Jbh
 * @package     Jbh_Router
 * @author      Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 */
interface Jbh_Router_Model_Route_Interface
{
    /**
     * Configure the router with data used for matching path
     * @return Jbh_Router_Model_Route_Abstract
     */
    public function configure();

    /**
     * Returns the router
     * @return Zend_Controller_Router_Route_Abstract
     */
    public function getRouterObject();

    /**
     * Set if this route is matching path
     * @param bool $match
     * @return Jbh_Router_Model_Route_Abstract
     */
    public function setMatch($bool);

    /**
     * Get if this route match the path
     * @return bool
     */
    public function isMatch();

    /**
     * Append config routers in the standard router
     * @return Jbh_Router_Model_Route_Abstract
     */
    public function addFrontendRouters();
}
