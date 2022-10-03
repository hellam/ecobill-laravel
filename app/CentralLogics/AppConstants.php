<?php

namespace App\CentralLogics;

#JOURNAL
define('ST_100', "Journal Entry");
define('ST_101', "Budget Entry");

define('ST_JOURNAL', "ST_100");
define('ST_BUDGET', "ST_101");
////////////////////////////////


#BANKING AND GL
define('ST_110', "Account Expense");
define('ST_111', "Account Deposit");
define('ST_112', "Account Transfer");
define('ST_113', "Bank Account Setup");
define('ST_114', "GL Account Setup");
define('ST_115', "GL Group Setup");
define('ST_116', "GL Classes Setup");
define('ST_117', "Currency Setup");
define('ST_118', "Exchange Rate Setup");
define('ST_119', "Payment Terms Setup");

define('ST_ACCOUNT_EXPENSE', "ST_110");
define('ST_ACCOUNT_DEPOSIT', "ST_111");
define('ST_ACCOUNT_TRANSFER', "ST_112");
define('ST_BANK_ACCOUNT_SETUP', "ST_113");
define('ST_GL_ACCOUNT_SETUP', "ST_114");
define('ST_GL_GROUP_SETUP', "ST_115");
define('ST_GL_CLASSES_SETUP', "ST_116");
define('ST_CURRENCY_SETUP', "ST_117");
define('ST_EXCHANGE_RATE_SETUP', "ST_118");
define('ST_PAYMENT_TERMS_SETUP',"ST_119");
//////////////////////////////////////

#SALES
define('ST_120', "Invoice");
define('ST_121', "Customer Payment");
define('ST_122', "Delivery Note");
define('ST_123', "Quotation");
define('ST_124', "Credit Note");

define('ST_INVOICE', "ST_120");
define('ST_CUSTOMER_PAYMENT', "ST_121");
define('ST_DELIVERY_NOTE', "ST_122");
define('ST_QUOTATION', "ST_123");
define('ST_CREDIT_NOTE', "ST_124");
////////////////////////////////

#PRODUCTS
define('ST_140', "Category Setup");
define('ST_141', "Product Setup");
define('ST_142', "Subscription Setup");

define('ST_CATEGORY_SETUP', "ST_140");
define('ST_PRODUCT_SETUP', "ST_141");
define('ST_SUBSCRIPTION_SETUP','ST_142');
//////////////////////////////////////

//UTILS
define('ST_400','Void Transaction');
define('ST_401','Transaction Supervision');

define('ST_VOID_TRANSACTION','ST_400');
define('ST_TRANSACTION_SUPERVISION','ST_401');
///////////////////////////////////////


#SETUP
define('ST_130', "Business Settings");
define('ST_131', "Role Setup");
define('ST_132', "Maker Checker Rule Setup");
define('ST_133', "Tax Setup");
define('ST_134', "API Setup");
define('ST_135', "Security Policy Setup");
define('ST_136', "Branch Setup");
define('ST_137', "User Role Assignment");

define('ST_BUSINESS_SETTINGS', "ST_130");
define('ST_ROLE_SETUP', "ST_131");
define('ST_MAKER_CHECKER_RULE_SETUP', "ST_132");
define('ST_TAX_SETUP', "ST_133");
define('ST_API_SETUP', "ST_134");
define('ST_SECURITY_POLICY_SETUP', "ST_135");
define('ST_BRANCH_SETUP', "ST_136");
define('ST_ROLE_ASSIGNMENT', "ST_137");
////////////////////////////////////////

#AUDIT
define('ST_301', "Account Management");
define('ST_302', "Logon Events");
define('ST_303', "Directory Service Access");
define('ST_304', "Policy Change");//eg A user right was assigned.
define('ST_305', "System Events");

define('ST_ACCOUNT_MANAGEMENT', "ST_301");
define('ST_LOGON_EVENT', "ST_302");
define('ST_DIRECTORY_SERVICE_ACCESS', "ST_303");
define('ST_POLICY_CHANGE', "ST_304");
define('ST_SYSTEM_EVENT', "ST_305");
////////////////////////////////////////

define('TRX_TYPES', [
    ST_JOURNAL => ST_100,
    ST_BUDGET => ST_101,

    ST_ACCOUNT_EXPENSE => ST_110,
    ST_ACCOUNT_DEPOSIT => ST_111,
    ST_ACCOUNT_TRANSFER => ST_112,
    ST_BANK_ACCOUNT_SETUP => ST_113,
    ST_GL_ACCOUNT_SETUP => ST_114,
    ST_GL_GROUP_SETUP => ST_115,
    ST_GL_CLASSES_SETUP => ST_116,
    ST_CURRENCY_SETUP => ST_117,
    ST_EXCHANGE_RATE_SETUP => ST_118,
    ST_PAYMENT_TERMS_SETUP => ST_119,

    ST_INVOICE => ST_120,
    ST_CUSTOMER_PAYMENT => ST_121,
    ST_DELIVERY_NOTE => ST_122,
    ST_QUOTATION => ST_123,
    ST_CREDIT_NOTE => ST_124,

    ST_VOID_TRANSACTION => ST_400,
    ST_TRANSACTION_SUPERVISION => ST_401,

    ST_BUSINESS_SETTINGS => ST_130,
    ST_ROLE_SETUP => ST_131,
    ST_MAKER_CHECKER_RULE_SETUP => ST_132,
    ST_TAX_SETUP => ST_133,
    ST_API_SETUP => ST_134,
    ST_SECURITY_POLICY_SETUP => ST_135,
    ST_BRANCH_SETUP => ST_136,
    ST_ROLE_ASSIGNMENT => ST_137,

    ST_ACCOUNT_MANAGEMENT => ST_301,
    ST_LOGON_EVENT => ST_302,
    ST_DIRECTORY_SERVICE_ACCESS => ST_303,
    ST_POLICY_CHANGE => ST_304,
    ST_SYSTEM_EVENT => ST_305,
]);

