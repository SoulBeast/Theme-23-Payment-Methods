<?php
 
namespace Perspective\Theme23MyPay\Model;
 
/**
 * Pay In Store payment method model
 */
class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
    /**
     * Payment code
     *
     * @var string
     */

    protected $_code = 'custompayment';
}