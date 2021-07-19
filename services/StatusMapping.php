<?php
/**
 * 2007-2021 PayPal
 *
 *  NOTICE OF LICENSE
 *
 *  This source file is subject to the Academic Free License (AFL 3.0)
 *  that is bundled with this package in the file LICENSE.txt.
 *  It is also available through the world-wide-web at this URL:
 *  http://opensource.org/licenses/afl-3.0.php
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to license@prestashop.com so we can send you a copy immediately.
 *
 *  DISCLAIMER
 *
 *  Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 *  versions in the future. If you wish to customize PrestaShop for your
 *  needs please refer to http://www.prestashop.com for more information.
 *
 *  @author 2007-2021 PayPal
 *  @author 202 ecommerce <tech@202-ecommerce.com>
 *  @copyright PayPal
 *  @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

namespace PaypalAddons\services;

use Configuration;
use MethodEC;
use MethodMB;
use MethodPPP;
use PaypalAddons\classes\AbstractMethodPaypal;

class StatusMapping
{
    /**
     * @return string $transactionStatus
     * @return int
     */
    public function getPsOrderStatusByTransaction($transactionStatus)
    {
        $orderStatus = 0;

        switch ($transactionStatus) {
            case 'Completed':
                $orderStatus = $this->getAcceptedStatus();
                break;
            case 'Refunded':
                $orderStatus = $this->getRefundStatus();
                break;
            case 'Failed':
                $orderStatus = $this->getFailedStatus();
                break;
            case 'Reversed':
                $orderStatus = $this->getFailedStatus();
                break;
            case 'Denied':
                $orderStatus = $this->getFailedStatus();
                break;
        }

        return $orderStatus;
    }

    public function getAcceptedStatus($method = null)
    {
        if (is_null($method)) {
            $method = AbstractMethodPaypal::load();
        }

        if ($this->isCustomize()) {
            if ($method instanceof MethodEC) {
                if ($this->isModeSale()) {
                    return (int)Configuration::get('PAYPAL_OS_ACCEPTED_TWO');
                }

                return (int)Configuration::get('PAYPAL_OS_ACCEPTED');
            }

            return (int)Configuration::get('PAYPAL_OS_ACCEPTED_TWO');
        }

        return (int)Configuration::get('PS_OS_PAYMENT');
    }

    public function getRefundStatus($method = null)
    {
        if (is_null($method)) {
            $method = AbstractMethodPaypal::load();
        }

        if ($this->isCustomize()) {
            if ($method instanceof MethodMB) {
                return (int)Configuration::get('PAYPAL_OS_REFUNDED_PAYPAL');
            }

            return (int)Configuration::get('PAYPAL_OS_REFUNDED');
        }

        return (int)Configuration::get('PS_OS_REFUND');
    }

    public function getFailedStatus()
    {
        if ($this->isCustomize()) {
            return (int)Configuration::get('PAYPAL_OS_VALIDATION_ERROR');
        }

        return (int)Configuration::get('PS_OS_CANCELED');
    }

    public function getCanceledStatus($method = null)
    {
        if (is_null($method)) {
            $method = AbstractMethodPaypal::load();
        }

        if ($this->isCustomize()) {
            if ($method instanceof MethodEC) {
                if ($this->isModeSale()) {
                    return (int)Configuration::get('PAYPAL_OS_CANCELED');
                }

                return (int)Configuration::get('PAYPAL_OS_CAPTURE_CANCELED');
            }
        }

        return (int)Configuration::get('PS_OS_CANCELED');
    }

    public function isCustomize()
    {
        return (bool)Configuration::get('PAYPAL_CUSTOMIZE_ORDER_STATUS');
    }

    public function isModeSale()
    {
        return (bool)Configuration::get('PAYPAL_API_INTENT') == 'sale';
    }
}
