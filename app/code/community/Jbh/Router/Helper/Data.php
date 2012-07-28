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
 * Jbh_Router data helper
 *
 * @category    Jbh
 * @package     Jbh_Router
 * @author      Jacques Bodin-Hullin <jacques@bodin-hullin.net>
 */
class Jbh_Router_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Get a specific router
     * @param string $routerName
     * @access public
     * @return Jbh_Router_Controller_Router
     */
    public function getRouter($routerName)
    {
        return Mage::registry(Jbh_Router_Controller_Router::ROUTER_NAME)->getRouter($routerName);
    }

}
