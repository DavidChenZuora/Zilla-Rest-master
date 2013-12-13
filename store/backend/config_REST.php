<?php

/**
 * Provide Copyright Notice
 **/



/** Basic Config Properties **/
/************************************************************/

// Zuora Credentials (should be API user with REST enabled) 
$username = 'michael.zeitz@zuora.com';
$password = 'Zuora001!';

// Base URL of Zuora REST services (SANDBOX)
$baseUrl = 'https://apisandbox-api.zuora.com/rest/v1/';

// Base URL of Zuora REST services (PRODUCTION)
//$baseUrl = 'https://api.zuora.com/rest/v1/';

// Content Type for REST services (must be JSON)
$contentType = 'application/json';

/************************************************************/





/** Hosted Page Credentials **/
/************************************************************/

$pageId = "2c92c0f94207dd730142098131d36c00";
$tenantId = "11484";
$apiSecurityKey = "Tpy1p7P0_jWRQk4tkYvrIMog9tdNJ5vD5XUUp6K9cCo=";

// App Url (SANDBOX)
$appUrl = "https://apisandbox.zuora.com";

// App Url (PRODUCTION)
//$appUrl = "https://www.zuora.com";

/************************************************************/




/** SFDC Credentials **/
/************************************************************/

//Enable this option to create a Salesforce account using the credentials below
$makeSfdcAccount = false;

$SfdcUsername = "michael.zeitz.personal1@zuora.com";
$SfdcPassword = "Zuora001!";
$SfdcSecurityToken = "BjAX99uML4gVnQCq0Ooo1MKRV";

// Salesforce WSDL (Test / Sandbox Tenant, ie test.salesforce)
//$SfdcWsdl = "sfdc/enterprise-sandbox.wsdl.xml";

// Salesforce WSDL (Production / Developer Tenant, ie login.salesforce)
$SfdcWsdl = "sfdc/enterprise.wsdl.xml";

/************************************************************/





/** Product Select Options **/
/************************************************************/

//Filter Products to only show a subset
$filterProducts = false;

//Field for filtering Products
$productFilterField = 'Product_Category__c';

//Value for filtering field
$productFilterValue = array('Web');

//Filter Product Rate Plans to only show a subset
$filterProductRatePlans = false;

//Field for filtering ProductRatePlans
$productRatePlanFilterField = 'ProductRatePlan_Category__c';

//Value for filtering field
$productRatePlanFilterValue = array('Web');

//Locally cache the product catalog at this location to reduce load times.
$cachePath = "DC_catalogCache.txt";

/************************************************************/





/** New Account Defaults **/
/************************************************************/

// Payment terms for this account (Due Upon Receipt, Net 30, Net 60, Net 90)
$defaultPaymentTerm = "Due Upon Receipt";

//New accounts are created in Zuora with the following default values
$defaultTimeZone = 'America/Los_Angeles';

// Specifies whether future payments are to be automatically billed when they are due
$defaultAutopay = true;

// Currency for new Account
$defaultCurrency = "USD";

// Default Bill Cylce Day for Account. Specify any day of the month (1-31, where 31 = end-of-month), or 0 for auto-set.
$defaultBillCycleDay = 1;

// Default Invoice Template ID (leave null for default)
$defaultInvoiceTemplateId = null;

// Default Communication Profile ID (leave null for default)
$defaultCommunicationProfileId = null;

// Notes field on Account
$defaultAccountNotes = 'This Account was created via Web Portal';

/************************************************************/




/** New Subscription Defaults **/
/************************************************************/

// Contract Effective Date of new Subscription (Today, First of current month, First of next month)
$defaultCED = 'Today';

// Service Activation Date of new Subscription (Today, First of current month, First of next month)
$defaultSAD = 'Today';

// Customer Acceptance Date of new Subscription (Today, First of current month, First of next month)
$defaultCAD = 'Today';

// Term Start Date of new Subscription (Today, First of current month, First of next month)
$defaultTSD = 'Today';

// Notes field on Subscription
$defaultSubscriptionNotes = 'This Subscription was created via Web Portal';

// Boolean for TERMED or EVERGREEN Subscriptions
$isTermed = true;

// If TERMED, Initial Term of new Subscription
$defaultInitialTerm = 12;

// If TERMED, Renewal Term of new Subscription
$defaultRenewalTerm = 12;

// Whether or not Subscription automatically renews at end of term
$defaultAutoRenew = true;

// Generate Invoice and Process Payment upon creating Subscription (Invoice is generated only for Subscription)
$defaultInvoiceCollect = true;

/************************************************************/




/** Amend Subscription Defaults **/
/************************************************************/

// Contract Effective Date of amendment (Today, First of current month, First of next month)
$defaultAmendCED = 'Today';

// Generate Invoice and Process Payment upon creating Subscription (Invoice is generated only for Subscription)
$defaultInvoiceCollect = true;

// Cancellation Method (EndOfCurrentTerm, EndOfLastInvoicePeriod, SpecificDate)
$defaultCancellationPolicy = 'EndOfCurrentTerm';

// Date the cancellation takes effect (Today, First of current month, First of next month)
$defaultCancellationDate = 'Today';

/************************************************************/

$test = 'test';

?>