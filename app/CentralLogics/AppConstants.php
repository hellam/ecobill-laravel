<?php

namespace App\CentralLogics;

#JOURNAL
define('ST_100', "Journal Entry");
//<-->
define('ST_JOURNAL', "100");

#ACCOUNT
define('ST_110', "Account Payment");
define('ST_111', "Account Deposit");
define('ST_112', "Funds Transfer");
//<-->
define('ST_ACCOUNT_PAYMENT', "110");
define('ST_ACCOUNT_DEPOSIT', "111");
define('ST_FUNDS_TRANSFER', "112");

#SALES
define('ST_120', "Invoice");
define('ST_121', "Customer Payment");
define('ST_122', "Delivery Note");
define('ST_123', "Quotation");
define('ST_124', "Credit Note");
//<-->
define('ST_INVOICE', "120");
define('ST_CUSTOMER_PAYMENT', "121");
define('ST_DELIVERY_NOTE', "122");
define('ST_QUOTATION', "123");
define('ST_CREDIT_NOTE', "124");

#AUDIT
define('AUD_301', "Account Management");
define('AUD_302', "Logon Events");
define('AUD_303', "Directory Service Access");
define('AUD_304', "Policy Change");//eg A user right was assigned.
define('AUD_305', "System Events");
//<-->
define('AUD_ACCOUNT_MANAGEMENT', "301");
define('AUD_LOGON_EVENT', "302");
define('AUD_DIRECTORY_SERVICE_ACCESS', "303");
define('AUD_POLICY_CHANGE', "304");
define('AUD_SYSTEM_EVENT', "305");

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

    AUD_ACCOUNT_MANAGEMENT => AUD_301,
    AUD_LOGON_EVENT => AUD_302,
    AUD_DIRECTORY_SERVICE_ACCESS => AUD_303,
    AUD_POLICY_CHANGE => AUD_304,
    AUD_SYSTEM_EVENT => AUD_305,
]);
