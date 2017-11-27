<?php

namespace VerumConsilium\PayU;

use InvalidArgumentException;
use stdClass;
use VerumConsilium\PayU\Api\RequestMethod;
use VerumConsilium\PayU\Exception\ConnectionException;
use VerumConsilium\PayU\Exception\PayUException;
use VerumConsilium\PayU\Util\CommonRequestUtil;
use VerumConsilium\PayU\Util\PayUApiServiceUtil;
use VerumConsilium\PayU\Util\PayUParameters;
use VerumConsilium\PayU\Util\PayUSubscriptionsRequestUtil;
use VerumConsilium\PayU\Util\PayUSubscriptionsUrlResolver;

/**
 * Manages all PayU recurring bill item operations
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 22/12/2013
 *
 */
class PayURecurringBillItem
{
    
    /**
     * Creates a recurring bill item
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string The response to the request sent
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function create($parameters, $lang = null)
    {
        $required = array(
                PayUParameters::SUBSCRIPTION_ID,
                PayUParameters::DESCRIPTION,
                PayUParameters::ITEM_VALUE,
                PayUParameters::CURRENCY
        );
        
        CommonRequestUtil::validateParameters($parameters, $required);
        $request = PayUSubscriptionsRequestUtil::buildRecurringBillItem($parameters);
        $urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(
            PayUSubscriptionsUrlResolver::RECURRING_BILL_ITEM_ENTITY,
                                                                                 PayUSubscriptionsUrlResolver::ADD_OPERATION,
                                                                                 array($parameters[PayUParameters::SUBSCRIPTION_ID])
        );
        
        $payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::POST);
        
        return PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
    }
    
    /**
     * Finds recurring bill items by id
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string The response to the request sent
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function find($parameters, $lang = null)
    {
        $required = array(PayUParameters::RECURRING_BILL_ITEM_ID);
        CommonRequestUtil::validateParameters($parameters, $required);
        $recurringBillItemId = CommonRequestUtil::getParameter($parameters, PayUParameters::RECURRING_BILL_ITEM_ID);
        
        $urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(
        
            PayUSubscriptionsUrlResolver::RECURRING_BILL_ITEM_ENTITY,
                                                                                PayUSubscriptionsUrlResolver::GET_OPERATION,
                                                                                array($recurringBillItemId)
        
        );
        
        $payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
        return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
    }
    
    /**
     * Returns the recurring bill items with the query params
     *
     * @param array $parameters The parameters to be sent to the server
     * @param  string $lang
     * @return string the recurring bill items found
     * @throws PayUException
     * @throws InvalidArgumentException
     * @throws ConnectionException
     */
    public static function findList($parameters, $lang = null)
    {
        $subscriptionId = CommonRequestUtil::getParameter($parameters, PayUParameters::SUBSCRIPTION_ID);
        $description = CommonRequestUtil::getParameter($parameters, PayUParameters::DESCRIPTION);
        
        $request = new stdClass();
        $request->subscriptionId = $subscriptionId;
        $request->description = $description;
        
        if (isset($subscriptionId) || isset($description)) {
            $urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(
            
                PayUSubscriptionsUrlResolver::RECURRING_BILL_ITEM_ENTITY,
                    PayUSubscriptionsUrlResolver::GET_LIST_OPERATION,
                    null
            
            );
            
            $urlSegment = CommonRequestUtil::addQueryParamsToUrl($urlSegment, $request);
        } else {
            throw new InvalidArgumentException('You must send ' . PayUParameters::SUBSCRIPTION_ID . ' or '. PayUParameters::DESCRIPTION . ' parameters');
        }
            
        $payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::GET);
        return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
    }
    
    /**
     * Updates a recurring bill item
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string The response to the request sent
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function update($parameters, $lang = null)
    {
        $required = array(PayUParameters::RECURRING_BILL_ITEM_ID);
        
        CommonRequestUtil::validateParameters($parameters, $required);
        
        $recurrinbBillItem = PayUSubscriptionsRequestUtil::buildRecurringBillItem($parameters);
        $urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(
            PayUSubscriptionsUrlResolver::RECURRING_BILL_ITEM_ENTITY,
                PayUSubscriptionsUrlResolver::EDIT_OPERATION,
                array($recurrinbBillItem->id)
        );
        $payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::PUT);
        return PayUApiServiceUtil::sendRequest($recurrinbBillItem, $payUHttpRequestInfo);
    }
    
    /**
     * Deletes a recurring bill item
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string The response to the request sent
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function delete($parameters, $lang = null)
    {
        $required = array(PayUParameters::RECURRING_BILL_ITEM_ID);
        CommonRequestUtil::validateParameters($parameters, $required);
        
        $recurrinbBillItem = PayUSubscriptionsRequestUtil::buildRecurringBillItem($parameters);
        
        $urlSegment = PayUSubscriptionsUrlResolver::getInstance()->getUrlSegment(
        
            PayUSubscriptionsUrlResolver::RECURRING_BILL_ITEM_ENTITY,
                                            PayUSubscriptionsUrlResolver::DELETE_OPERATION,
                                            array($recurrinbBillItem->id)
        
        );
        $payUHttpRequestInfo = PayUSubscriptionsRequestUtil::buildHttpRequestInfo($urlSegment, $lang, RequestMethod::DELETE);
        return PayUApiServiceUtil::sendRequest(null, $payUHttpRequestInfo);
    }
}