define('TIME_ZONE', array(
    'Asia/Kabul' => 'UTC +04:36 Asia/Kabul - Afghanistan',
    'Europe/Tirane' => 'UTC +01:19 Europe/Tirane - Albania',
    'Africa/Algiers' => 'UTC +00:12 Africa/Algiers - Algeria',
    'Pacific/Pago_Pago' => 'UTC +12:37 Pacific/Pago_Pago - American Samoa',
    'Europe/Andorra' => 'UTC +00:06 Europe/Andorra - Andorra',
    'Africa/Luanda' => 'UTC +00:13 Africa/Luanda - Angola',
    'America/Anguilla' => 'UTC -04:24 America/Anguilla - Anguilla',
    'Antarctica/Casey' => 'UTC Antarctica/Casey - Antarctica',
    'Antarctica/Davis' => 'UTC Antarctica/Davis - Antarctica',
    'Antarctica/DumontDUrville' => 'UTC +09:48 Antarctica/DumontDUrville - Antarctica',
    'Antarctica/Mawson' => 'UTC Antarctica/Mawson - Antarctica',
    'Antarctica/McMurdo' => 'UTC +11:39 Antarctica/McMurdo - Antarctica',
    'Antarctica/Palmer' => 'UTC Antarctica/Palmer - Antarctica',
    'Antarctica/Rothera' => 'UTC Antarctica/Rothera - Antarctica',
    'Antarctica/Syowa' => 'UTC +03:06 Antarctica/Syowa - Antarctica',
    'Antarctica/Troll' => 'UTC Antarctica/Troll - Antarctica',
    'Antarctica/Vostok' => 'UTC +05:50 Antarctica/Vostok - Antarctica',
    'America/Antigua' => 'UTC -04:24 America/Antigua - Antigua and Barbuda',
    'America/Argentina/Buenos_Aires' => 'UTC -03:53 America/Argentina/Buenos_Aires - Argentina',
    'America/Argentina/Catamarca' => 'UTC -04:23 America/Argentina/Catamarca - Argentina',
    'America/Argentina/Cordoba' => 'UTC -04:16 America/Argentina/Cordoba - Argentina',
    'America/Argentina/Jujuy' => 'UTC -04:21 America/Argentina/Jujuy - Argentina',
    'America/Argentina/La_Rioja' => 'UTC -04:27 America/Argentina/La_Rioja - Argentina',
    'America/Argentina/Mendoza' => 'UTC -04:35 America/Argentina/Mendoza - Argentina',
    'America/Argentina/Rio_Gallegos' => 'UTC -04:36 America/Argentina/Rio_Gallegos - Argentina',
    'America/Argentina/Salta' => 'UTC -04:21 America/Argentina/Salta - Argentina',
    'America/Argentina/San_Juan' => 'UTC -04:34 America/Argentina/San_Juan - Argentina',
    'America/Argentina/San_Luis' => 'UTC -04:25 America/Argentina/San_Luis - Argentina',
    'America/Argentina/Tucuman' => 'UTC -04:20 America/Argentina/Tucuman - Argentina',
    'America/Argentina/Ushuaia' => 'UTC -04:33 America/Argentina/Ushuaia - Argentina',
    'Asia/Yerevan' => 'UTC +02:58 Asia/Yerevan - Armenia',
    'America/Aruba' => 'UTC -04:24 America/Aruba - Aruba',
    'Antarctica/Macquarie' => 'UTC Antarctica/Macquarie - Australia',
    'Australia/Adelaide' => 'UTC +09:14 Australia/Adelaide - Australia',
    'Australia/Brisbane' => 'UTC +10:12 Australia/Brisbane - Australia',
    'Australia/Broken_Hill' => 'UTC +09:25 Australia/Broken_Hill - Australia',
    'Australia/Darwin' => 'UTC +08:43 Australia/Darwin - Australia',
    'Australia/Eucla' => 'UTC +08:35 Australia/Eucla - Australia',
    'Australia/Hobart' => 'UTC +09:49 Australia/Hobart - Australia',
    'Australia/Lindeman' => 'UTC +09:55 Australia/Lindeman - Australia',
    'Australia/Lord_Howe' => 'UTC +10:36 Australia/Lord_Howe - Australia',
    'Australia/Melbourne' => 'UTC +09:39 Australia/Melbourne - Australia',
    'Australia/Perth' => 'UTC +07:43 Australia/Perth - Australia',
    'Australia/Sydney' => 'UTC +10:04 Australia/Sydney - Australia',
    'Europe/Vienna' => 'UTC +01:05 Europe/Vienna - Austria',
    'Asia/Baku' => 'UTC +03:19 Asia/Baku - Azerbaijan',
    'America/Nassau' => 'UTC -05:17 America/Nassau - Bahamas',
    'Asia/Bahrain' => 'UTC +03:26 Asia/Bahrain - Bahrain',
    'Asia/Dhaka' => 'UTC +06:01 Asia/Dhaka - Bangladesh',
    'America/Barbados' => 'UTC -03:58 America/Barbados - Barbados',
    'Europe/Minsk' => 'UTC +01:50 Europe/Minsk - Belarus',
    'Europe/Brussels' => 'UTC +00:17 Europe/Brussels - Belgium',
    'America/Belize' => 'UTC -05:52 America/Belize - Belize',
    'Africa/Porto-Novo' => 'UTC +00:13 Africa/Porto-Novo - Benin',
    'Atlantic/Bermuda' => 'UTC -04:19 Atlantic/Bermuda - Bermuda',
    'Asia/Thimphu' => 'UTC +05:58 Asia/Thimphu - Bhutan',
    'America/La_Paz' => 'UTC -04:32 America/La_Paz - Bolivia, Plurinational State of',
    'America/Kralendijk' => 'UTC -04:24 America/Kralendijk - Bonaire, Sint Eustatius and Saba',
    'Europe/Sarajevo' => 'UTC +01:22 Europe/Sarajevo - Bosnia and Herzegovina',
    'Africa/Gaborone' => 'UTC +02:10 Africa/Gaborone - Botswana',
    'America/Araguaina' => 'UTC -03:12 America/Araguaina - Brazil',
    'America/Bahia' => 'UTC -02:34 America/Bahia - Brazil',
    'America/Belem' => 'UTC -03:13 America/Belem - Brazil',
    'America/Boa_Vista' => 'UTC -04:02 America/Boa_Vista - Brazil',
    'America/Campo_Grande' => 'UTC -03:38 America/Campo_Grande - Brazil',
    'America/Cuiaba' => 'UTC -03:44 America/Cuiaba - Brazil',
    'America/Eirunepe' => 'UTC -04:39 America/Eirunepe - Brazil',
    'America/Fortaleza' => 'UTC -02:34 America/Fortaleza - Brazil',
    'America/Maceio' => 'UTC -02:22 America/Maceio - Brazil',
    'America/Manaus' => 'UTC -04:00 America/Manaus - Brazil',
    'America/Noronha' => 'UTC -02:09 America/Noronha - Brazil',
    'America/Porto_Velho' => 'UTC -04:15 America/Porto_Velho - Brazil',
    'America/Recife' => 'UTC -02:19 America/Recife - Brazil',
    'America/Rio_Branco' => 'UTC -04:31 America/Rio_Branco - Brazil',
    'America/Santarem' => 'UTC -03:38 America/Santarem - Brazil',
    'America/Sao_Paulo' => 'UTC -03:06 America/Sao_Paulo - Brazil',
    'Indian/Chagos' => 'UTC +04:49 Indian/Chagos - British Indian Ocean Territory',
    'Asia/Brunei' => 'UTC +07:21 Asia/Brunei - Brunei Darussalam',
    'Europe/Sofia' => 'UTC +01:33 Europe/Sofia - Bulgaria',
    'Africa/Ouagadougou' => 'UTC -00:16 Africa/Ouagadougou - Burkina Faso',
    'Africa/Bujumbura' => 'UTC +02:10 Africa/Bujumbura - Burundi',
    'Asia/Phnom_Penh' => 'UTC +06:42 Asia/Phnom_Penh - Cambodia',
    'Africa/Douala' => 'UTC +00:13 Africa/Douala - Cameroon',
    'America/Atikokan' => 'UTC -05:18 America/Atikokan - Canada',
    'America/Blanc-Sablon' => 'UTC -04:24 America/Blanc-Sablon - Canada',
    'America/Cambridge_Bay' => 'UTC America/Cambridge_Bay - Canada',
    'America/Creston' => 'UTC -07:28 America/Creston - Canada',
    'America/Dawson' => 'UTC -09:17 America/Dawson - Canada',
    'America/Dawson_Creek' => 'UTC -08:00 America/Dawson_Creek - Canada',
    'America/Edmonton' => 'UTC -07:33 America/Edmonton - Canada',
    'America/Fort_Nelson' => 'UTC -08:10 America/Fort_Nelson - Canada',
    'America/Glace_Bay' => 'UTC -03:59 America/Glace_Bay - Canada',
    'America/Goose_Bay' => 'UTC -04:01 America/Goose_Bay - Canada',
    'America/Halifax' => 'UTC -04:14 America/Halifax - Canada',
    'America/Inuvik' => 'UTC America/Inuvik - Canada',
    'America/Iqaluit' => 'UTC America/Iqaluit - Canada',
    'America/Moncton' => 'UTC -04:19 America/Moncton - Canada',
    'America/Nipigon' => 'UTC -05:53 America/Nipigon - Canada',
    'America/Pangnirtung' => 'UTC America/Pangnirtung - Canada',
    'America/Rainy_River' => 'UTC -06:18 America/Rainy_River - Canada',
    'America/Rankin_Inlet' => 'UTC America/Rankin_Inlet - Canada',
    'America/Regina' => 'UTC -06:58 America/Regina - Canada',
    'America/Resolute' => 'UTC America/Resolute - Canada',
    'America/St_Johns' => 'UTC -03:30 America/St_Johns - Canada',
    'America/Swift_Current' => 'UTC -07:11 America/Swift_Current - Canada',
    'America/Thunder_Bay' => 'UTC -05:57 America/Thunder_Bay - Canada',
    'America/Toronto' => 'UTC -05:17 America/Toronto - Canada',
    'America/Vancouver' => 'UTC -08:12 America/Vancouver - Canada',
    'America/Whitehorse' => 'UTC -09:00 America/Whitehorse - Canada',
    'America/Winnipeg' => 'UTC -06:28 America/Winnipeg - Canada',
    'America/Yellowknife' => 'UTC America/Yellowknife - Canada',
    'Atlantic/Cape_Verde' => 'UTC -01:34 Atlantic/Cape_Verde - Cape Verde',
    'America/Cayman' => 'UTC -05:18 America/Cayman - Cayman Islands',
    'Africa/Bangui' => 'UTC +00:13 Africa/Bangui - Central African Republic',
    'Africa/Ndjamena' => 'UTC +01:00 Africa/Ndjamena - Chad',
    'America/Punta_Arenas' => 'UTC -04:43 America/Punta_Arenas - Chile',
    'America/Santiago' => 'UTC -04:42 America/Santiago - Chile',
    'Pacific/Easter' => 'UTC -07:17 Pacific/Easter - Chile',
    'Asia/Shanghai' => 'UTC +08:05 Asia/Shanghai - China',
    'Asia/Urumqi' => 'UTC +05:50 Asia/Urumqi - China',
    'Indian/Christmas' => 'UTC +06:42 Indian/Christmas - Christmas Island',
    'Indian/Cocos' => 'UTC +06:24 Indian/Cocos - Cocos (Keeling) Islands',
    'America/Bogota' => 'UTC -04:56 America/Bogota - Colombia',
    'Indian/Comoro' => 'UTC +02:27 Indian/Comoro - Comoros',
    'Africa/Brazzaville' => 'UTC +00:13 Africa/Brazzaville - Congo',
    'Africa/Kinshasa' => 'UTC +00:13 Africa/Kinshasa - Congo, the Democratic Republic of the',
    'Africa/Lubumbashi' => 'UTC +02:10 Africa/Lubumbashi - Congo, the Democratic Republic of the',
    'Pacific/Rarotonga' => 'UTC +13:20 Pacific/Rarotonga - Cook Islands',
    'America/Costa_Rica' => 'UTC -05:36 America/Costa_Rica - Costa Rica',
    'Europe/Zagreb' => 'UTC +01:22 Europe/Zagreb - Croatia',
    'America/Havana' => 'UTC -05:29 America/Havana - Cuba',
    'America/Curacao' => 'UTC -04:24 America/Curacao - Curaçao',
    'Asia/Famagusta' => 'UTC +02:15 Asia/Famagusta - Cyprus',
    'Asia/Nicosia' => 'UTC +02:13 Asia/Nicosia - Cyprus',
    'Europe/Prague' => 'UTC +00:57 Europe/Prague - Czech Republic',
    'Africa/Abidjan' => 'UTC -00:16 Africa/Abidjan - Côte d\'Ivoire',
    'Europe/Copenhagen' => 'UTC +00:53 Europe/Copenhagen - Denmark',
    'Africa/Djibouti' => 'UTC +02:27 Africa/Djibouti - Djibouti',
    'America/Dominica' => 'UTC -04:24 America/Dominica - Dominica',
    'America/Santo_Domingo' => 'UTC -04:39 America/Santo_Domingo - Dominican Republic',
    'America/Guayaquil' => 'UTC -05:19 America/Guayaquil - Ecuador',
    'Pacific/Galapagos' => 'UTC -05:58 Pacific/Galapagos - Ecuador',
    'Africa/Cairo' => 'UTC +02:05 Africa/Cairo - Egypt',
    'America/El_Salvador' => 'UTC -05:56 America/El_Salvador - El Salvador',
    'Africa/Malabo' => 'UTC +00:13 Africa/Malabo - Equatorial Guinea',
    'Africa/Asmara' => 'UTC +02:27 Africa/Asmara - Eritrea',
    'Europe/Tallinn' => 'UTC +01:39 Europe/Tallinn - Estonia',
    'Africa/Addis_Ababa' => 'UTC +02:27 Africa/Addis_Ababa - Ethiopia',
    'Atlantic/Stanley' => 'UTC -03:51 Atlantic/Stanley - Falkland Islands (Malvinas)',
    'Atlantic/Faroe' => 'UTC -00:27 Atlantic/Faroe - Faroe Islands',
    'Pacific/Fiji' => 'UTC +11:55 Pacific/Fiji - Fiji',
    'Europe/Helsinki' => 'UTC +01:39 Europe/Helsinki - Finland',
    'Europe/Paris' => 'UTC +00:09 Europe/Paris - France',
    'America/Cayenne' => 'UTC -03:29 America/Cayenne - French Guiana',
    'Pacific/Gambier' => 'UTC -08:59 Pacific/Gambier - French Polynesia',
    'Pacific/Marquesas' => 'UTC -09:18 Pacific/Marquesas - French Polynesia',
    'Pacific/Tahiti' => 'UTC -09:58 Pacific/Tahiti - French Polynesia',
    'Indian/Kerguelen' => 'UTC +04:54 Indian/Kerguelen - French Southern Territories',
    'Africa/Libreville' => 'UTC +00:13 Africa/Libreville - Gabon',
    'Africa/Banjul' => 'UTC -00:16 Africa/Banjul - Gambia',
    'Asia/Tbilisi' => 'UTC +02:59 Asia/Tbilisi - Georgia',
    'Europe/Berlin' => 'UTC +00:53 Europe/Berlin - Germany',
    'Europe/Busingen' => 'UTC +00:34 Europe/Busingen - Germany',
    'Africa/Accra' => 'UTC -00:16 Africa/Accra - Ghana',
    'Europe/Gibraltar' => 'UTC -00:21 Europe/Gibraltar - Gibraltar',
    'Europe/Athens' => 'UTC +01:34 Europe/Athens - Greece',
    'America/Danmarkshavn' => 'UTC -01:14 America/Danmarkshavn - Greenland',
    'America/Nuuk' => 'UTC -03:26 America/Nuuk - Greenland',
    'America/Scoresbysund' => 'UTC -01:27 America/Scoresbysund - Greenland',
    'America/Thule' => 'UTC -04:35 America/Thule - Greenland',
    'America/Grenada' => 'UTC -04:24 America/Grenada - Grenada',
    'America/Guadeloupe' => 'UTC -04:24 America/Guadeloupe - Guadeloupe',
    'Pacific/Guam' => 'UTC -14:21 Pacific/Guam - Guam',
    'America/Guatemala' => 'UTC -06:02 America/Guatemala - Guatemala',
    'Europe/Guernsey' => 'UTC -00:01 Europe/Guernsey - Guernsey',
    'Africa/Conakry' => 'UTC -00:16 Africa/Conakry - Guinea',
    'Africa/Bissau' => 'UTC -01:02 Africa/Bissau - Guinea-Bissau',
    'America/Guyana' => 'UTC -03:52 America/Guyana - Guyana',
    'America/Port-au-Prince' => 'UTC -04:49 America/Port-au-Prince - Haiti',
    'Europe/Vatican' => 'UTC +00:49 Europe/Vatican - Holy See (Vatican City State)',
    'America/Tegucigalpa' => 'UTC -05:48 America/Tegucigalpa - Honduras',
    'Asia/Hong_Kong' => 'UTC +07:36 Asia/Hong_Kong - Hong Kong',
    'Europe/Budapest' => 'UTC +01:16 Europe/Budapest - Hungary',
    'Atlantic/Reykjavik' => 'UTC -00:16 Atlantic/Reykjavik - Iceland',
    'Asia/Kolkata' => 'UTC +05:53 Asia/Kolkata - India',
    'Asia/Jakarta' => 'UTC +07:07 Asia/Jakarta - Indonesia',
    'Asia/Jayapura' => 'UTC +09:22 Asia/Jayapura - Indonesia',
    'Asia/Makassar' => 'UTC +07:57 Asia/Makassar - Indonesia',
    'Asia/Pontianak' => 'UTC +07:17 Asia/Pontianak - Indonesia',
    'Asia/Tehran' => 'UTC +03:25 Asia/Tehran - Iran, Islamic Republic of',
    'Asia/Baghdad' => 'UTC +02:57 Asia/Baghdad - Iraq',
    'Europe/Dublin' => 'UTC -00:25 Europe/Dublin - Ireland',
    'Europe/Isle_of_Man' => 'UTC -00:01 Europe/Isle_of_Man - Isle of Man',
    'Asia/Jerusalem' => 'UTC +02:20 Asia/Jerusalem - Israel',
    'Europe/Rome' => 'UTC +00:49 Europe/Rome - Italy',
    'America/Jamaica' => 'UTC -05:07 America/Jamaica - Jamaica',
    'Asia/Tokyo' => 'UTC +09:18 Asia/Tokyo - Japan',
    'Europe/Jersey' => 'UTC -00:01 Europe/Jersey - Jersey',
    'Asia/Amman' => 'UTC +02:23 Asia/Amman - Jordan',
    'Asia/Almaty' => 'UTC +05:07 Asia/Almaty - Kazakhstan',
    'Asia/Aqtau' => 'UTC +03:21 Asia/Aqtau - Kazakhstan',
    'Asia/Aqtobe' => 'UTC +03:48 Asia/Aqtobe - Kazakhstan',
    'Asia/Atyrau' => 'UTC +03:27 Asia/Atyrau - Kazakhstan',
    'Asia/Oral' => 'UTC +03:25 Asia/Oral - Kazakhstan',
    'Asia/Qostanay' => 'UTC +04:14 Asia/Qostanay - Kazakhstan',
    'Asia/Qyzylorda' => 'UTC +04:21 Asia/Qyzylorda - Kazakhstan',
    'Africa/Nairobi' => 'UTC +02:27 Africa/Nairobi - Kenya',
    'Pacific/Kanton' => 'UTC Pacific/Kanton - Kiribati',
    'Pacific/Kiritimati' => 'UTC -10:29 Pacific/Kiritimati - Kiribati',
    'Pacific/Tarawa' => 'UTC +11:32 Pacific/Tarawa - Kiribati',
    'Asia/Pyongyang' => 'UTC +08:23 Asia/Pyongyang - Korea, Democratic People\'s Republic of',
    'Asia/Seoul' => 'UTC +08:27 Asia/Seoul - Korea, Republic of',
    'Asia/Kuwait' => 'UTC +03:06 Asia/Kuwait - Kuwait',
    'Asia/Bishkek' => 'UTC +04:58 Asia/Bishkek - Kyrgyzstan',
    'Asia/Vientiane' => 'UTC +06:42 Asia/Vientiane - Lao People\'s Democratic Republic',
    'Europe/Riga' => 'UTC +01:36 Europe/Riga - Latvia',
    'Asia/Beirut' => 'UTC +02:22 Asia/Beirut - Lebanon',
    'Africa/Maseru' => 'UTC +01:52 Africa/Maseru - Lesotho',
    'Africa/Monrovia' => 'UTC -00:43 Africa/Monrovia - Liberia',
    'Africa/Tripoli' => 'UTC +00:52 Africa/Tripoli - Libya',
    'Europe/Vaduz' => 'UTC +00:34 Europe/Vaduz - Liechtenstein',
    'Europe/Vilnius' => 'UTC +01:41 Europe/Vilnius - Lithuania',
    'Europe/Luxembourg' => 'UTC +00:17 Europe/Luxembourg - Luxembourg',
    'Asia/Macau' => 'UTC +07:34 Asia/Macau - Macao',
    'Europe/Skopje' => 'UTC +01:22 Europe/Skopje - Macedonia, the Former Yugoslav Republic of',
    'Indian/Antananarivo' => 'UTC +02:27 Indian/Antananarivo - Madagascar',
    'Africa/Blantyre' => 'UTC +02:10 Africa/Blantyre - Malawi',
    'Asia/Kuala_Lumpur' => 'UTC +06:55 Asia/Kuala_Lumpur - Malaysia',
    'Asia/Kuching' => 'UTC +07:21 Asia/Kuching - Malaysia',
    'Indian/Maldives' => 'UTC +04:54 Indian/Maldives - Maldives',
    'Africa/Bamako' => 'UTC -00:16 Africa/Bamako - Mali',
    'Europe/Malta' => 'UTC +00:58 Europe/Malta - Malta',
    'Pacific/Kwajalein' => 'UTC +11:09 Pacific/Kwajalein - Marshall Islands',
    'Pacific/Majuro' => 'UTC +11:32 Pacific/Majuro - Marshall Islands',
    'America/Martinique' => 'UTC -04:04 America/Martinique - Martinique',
    'Africa/Nouakchott' => 'UTC -00:16 Africa/Nouakchott - Mauritania',
    'Indian/Mauritius' => 'UTC +03:50 Indian/Mauritius - Mauritius',
    'Indian/Mayotte' => 'UTC +02:27 Indian/Mayotte - Mayotte',
    'America/Bahia_Banderas' => 'UTC -07:01 America/Bahia_Banderas - Mexico',
    'America/Cancun' => 'UTC -05:47 America/Cancun - Mexico',
    'America/Chihuahua' => 'UTC -07:04 America/Chihuahua - Mexico',
    'America/Hermosillo' => 'UTC -07:23 America/Hermosillo - Mexico',
    'America/Matamoros' => 'UTC -06:40 America/Matamoros - Mexico',
    'America/Mazatlan' => 'UTC -07:05 America/Mazatlan - Mexico',
    'America/Merida' => 'UTC -05:58 America/Merida - Mexico',
    'America/Mexico_City' => 'UTC -06:36 America/Mexico_City - Mexico',
    'America/Monterrey' => 'UTC -06:41 America/Monterrey - Mexico',
    'America/Ojinaga' => 'UTC -06:57 America/Ojinaga - Mexico',
    'America/Tijuana' => 'UTC -07:48 America/Tijuana - Mexico',
    'Pacific/Chuuk' => 'UTC +09:48 Pacific/Chuuk - Micronesia, Federated States of',
    'Pacific/Kosrae' => 'UTC -13:08 Pacific/Kosrae - Micronesia, Federated States of',
    'Pacific/Pohnpei' => 'UTC +10:39 Pacific/Pohnpei - Micronesia, Federated States of',
    'Europe/Chisinau' => 'UTC +01:55 Europe/Chisinau - Moldova, Republic of',
    'Europe/Monaco' => 'UTC +00:09 Europe/Monaco - Monaco',
    'Asia/Choibalsan' => 'UTC +07:38 Asia/Choibalsan - Mongolia',
    'Asia/Hovd' => 'UTC +06:06 Asia/Hovd - Mongolia',
    'Asia/Ulaanbaatar' => 'UTC +07:07 Asia/Ulaanbaatar - Mongolia',
    'Europe/Podgorica' => 'UTC +01:22 Europe/Podgorica - Montenegro',
    'America/Montserrat' => 'UTC -04:24 America/Montserrat - Montserrat',
    'Africa/Casablanca' => 'UTC -00:30 Africa/Casablanca - Morocco',
    'Africa/Maputo' => 'UTC +02:10 Africa/Maputo - Mozambique',
    'Asia/Yangon' => 'UTC +06:24 Asia/Yangon - Myanmar',
    'Africa/Windhoek' => 'UTC +01:08 Africa/Windhoek - Namibia',
    'Pacific/Nauru' => 'UTC +11:07 Pacific/Nauru - Nauru',
    'Asia/Kathmandu' => 'UTC +05:41 Asia/Kathmandu - Nepal',
    'Europe/Amsterdam' => 'UTC +00:17 Europe/Amsterdam - Netherlands',
    'Pacific/Noumea' => 'UTC +11:05 Pacific/Noumea - New Caledonia',
    'Pacific/Auckland' => 'UTC +11:39 Pacific/Auckland - New Zealand',
    'Pacific/Chatham' => 'UTC +12:13 Pacific/Chatham - New Zealand',
    'America/Managua' => 'UTC -05:45 America/Managua - Nicaragua',
    'Africa/Niamey' => 'UTC +00:13 Africa/Niamey - Niger',
    'Africa/Lagos' => 'UTC +00:13 Africa/Lagos - Nigeria',
    'Pacific/Niue' => 'UTC -11:19 Pacific/Niue - Niue',
    'Pacific/Norfolk' => 'UTC +11:11 Pacific/Norfolk - Norfolk Island',
    'Pacific/Saipan' => 'UTC -14:21 Pacific/Saipan - Northern Mariana Islands',
    'Europe/Oslo' => 'UTC +00:53 Europe/Oslo - Norway',
    'Asia/Muscat' => 'UTC +03:41 Asia/Muscat - Oman',
    'Asia/Karachi' => 'UTC +04:28 Asia/Karachi - Pakistan',
    'Pacific/Palau' => 'UTC -15:02 Pacific/Palau - Palau',
    'Asia/Gaza' => 'UTC +02:17 Asia/Gaza - Palestine, State of',
    'Asia/Hebron' => 'UTC +02:20 Asia/Hebron - Palestine, State of',
    'America/Panama' => 'UTC -05:18 America/Panama - Panama',
    'Pacific/Bougainville' => 'UTC +10:22 Pacific/Bougainville - Papua New Guinea',
    'Pacific/Port_Moresby' => 'UTC +09:48 Pacific/Port_Moresby - Papua New Guinea',
    'America/Asuncion' => 'UTC -03:50 America/Asuncion - Paraguay',
    'America/Lima' => 'UTC -05:08 America/Lima - Peru',
    'Asia/Manila' => 'UTC -15:56 Asia/Manila - Philippines',
    'Pacific/Pitcairn' => 'UTC -08:40 Pacific/Pitcairn - Pitcairn',
    'Europe/Warsaw' => 'UTC +01:24 Europe/Warsaw - Poland',
    'Atlantic/Azores' => 'UTC -01:42 Atlantic/Azores - Portugal',
    'Atlantic/Madeira' => 'UTC -01:07 Atlantic/Madeira - Portugal',
    'Europe/Lisbon' => 'UTC -00:36 Europe/Lisbon - Portugal',
    'America/Puerto_Rico' => 'UTC -04:24 America/Puerto_Rico - Puerto Rico',
    'Asia/Qatar' => 'UTC +03:26 Asia/Qatar - Qatar',
    'Europe/Bucharest' => 'UTC +01:44 Europe/Bucharest - Romania',
    'Asia/Anadyr' => 'UTC +11:49 Asia/Anadyr - Russian Federation',
    'Asia/Barnaul' => 'UTC +05:35 Asia/Barnaul - Russian Federation',
    'Asia/Chita' => 'UTC +07:33 Asia/Chita - Russian Federation',
    'Asia/Irkutsk' => 'UTC +06:57 Asia/Irkutsk - Russian Federation',
    'Asia/Kamchatka' => 'UTC +10:34 Asia/Kamchatka - Russian Federation',
    'Asia/Khandyga' => 'UTC +09:02 Asia/Khandyga - Russian Federation',
    'Asia/Krasnoyarsk' => 'UTC +06:11 Asia/Krasnoyarsk - Russian Federation',
    'Asia/Magadan' => 'UTC +10:03 Asia/Magadan - Russian Federation',
    'Asia/Novokuznetsk' => 'UTC +05:48 Asia/Novokuznetsk - Russian Federation',
    'Asia/Novosibirsk' => 'UTC +05:31 Asia/Novosibirsk - Russian Federation',
    'Asia/Omsk' => 'UTC +04:53 Asia/Omsk - Russian Federation',
    'Asia/Sakhalin' => 'UTC +09:30 Asia/Sakhalin - Russian Federation',
    'Asia/Srednekolymsk' => 'UTC +10:14 Asia/Srednekolymsk - Russian Federation',
    'Asia/Tomsk' => 'UTC +05:39 Asia/Tomsk - Russian Federation',
    'Asia/Ust-Nera' => 'UTC +09:32 Asia/Ust-Nera - Russian Federation',
    'Asia/Vladivostok' => 'UTC +08:47 Asia/Vladivostok - Russian Federation',
    'Asia/Yakutsk' => 'UTC +08:38 Asia/Yakutsk - Russian Federation',
    'Asia/Yekaterinburg' => 'UTC +04:02 Asia/Yekaterinburg - Russian Federation',
    'Europe/Astrakhan' => 'UTC +03:12 Europe/Astrakhan - Russian Federation',
    'Europe/Kaliningrad' => 'UTC +01:22 Europe/Kaliningrad - Russian Federation',
    'Europe/Kirov' => 'UTC +03:18 Europe/Kirov - Russian Federation',
    'Europe/Moscow' => 'UTC +02:30 Europe/Moscow - Russian Federation',
    'Europe/Samara' => 'UTC +03:20 Europe/Samara - Russian Federation',
    'Europe/Saratov' => 'UTC +03:04 Europe/Saratov - Russian Federation',
    'Europe/Ulyanovsk' => 'UTC +03:13 Europe/Ulyanovsk - Russian Federation',
    'Europe/Volgograd' => 'UTC +02:57 Europe/Volgograd - Russian Federation',
    'Africa/Kigali' => 'UTC +02:10 Africa/Kigali - Rwanda',
    'Indian/Reunion' => 'UTC +03:41 Indian/Reunion - Réunion',
    'America/St_Barthelemy' => 'UTC -04:24 America/St_Barthelemy - Saint Barthélemy',
    'Atlantic/St_Helena' => 'UTC -00:16 Atlantic/St_Helena - Saint Helena, Ascension and Tristan da Cunha',
    'America/St_Kitts' => 'UTC -04:24 America/St_Kitts - Saint Kitts and Nevis',
    'America/St_Lucia' => 'UTC -04:24 America/St_Lucia - Saint Lucia',
    'America/Marigot' => 'UTC -04:24 America/Marigot - Saint Martin (French part)',
    'America/Miquelon' => 'UTC -03:44 America/Miquelon - Saint Pierre and Miquelon',
    'America/St_Vincent' => 'UTC -04:24 America/St_Vincent - Saint Vincent and the Grenadines',
    'Pacific/Apia' => 'UTC +12:33 Pacific/Apia - Samoa',
    'Europe/San_Marino' => 'UTC +00:49 Europe/San_Marino - San Marino',
    'Africa/Sao_Tome' => 'UTC +00:26 Africa/Sao_Tome - Sao Tome and Principe',
    'Asia/Riyadh' => 'UTC +03:06 Asia/Riyadh - Saudi Arabia',
    'Africa/Dakar' => 'UTC -00:16 Africa/Dakar - Senegal',
    'Europe/Belgrade' => 'UTC +01:22 Europe/Belgrade - Serbia',
    'Indian/Mahe' => 'UTC +03:41 Indian/Mahe - Seychelles',
    'Africa/Freetown' => 'UTC -00:16 Africa/Freetown - Sierra Leone',
    'Asia/Singapore' => 'UTC +06:55 Asia/Singapore - Singapore',
    'America/Lower_Princes' => 'UTC -04:24 America/Lower_Princes - Sint Maarten (Dutch part)',
    'Europe/Bratislava' => 'UTC +00:57 Europe/Bratislava - Slovakia',
    'Europe/Ljubljana' => 'UTC +01:22 Europe/Ljubljana - Slovenia',
    'Pacific/Guadalcanal' => 'UTC +10:39 Pacific/Guadalcanal - Solomon Islands',
    'Africa/Mogadishu' => 'UTC +02:27 Africa/Mogadishu - Somalia',
    'Africa/Johannesburg' => 'UTC +01:52 Africa/Johannesburg - South Africa',
    'Atlantic/South_Georgia' => 'UTC -02:26 Atlantic/South_Georgia - South Georgia and the South Sandwich Islands',
    'Africa/Juba' => 'UTC +02:06 Africa/Juba - South Sudan',
    'Africa/Ceuta' => 'UTC -00:21 Africa/Ceuta - Spain',
    'Atlantic/Canary' => 'UTC -01:01 Atlantic/Canary - Spain',
    'Europe/Madrid' => 'UTC -00:14 Europe/Madrid - Spain',
    'Asia/Colombo' => 'UTC +05:19 Asia/Colombo - Sri Lanka',
    'Africa/Khartoum' => 'UTC +02:10 Africa/Khartoum - Sudan',
    'America/Paramaribo' => 'UTC -03:40 America/Paramaribo - Suriname',
    'Arctic/Longyearbyen' => 'UTC +00:53 Arctic/Longyearbyen - Svalbard and Jan Mayen',
    'Africa/Mbabane' => 'UTC +01:52 Africa/Mbabane - Swaziland',
    'Europe/Stockholm' => 'UTC +00:53 Europe/Stockholm - Sweden',
    'Europe/Zurich' => 'UTC +00:34 Europe/Zurich - Switzerland',
    'Asia/Damascus' => 'UTC +02:25 Asia/Damascus - Syrian Arab Republic',
    'Asia/Taipei' => 'UTC +08:06 Asia/Taipei - Taiwan, Province of China',
    'Asia/Dushanbe' => 'UTC +04:35 Asia/Dushanbe - Tajikistan',
    'Africa/Dar_es_Salaam' => 'UTC +02:27 Africa/Dar_es_Salaam - Tanzania, United Republic of',
    'Asia/Bangkok' => 'UTC +06:42 Asia/Bangkok - Thailand',
    'Asia/Dili' => 'UTC +08:22 Asia/Dili - Timor-Leste',
    'Africa/Lome' => 'UTC -00:16 Africa/Lome - Togo',
    'Pacific/Fakaofo' => 'UTC -11:24 Pacific/Fakaofo - Tokelau',
    'Pacific/Tongatapu' => 'UTC +12:19 Pacific/Tongatapu - Tonga',
    'America/Port_of_Spain' => 'UTC -04:24 America/Port_of_Spain - Trinidad and Tobago',
    'Africa/Tunis' => 'UTC +00:40 Africa/Tunis - Tunisia',
    'Europe/Istanbul' => 'UTC +01:55 Europe/Istanbul - Turkey',
    'Asia/Ashgabat' => 'UTC +03:53 Asia/Ashgabat - Turkmenistan',
    'America/Grand_Turk' => 'UTC -04:44 America/Grand_Turk - Turks and Caicos Islands',
    'Pacific/Funafuti' => 'UTC +11:32 Pacific/Funafuti - Tuvalu',
    'Africa/Kampala' => 'UTC +02:27 Africa/Kampala - Uganda',
    'Europe/Kyiv' => 'UTC +02:02 Europe/Kyiv - Ukraine',
    'Europe/Simferopol' => 'UTC +02:16 Europe/Simferopol - Ukraine',
    'Asia/Dubai' => 'UTC +03:41 Asia/Dubai - United Arab Emirates',
    'Europe/London' => 'UTC -00:01 Europe/London - United Kingdom',
    'America/Adak' => 'UTC +12:13 America/Adak - United States',
    'America/Anchorage' => 'UTC +14:00 America/Anchorage - United States',
    'America/Boise' => 'UTC -07:44 America/Boise - United States',
    'America/Chicago' => 'UTC -05:50 America/Chicago - United States',
    'America/Denver' => 'UTC -06:59 America/Denver - United States',
    'America/Detroit' => 'UTC -05:32 America/Detroit - United States',
    'America/Indiana/Indianapolis' => 'UTC -05:44 America/Indiana/Indianapolis - United States',
    'America/Indiana/Knox' => 'UTC -05:46 America/Indiana/Knox - United States',
    'America/Indiana/Marengo' => 'UTC -05:45 America/Indiana/Marengo - United States',
    'America/Indiana/Petersburg' => 'UTC -05:49 America/Indiana/Petersburg - United States',
    'America/Indiana/Tell_City' => 'UTC -05:47 America/Indiana/Tell_City - United States',
    'America/Indiana/Vevay' => 'UTC -05:40 America/Indiana/Vevay - United States',
    'America/Indiana/Vincennes' => 'UTC -05:50 America/Indiana/Vincennes - United States',
    'America/Indiana/Winamac' => 'UTC -05:46 America/Indiana/Winamac - United States',
    'America/Juneau' => 'UTC +15:02 America/Juneau - United States',
    'America/Kentucky/Louisville' => 'UTC -05:43 America/Kentucky/Louisville - United States',
    'America/Kentucky/Monticello' => 'UTC -05:39 America/Kentucky/Monticello - United States',
    'America/Los_Angeles' => 'UTC -07:52 America/Los_Angeles - United States',
    'America/Menominee' => 'UTC -05:50 America/Menominee - United States',
    'America/Metlakatla' => 'UTC +15:13 America/Metlakatla - United States',
    'America/New_York' => 'UTC -04:56 America/New York - United States',
    'America/Nome' => 'UTC +12:58 America/Nome - United States',
    'America/North_Dakota/Beulah' => 'UTC -06:47 America/North_Dakota/Beulah - United States',
    'America/North_Dakota/Center' => 'UTC -06:45 America/North_Dakota/Center - United States',
    'America/North_Dakota/New_Salem' => 'UTC -06:45 America/North_Dakota/New_Salem - United States',
    'America/Phoenix' => 'UTC -07:28 America/Phoenix - United States',
    'America/Sitka' => 'UTC +14:58 America/Sitka - United States',
    'America/Yakutat' => 'UTC +14:41 America/Yakutat - United States',
    'Pacific/Honolulu' => 'UTC -10:31 Pacific/Honolulu - United States',
    'Pacific/Midway' => 'UTC +12:37 Pacific/Midway - United States Minor Outlying Islands',
    'Pacific/Wake' => 'UTC +11:32 Pacific/Wake - United States Minor Outlying Islands',
    'America/Montevideo' => 'UTC -03:44 America/Montevideo - Uruguay',
    'Asia/Samarkand' => 'UTC +04:27 Asia/Samarkand - Uzbekistan',
    'Asia/Tashkent' => 'UTC +04:37 Asia/Tashkent - Uzbekistan',
    'Pacific/Efate' => 'UTC +11:13 Pacific/Efate - Vanuatu',
    'America/Caracas' => 'UTC -04:27 America/Caracas - Venezuela, Bolivarian Republic of',
    'Asia/Ho_Chi_Minh' => 'UTC +07:06 Asia/Ho_Chi_Minh - Viet Nam',
    'America/Tortola' => 'UTC -04:24 America/Tortola - Virgin Islands, British',
    'America/St_Thomas' => 'UTC -04:24 America/St_Thomas - Virgin Islands, U.S.',
    'Pacific/Wallis' => 'UTC +11:32 Pacific/Wallis - Wallis and Futuna',
    'Africa/El_Aaiun' => 'UTC -00:52 Africa/El_Aaiun - Western Sahara',
    'Asia/Aden' => 'UTC +03:06 Asia/Aden - Yemen',
    'Africa/Lusaka' => 'UTC +02:10 Africa/Lusaka - Zambia',
    'Africa/Harare' => 'UTC +02:10 Africa/Harare - Zimbabwe',
    'Europe/Mariehamn' => 'UTC +01:39 Europe/Mariehamn - Åland Islands'
));
define('DATE_FORMAT',array(
    'DDMMYYYY',
    'MMDDYYYY',
    'YYYYMMDD',
    'DD MMM YYYY',
    'MMM DD YYYY',
    'YYYY MMM DD'));
