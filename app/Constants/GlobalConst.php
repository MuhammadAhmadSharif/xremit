<?php

namespace App\Constants;

class GlobalConst {
    const USER_PASS_RESEND_TIME_MINUTE = "1";

    const ACTIVE = true;
    const BANNED = false;
    const DEFAULT_TOKEN_EXP_SEC = 3600;

    const VERIFIED = 1;
    const APPROVED = 1;
    const PENDING = 2;
    const REJECTED = 3;
    const DEFAULT = 0;
    const UNVERIFIED = 0;

    const UNKNOWN          = "UNKNOWN";
    const USEFUL_LINK_PRIVACY_POLICY = "PRIVACY_POLICY";

    const DOCUMENT_TYPE_NID             = "NID Card";
    const DOCUMENT_TYPE_DRIVING_LICENCE = "Driving Licence";
    const DOCUMENT_TYPE_PASSPORT        = "Passport";

    //transaction type

    const TRANSACTION_TYPE_BANK                 = "Bank Transfer";
    const TRANSACTION_TYPE_MOBILE               = "Mobile Money";
    const TRANSACTION_TYPE_CASHPICKUP               = "Cash Pickup";

    //beneficiary
    
    const RECIPIENT_METHOD_BANK            = "Bank";
    const RECIPIENT_METHOD_CASH            = "Cash";
    const RECIPIENT_METHOD_MOBILE          = "Mobile";

    const BENEFICIARY_METHOD_BANK_TRANSAFER            = "Bank Transfer";
    const BENEFICIARY_METHOD_CASHPICKUP               = "Cash Pickup";
    const BENEFICIARY_METHOD_MOBILE_MONEY          = "Mobile Money";

    //Remittance log Status

    const REMITTANCE_STATUS_REVIEW_PAYMENT      = 1;
    const REMITTANCE_STATUS_PENDING             = 2;
    const REMITTANCE_STATUS_CONFIRM_PAYMENT     = 3;
    const REMITTANCE_STATUS_HOLD                = 4;
    const REMITTANCE_STATUS_SETTLED             = 5;
    const REMITTANCE_STATUS_COMPLETE            = 6;
    const REMITTANCE_STATUS_CANCEL              = 7;
    const REMITTANCE_STATUS_FAILED              = 8;
    const REMITTANCE_STATUS_REFUND              = 9;
    const REMITTANCE_STATUS_DELAYED             = 10;
    const REMITTANCE_STATUS_ALL                 = "ALL";


    //time period
    const LAST_ONE_WEEKS                         = "LAST_ONE_WEEKS";
    const LAST_TWO_WEEKS                         = "LAST_TWO_WEEKS";
    const LAST_ONE_MONTHS                        = "LAST_ONE_MONTHS";
    const LAST_TWO_MONTHS                        = "LAST_TWO_MONTHS";
    const LAST_THREE_MONTHS                      = "LAST_THREE_MONTHS";
    const LAST_SIX_MONTHS                        = "LAST_SIX_MONTHS";
    const LAST_ONE_YEARS                         = "LAST_ONE_YEARS";
    const SPECIFIC_DATES                         = "SPECIFIC_DATES";

    const LIVE = 'live';
    const SANDBOX = 'sandbox';

    const NEW_USER_BONUS                         = "new-user-bonus";
    const COUPON                                 = "coupon";

    const BANK_TRANSFER                          = "bank-transfer";
    const MOBILE_MONEY                           = "mobile_money";
    const CASH_PICKUP                            = "cash-pickup";

    const CURRENCY_LAYER            = "CURRENCY-LAYER";
    const SYSTEM_MAINTENANCE        = "system-maintenance";
}