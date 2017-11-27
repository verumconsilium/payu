<?php

namespace VerumConsilium\PayU;

use InvalidArgumentException;
use stdClass;
use VerumConsilium\PayU\Api\RequestMethod;
use VerumConsilium\PayU\Exception\PayUException;
use VerumConsilium\PayU\Util\CommonRequestUtil;
use VerumConsilium\PayU\Util\PayUApiServiceUtil;
use VerumConsilium\PayU\Util\PayUParameters;
use VerumConsilium\PayU\Util\PayUSubscriptionsRequestUtil;
use VerumConsilium\PayU\Util\PayUSubscriptionsUrlResolver;

/**
 * Manages all PayU recurring bill operations
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 25/09/2014
 *
 */
class PayURecurringBill
{
    
    
    
    /**
     * Finds a recurring bill by id
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string The response to the request sent
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function find($parameters, $lang = null)
    {
        $required = array(PayUParameters::RECURRING_BILL_ID);
        CommonRequestUtil::validateParameters($parameters, $required);
        $recurringBillId = CommonRequestUtil::getParameter($parameters, PayUParameters::RECURRING_BILL_ID);
    
        $urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(
    
            PayUSubscriptionsUrlResolver::RECURRING_BILL_ENTITY,
                PayUSubscriptionsUrlResolver::GET_OPERATION,
                array($recurringBillId)
    
        );
    
        $payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
        return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
    }
    
    
    /**
     * Finds all bill filtered by
     * - customer id
     * - date begin
     * - date final
     * - payment method
     * - subscription Id
     *
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string the subscription plan list
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function listRecurringBills($parameters, $lang = null)
    {
        $request = new stdClass();
        $request->customerId = CommonRequestUtil::getParameter($parameters, PayUParameters::CUSTOMER_ID);
        $request->dateBegin = CommonRequestUtil::getParameter($parameters, PayUParameters::RECURRING_BILL_DATE_BEGIN);
        $request->dateFinal = CommonRequestUtil::getParameter($parameters, PayUParameters::RECURRING_BILL_DATE_FINAL);
        $request->paymentMethod = CommonRequestUtil::getParameter($parameters, PayUParameters::RECURRING_BILL_PAYMENT_METHOD_TYPE);
        $request->state = CommonRequestUtil::getParameter($parameters, PayUParameters::RECURRING_BILL_STATE);
        $request->subscriptionId = CommonRequestUtil::getParameter($parameters, PayUParameters::SUBSCRIPTION_ID);
        $request->limit = CommonRequestUtil::getParameter($parameters, PayUParameters::LIMIT);
        $request->offset = CommonRequestUtil::getParameter($parameters, PayUParameters::OFFSET);
    
        $urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(
    
            PayUSubscriptionsUrlResolver::RECURRING_BILL_ENTITY,
                PayUSubscriptionsUrlResolver::QUERY_OPERATION
    
        );
    
        $urlSegment = CommonRequestUtil::addQueryParamsToUrl($urlSegment, $request);
    
        $payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
        return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
    }
}
