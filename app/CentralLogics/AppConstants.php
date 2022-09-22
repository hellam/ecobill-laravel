<?php

namespace App\CentralLogics;

#JOURNAL
define('ST_100', "Journal Entry");
//<-->
define('ST_JOURNAL', "ST_100");

#ACCOUNT
define('ST_110', "Account Payment");
define('ST_111', "Account Deposit");
define('ST_112', "Funds Transfer");
//<-->
define('ST_ACCOUNT_PAYMENT', "ST_110");
define('ST_ACCOUNT_DEPOSIT', "ST_111");
define('ST_FUNDS_TRANSFER', "ST_112");

#SALES
define('ST_120', "Invoice");
define('ST_121', "Customer Payment");
define('ST_122', "Delivery Note");
define('ST_123', "Quotation");
define('ST_124', "Credit Note");
//<-->
define('ST_INVOICE', "ST_120");
define('ST_CUSTOMER_PAYMENT', "ST_121");
define('ST_DELIVERY_NOTE', "ST_122");
define('ST_QUOTATION', "ST_123");
define('ST_CREDIT_NOTE', "ST_124");

#AUDIT
define('ST_301', "Account Management");
define('ST_302', "Logon Events");
define('ST_303', "Directory Service Access");
define('ST_304', "Policy Change");//eg A user right was assigned.
define('ST_305', "System Events");
//<-->
define('ST_ACCOUNT_MANAGEMENT', "ST_301");
define('ST_LOGON_EVENT', "ST_302");
define('ST_DIRECTORY_SERVICE_ACCESS', "ST_303");
define('ST_POLICY_CHANGE', "ST_304");
define('ST_SYSTEM_EVENT', "ST_305");

define('TRX_TYPES', [
    ST_JOURNAL => ST_100,

    ST_ACCOUNT_PAYMENT => ST_110,
    ST_ACCOUNT_DEPOSIT => ST_111,
    ST_FUNDS_TRANSFER => ST_112,

    ST_INVOICE => ST_120,
    ST_CUSTOMER_PAYMENT => ST_121,
    ST_DELIVERY_NOTE => ST_122,
    ST_QUOTATION => ST_123,
    ST_CREDIT_NOTE => ST_124,

    ST_ACCOUNT_MANAGEMENT => ST_301,
    ST_LOGON_EVENT => ST_302,
    ST_DIRECTORY_SERVICE_ACCESS => ST_303,
    ST_POLICY_CHANGE => ST_304,
    ST_SYSTEM_EVENT => ST_305,
]);
