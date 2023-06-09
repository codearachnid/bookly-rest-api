<?php
/**
* @OA\Get(
*     path="/wp-json/wp/v2/bookly_appointments",
*     summary="Get a paginated array of appointments objects.",
*     tags={"appointments"},
*     @OA\Response(response=200, description="success"),
*     @OA\Response(response=404, description="not found"),
*     @OA\Response(response=400, description="bad request"),
*     @OA\Response(response=401, description="unauthorized"),
*     @OA\Response(response=403, description="forbidden")
* )
*/

/**
* @OA\Post(
*     path="/wp-json/wp/v2/bookly_appointments",
*     summary="Store a single appointment object.",
*     tags={"appointments"},
*     @OA\Parameter(
*         in="query",
*         name="location_id",
*         required=false,
*         description="The appointment Location ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="integer"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="staff_id",
*         required=true,
*         description="The appointment Staff ID.",
*         style="form",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\Parameter(
*         in="query",
*         name="staff_any",
*         required=false,
*         description="",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="service_id",
*         required=true,
*         description="The appointment Service ID.",
*         style="form",
*         @OA\Schema(type="integer", example=1)
*     ),
*     @OA\Parameter(
*         in="query",
*         name="custom_service_name",
*         required=false,
*         description="The appointment Custom Service Name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="custom_service_price",
*         required=false,
*         description="The appointment Custom Service Price.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
* @OA\Parameter(
*     name="customer_appointment",
*     in="query",
*     description="Customer appointment details",
*     required=true,
*     @OA\Schema(
*             type="object",
*             @OA\Property(property="package_id", type="integer", example=0),
*             @OA\Property(property="customer_id", type="integer", example=1, required={"true"}),
*             @OA\Property(property="number_of_persons", type="integer", example=1),
*             @OA\Property(property="units", type="integer", example=1),
*             @OA\Property(property="status", type="string", example=""),
*             @OA\Property(property="notes", type="string", example=""),
*             @OA\Property(property="extras", type="string", example=""),
*             @OA\Property(property="extras_multiply_nop", type="integer", example=1),
*             @OA\Property(property="status_changed_at", type="string", format="date-time", example="Y-m-d H:i:s"),
*             @OA\Property(property="token", type="string", example=""),
*             @OA\Property(property="time_zone", type="string", example=""),
*             @OA\Property(property="time_zone_offset", type="string", example=""),
*             @OA\Property(property="rating", type="integer", example=0),
*             @OA\Property(property="rating_comment", type="string", example=""),
*             @OA\Property(property="locale", type="string", example=""),
*             @OA\Property(property="compound_service_id", type="integer", example=0),
*             @OA\Property(property="compound_token", type="string", example=""),
*             @OA\Property(property="created_from", type="string", example=""),
*             @OA\Property(property="created_at", type="string", format="date-time", example="Y-m-d H:i:s"),
*             @OA\Property(property="custom_fields", type="string", example="")
*     ),
*     style="deepObject"
* ),
*     @OA\Parameter(
*         in="query",
*         name="start_date",
*         required=true,
*         description="The appointment Start Date. Format: Y-m-d.",
*         style="form",
*         @OA\Schema(type="string", format="date-time", example="2023-03-22T20:00:00Z")
*     ),
*     @OA\Parameter(
*         in="query",
*         name="end_date",
*         required=true,
*         description="The appointment End Date. Format: Y-m-d",
*         style="form",
*         @OA\Schema(type="string", format="date-time", example="2023-03-22T20:15:00Z")
*     ),
*     @OA\Parameter(
*         in="query",
*         name="extras_duration",
*         required=false,
*         description="The appointment Extras Duration.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="internal_note",
*         required=false,
*         description="The appointment Internal Note.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="google_event_id",
*         required=false,
*         description="The appointment Google Event ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="google_event_etag",
*         required=false,
*         description="The appointment Google Event etag.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="outlook_event_id",
*         required=false,
*         description="The appointment Outlook Event ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="outlook_event_change_key",
*         required=false,
*         description="The appointment Outlook Event Change Key.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="outlook_event_series_id",
*         required=false,
*         description="The appointment Outlook Event Series ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
* @OA\Parameter(
*     name="payment_details",
*     in="query",
*     description="Appointment payment details",
*     required=false,
*     @OA\Schema(
*             type="object",
*             @OA\Property(property="coupon_id", type="integer", example=0),
*             @OA\Property(property="gift_card_id", type="integer", example=0),
*             @OA\Property(property="target", type="string", enum={"appointments","packages"}),
*             @OA\Property(property="type", type="string", enum={"local","free","paypal","authorize_net","stripe","2checkout","payu_biz","payu_latam","payson","mollie","woocommerce","cloud_stripe","cloud_square","cloud_gift"}, example="local"),
*             @OA\Property(property="total", type="string", example=""),
*             @OA\Property(property="tax", type="string", example=""),
*             @OA\Property(property="paid", type="string", example=""),
*             @OA\Property(property="paid_type", type="string", enum={"in_full","deposit"}),
*             @OA\Property(property="gateway_price_correction", type="string", example=""),
*             @OA\Property(property="status", type="integer", enum={"pending","completed","rejected","refunded"}),
*             @OA\Property(property="token", type="string", example=""),
*             @OA\Property(property="details", type="string", example=""),
*             @OA\Property(property="ref_id", type="string", example=""),
*             @OA\Property(property="created_at", type="string", format="date-time", example="Y-m-d H:i:s"),
*             @OA\Property(property="updated_at", type="string", format="date-time", example="Y-m-d H:i:s")
*     ),
*     style="deepObject"
* ),
* @OA\Parameter(
*     in="query",
*     name="created_from",
*     required=true,
*     description="Indicates from which application the appointment was created.",
*     style="form",
*     @OA\Schema(
*         type="array",
*         @OA\Items(
*             type="string",
*             enum={"bookly","google","outlook","api"},
*             example="api"
*         ),
*         default="api"
*     )
* ),
*     @OA\Parameter(
*         in="query",
*         name="created_at",
*         required=false,
*         description="The appointment Creation Date. Format: Y-m-d.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Response(response=200, description="success"),
*     @OA\Response(response=404, description="not found"),
*     @OA\Response(response=400, description="bad request"),
*     @OA\Response(response=401, description="unauthorized"),
*     @OA\Response(response=403, description="forbidden"),
*     @OA\Response(response=422, description="unprocessable entity")
* )
*/

