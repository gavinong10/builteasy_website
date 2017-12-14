<?php

class Thrive_Dash_Api_Mandrill_Exceptions extends Exception {
}

class Thrive_Dash_Api_Mandrill_HttpError extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The parameters passed to the API call are invalid or not provided when required
 */
class Thrive_Dash_Api_Mandrill_ValidationError extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided API key is not a valid Mandrill API key
 */
class Thrive_Dash_Api_Mandrill_Invalid_Key extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested feature requires payment.
 */
class Thrive_Dash_Api_Mandrill_PaymentRequired extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided subaccount id does not exist.
 */
class Thrive_Dash_Api_Mandrill_Unknown_Subaccount extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested template does not exist
 */
class Thrive_Dash_Api_Mandrill_Unknown_Template extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The subsystem providing this API call is down for maintenance
 */
class Thrive_Dash_Api_Mandrill_ServiceUnavailable extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided message id does not exist.
 */
class Thrive_Dash_Api_Mandrill_Unknown_Message extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested tag does not exist or contains invalid characters
 */
class Thrive_Dash_Api_Mandrill_Invalid_Tag_Name extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested email is not in the rejection list
 */
class Thrive_Dash_Api_Mandrill_Invalid_Reject extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested sender does not exist
 */
class Thrive_Dash_Api_Mandrill_Unknown_Sender extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested URL has not been seen in a tracked link
 */
class Thrive_Dash_Api_Mandrill_Unknown_Url extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided tracking domain does not exist.
 */
class Thrive_Dash_Api_Mandrill_Unknown_TrackingDomain extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The given template name already exists or contains invalid characters
 */
class Thrive_Dash_Api_Mandrill_Invalid_Template extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested webhook does not exist
 */
class Thrive_Dash_Api_Mandrill_Unknown_Webhook extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested inbound domain does not exist
 */
class Thrive_Dash_Api_Mandrill_Unknown_InboundDomain extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided inbound route does not exist.
 */
class Thrive_Dash_Api_Mandrill_Unknown_InboundRoute extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The requested export job does not exist
 */
class Thrive_Dash_Api_Mandrill_Unknown_Export extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * A dedicated IP cannot be provisioned while another request is pending.
 */
class Thrive_Dash_Api_Mandrill_IP_ProvisionLimit extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided dedicated IP pool does not exist.
 */
class Thrive_Dash_Api_Mandrill_Unknown_Pool extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The user hasn't started sending yet.
 */
class Thrive_Dash_Api_Mandrill_NoSendingHistory extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The user's reputation is too low to continue.
 */
class Thrive_Dash_Api_Mandrill_PoorReputation extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided dedicated IP does not exist.
 */
class Thrive_Dash_Api_Mandrill_Unknown_IP extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * You cannot remove the last IP from your default IP pool.
 */
class Thrive_Dash_Api_Mandrill_Invalid_EmptyDefaultPool extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The default pool cannot be deleted.
 */
class Thrive_Dash_Api_Mandrill_Invalid_DeleteDefaultPool extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * Non-empty pools cannot be deleted.
 */
class Thrive_Dash_Api_Mandrill_Invalid_DeleteNonEmptyPool extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The domain name is not configured for use as the dedicated IP's custom reverse DNS.
 */
class Thrive_Dash_Api_Mandrill_Invalid_CustomDNS extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * A custom DNS change for this dedicated IP is currently pending.
 */
class Thrive_Dash_Api_Mandrill_Invalid_CustomDNSPending extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * Custom metadata field limit reached.
 */
class Thrive_Dash_Api_Mandrill_Metadata_FieldLimit extends Thrive_Dash_Api_Mandrill_Exceptions {
}

/**
 * The provided metadata field name does not exist.
 */
class Thrive_Dash_Api_Mandrill_Unknown_MetadataField extends Thrive_Dash_Api_Mandrill_Exceptions {
}


