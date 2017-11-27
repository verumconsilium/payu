<?php

namespace VerumConsilium\PayU\Api;

use ErrorException;
use InvalidArgumentException;

/**
 * Contains information about the Environment setup
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */
class Environment
{
    
    /** name for payments Api*/
    const PAYMENTS_API = "PAYMENTS_API";
    
    /** name for reports Api*/
    const REPORTS_API = "REPORTS_API";
    
    /** name for subscriptions Api */
    const SUBSCRIPTIONS_API = "SUBSCRIPTIONS_API";
    
    /** url used to payments service Api  */
    private static $paymentsUrl = "https://Api.payulatam.com/payments-Api/4.0/service.cgi";
    
    /** url used to reports service Api  */
    private static $reportsUrl = "https://Api.payulatam.com/reports-Api/4.0/service.cgi";
    
    /** url used to subscriptions service Api  */
    private static $subscriptionsUrl = "https://Api.payulatam.com/payments-Api/rest/v4.3";
    
    /** url used to subscriptions service Api  if the test variable is true */
    private static $paymentsTestUrl = "https://Api.payulatam.com/payments-Api/4.0/service.cgi";
    
    /** url used to reports service Api  if the test variable is true */
    private static $reportsTestUrl = "https://Api.payulatam.com/reports-Api/4.0/service.cgi";
    
    /** url used to subscriptions service Api  if the test variable is true */
    private static $subscriptionsTestUrl = "https://Api.payulatam.com/payments-Api/rest/v4.3";
    
    /** url used to subscriptions service Api  if is not null*/
    private static $paymentsCustomUrl = null;

    /** url used to reports service Api  if is not null*/
    private static $reportsCustomUrl = null;
    
    /** url used to subscriptions service Api  if is not null*/
    private static $subscriptionsCustomUrl = null;
    
    
    /** if this is true the test url is used to request*/
    public static $test = false;
    
    
    /**
     * Gets the suitable url to the Api sent
     * @param string $api the Api to get the url it can have three values
     * PAYMENTS_API, REPORTS_API, SUBSCRIPTIONS_API
     * @throws InvalidArgumentException if the Api value doesn't have a valid value
     * @return string with the url
     */
    public static function getApiUrl($api)
    {
        switch ($api) {
            case Environment::PAYMENTS_API:
                return Environment::getPaymentsUrl();
            case Environment::REPORTS_API:
                return Environment::getReportsUrl();
            case Environment::SUBSCRIPTIONS_API:
                return Environment::getSubscriptionUrl();
            default:
                throw new InvalidArgumentException(sprintf('the Api argument [%s] is invalid please check the Environment class ', $api));
        }
    }
    
    /**
     * Returns the payments url
     * @return string  the paymets url configured
     */
    public static function getPaymentsUrl()
    {
        if (isset(Environment::$paymentsCustomUrl)) {
            return Environment::$paymentsCustomUrl;
        }
        
        if (!Environment::$test) {
            return Environment::$paymentsUrl;
        } else {
            return Environment::$paymentsTestUrl;
        }
    }
    
    /**
     * Returns the reports url
     * @return string the reports url
     */
    public static function getReportsUrl()
    {
        if (Environment::$reportsCustomUrl != null) {
            return Environment::$reportsCustomUrl;
        }
        
        if (!Environment::$test) {
            return Environment::$reportsUrl;
        } else {
            return Environment::$reportsTestUrl;
        }
    }
    
    /**
     * Returns the subscriptions url
     * @return string the subscriptions url
     */
    public static function getSubscriptionUrl()
    {
        if (Environment::$subscriptionsCustomUrl != null) {
            return Environment::$subscriptionsCustomUrl;
        }
    
        if (!Environment::$test) {
            return Environment::$subscriptionsTestUrl;
        } else {
            return Environment::$subscriptionsUrl;
        }
    }
    
    
    /**
     * Set a  custom payments url
     * @param string $paymentsCustomUrl
     */
    public static function setPaymentsCustomUrl($paymentsCustomUrl)
    {
        Environment::$paymentsCustomUrl = $paymentsCustomUrl;
    }

    /**
     * Set a custom reports url
     * @param string $reportsCustomUrl
     */
    public static function setReportsCustomUrl($reportsCustomUrl)
    {
        Environment::$reportsCustomUrl = $reportsCustomUrl;
    }

    /**
     * Set a custom subscriptions url
     * @param string $subscriptionsCustomUrl
     */
    public static function setSubscriptionsCustomUrl($subscriptionsCustomUrl)
    {
        Environment::$subscriptionsCustomUrl = $subscriptionsCustomUrl;
    }
    
    /**
     * Validates the Environment before process any request
     * @throws ErrorException
     */
    public static function validate()
    {
        if (version_compare(PHP_VERSION, '5.2.1', '<')) {
            throw new ErrorException('PHP version >= 5.2.1 required');
        }
        
        
        $requiredExtensions = array('curl','xml','mbstring','json');
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                throw new ErrorException('The Payu library requires the ' . $ext . ' extension.');
            }
        }
    }
}