/**
* @OA\Get(
*     path="/wp-json/wp/v2/bookly_appointments/{id}",
*     summary="Get a single appointment object",
*     description="Get a single appointment data object providing its unique ID number.",
*     operationId="getAppointment",
*     tags={"appointments"},
*     @OA\Parameter(
*         in="path",
*         name="id",
*         required=true,
*         description="The appointment ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="integer"))
*     ),
*     @OA\Response(response=200, description="success"),
*     @OA\Response(response=404, description="not found"),
*     @OA\Response(response=400, description="bad request"),
*     @OA\Response(response=401, description="unauthorized"),
*     @OA\Response(response=403, description="forbidden")
* )
*/

/**
* @OA\Put(
*     path="/wp-json/wp/v2/bookly_appointments/{id}",
*     summary="Update a single appointment object.",
*     tags={"appointments"},
*     @OA\Parameter(
*         in="path",
*         name="id",
*         required=true,
*         description="The appointment ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="integer"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="location_id",
*         required=false,
*         description="The appointment Location ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="integer"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="staff_id",
*         required=true,
*         description="The appointment Staff ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="staff_any",
*         required=false,
*         description="",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="service_id",
*         required=false,
*         description="The appointment Service ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="custom_service_name",
*         required=false,
*         description="The appointment Custom Service Name.",
*         style="form",
*         @OA\Schema(type="string")
*     ),
*     @OA\Parameter(
*         in="query",
*         name="custom_service_price",
*         required=false,
*         description="The appointment Custom Service Price.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="start_date",
*         required=true,
*         description="The appointment Start Date. Format: Y-m-d.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="end_date",
*         required=true,
*         description="The appointment End Date. Format: Y-m-d",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="extras_duration",
*         required=false,
*         description="The appointment Extras Duration.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="internal_note",
*         required=false,
*         description="The appointment Internal Note.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="google_event_id",
*         required=false,
*         description="The appointment Google Event ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="google_event_etag",
*         required=false,
*         description="The appointment Google Event etag.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="outlook_event_id",
*         required=false,
*         description="The appointment Outlook Event ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="outlook_event_change_key",
*         required=false,
*         description="The appointment Outlook Event Change Key.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="outlook_event_series_id",
*         required=false,
*         description="The appointment Outlook Event Series ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="created_from",
*         required=true,
*         description="Indicates from which application the appointment was created.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="created",
*         required=false,
*         description="The appointment creation date. Format: Y-m-d.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Response(response=200, description="success"),
*     @OA\Response(response=404, description="not found"),
*     @OA\Response(response=400, description="bad request"),
*     @OA\Response(response=401, description="unauthorized"),
*     @OA\Response(response=403, description="forbidden"),
*     @OA\Response(response=422, description="unprocessable entity")
* )
*/

/**
* @OA\Delete(
*     path="/wp-json/wp/v2/bookly_appointments/{id}",
*     summary="Delete a appointment object.",
*     tags={"appointments"},
*     @OA\Parameter(
*         in="path",
*         name="id",
*         required=true,
*         description="The appointment ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="integer"))
*     ),
*     @OA\Response(response=200, description="success"),
*     @OA\Response(response=404, description="not found"),
*     @OA\Response(response=400, description="bad request"),
*     @OA\Response(response=401, description="unauthorized"),
*     @OA\Response(response=403, description="forbidden")
* )
*/

/**
* @OA\Get(
*     path="/wp-json/wp/v2/bookly_appointments/availability",
*     summary="Get Appointments Availability",
*     description="Get an array of available slots.",
*     operationId="getAppointmentAvailability",
*     tags={"appointments"},
*     @OA\Parameter(
*         in="query",
*         name="date_from",
*         required=true,
*         description="Date from which you want to look for availability. Format: Y-m-d",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="days",
*         required=true,
*         description="Days of the week in which you want to find availability. Options: [1,2,3,4,5,6,7]",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="time_from",
*         required=true,
*         description="Time from which you want to look for availability. Format: 24h format (10:00)",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="time_to",
*         required=true,
*         description="Time to which you want to look for availability. Format: 24h format (10:00)",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="service_id",
*         required=true,
*         description="The appointment Service ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="staff_ids",
*         required=true,
*         description="A list of staff IDs, separated by commas. For instance: 1,33,44.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),*
*     @OA\Response(response=200, description="success"),
*     @OA\Response(response=404, description="not found"),
*     @OA\Response(response=400, description="bad request"),
*     @OA\Response(response=401, description="unauthorized"),
*     @OA\Response(response=403, description="forbidden")
* )
*/
