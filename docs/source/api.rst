API Documentation
=================

The SPARKS system allows integration with external systems via a simple REST interface.

Authentication
--------------

All API requests must include your API Key in the HTTP header:

`X-API-KEY: your_api_key`

You can find API keys in the "User Management" section of the Admin Panel.

Endpoint: Send Email (V1)
--------------------------

To add an email to the queue, send a POST request.

* **URL**: `http://your-domain.com/api/v1/send`
* **Method**: `POST`
* **Format**: `application/json`

=======  ========  ========  =============================================================
Field    Type      Required  Description
=======  ========  ========  =============================================================
to_email string    Yes       Email of the recipient.
to_name  string    No        Name of the recipient.
subject  string    Yes       Subject of the message.
body_html string   Yes       HTML or text content of the message.
priority int       No        Priority (1: High, 5: Medium, 9: Low). Default: 5
scheduled_at string No       Optional. ISO Date (Y-m-d H:i:s) for future delivery.
=======  ========  ========  =============================================================

**Example Request:**

.. code-block:: json

   {
     "to_email": "recipient@example.com",
     "to_name": "Marco Spinelli",
     "subject": "Welcome to SPARKS",
     "body_html": "<h1>Hello Marco!</h1><p>Your email is ready to be sent.</p>",
     "priority": 1,
     "scheduled_at": "2026-02-25 15:30:00"
   }

**Example Response (Success):**

.. code-block:: json

   {
     "success": true,
     "message": "Email added to queue",
     "id": 1234
   }

Response Considerations
-----------------------

* **200 OK**: If the message was successfully added to the queue.
* **401 Unauthorized**: If the API key is missing or incorrect.
* **400 Bad Request**: If mandatory fields are missing or data is invalid.

   The actual sending of emails depends on the execution of the Worker's Cron Job. The API is limited to adding the message to the internal sending queue.
