<?php

/**
 * @OA\Get(
 *     path="/wp-json/wp/v2/bookly_services",
 *     summary="Get a paginated array of services objects.",
 *     tags={"services"},
 *     @OA\Parameter(
 *         in="query",
 *         name="staff_id",
 *         required=false,
 *         description="The staff ID",
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
 * @OA\Post(
 *     path="/wp-json/wp/v2/bookly_services",
 *     summary="Store a single service object.",
 *     tags={"services"},
 *     @OA\Parameter(
 *         in="query",
 *         name="category_id",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="type",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="title",
 *         required=true,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="duration",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="slot_length",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="price",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="color",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="deposit",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="capacity_min",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="capacity_max",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="one_booking_per_slot",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="padding_left",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="padding_right",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="info",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="start_time_info",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="end_time_info",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="units_min",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="units_max",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="package_life_time",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="package_size",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="package_unassigned",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="appointments_limit",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="limit_period",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="staff_preference",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="staff_preference_settings",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="recurrence_enabled",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="recurrence_frequencies",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="time_requirements",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="collaborative_equal_duration",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="visibility",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="position",
 *         required=false,
 *         description="",
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
 * @OA\Put(
 *     path="/wp-json/wp/v2/bookly_services/{id}",
 *     summary="Update a single service object.",
 *     tags={"services"},
 *     @OA\Parameter(
 *         in="path",
 *         name="id",
 *         required=true,
 *         description="The service ID.",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="integer"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="category_id",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="type",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="title",
 *         required=true,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="duration",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="slot_length",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="price",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="color",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="deposit",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="capacity_min",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="capacity_max",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="one_booking_per_slot",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="padding_left",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="padding_right",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="info",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="start_time_info",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="end_time_info",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="units_min",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="units_max",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="package_life_time",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="package_size",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="package_unassigned",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="appointments_limit",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="limit_period",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="staff_preference",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="staff_preference_settings",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="recurrence_enabled",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="recurrence_frequencies",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="time_requirements",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="collaborative_equal_duration",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="visibility",
 *         required=false,
 *         description="",
 *         style="form",
 *         @OA\Schema(type="array", @OA\Items(type="string"))
 *     ),
 *     @OA\Parameter(
 *         in="query",
 *         name="position",
 *         required=false,
 *         description="",
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
 *     path="/wp-json/wp/v2/bookly_services/{id}",
 *     summary="Get a single service object",
 *     description="Get a single service data object providing its unique ID number.",
 *     tags={"services"},
 *     @OA\Parameter(
 *         in="path",
 *         name="id",
 *         required=true,
 *         description="The service ID.",
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
 * @OA\Delete(
 *     path="/wp-json/wp/v2/bookly_services/{id}",
 *     summary="Delete a service object.",
 *     tags={"services"},
 *     @OA\Parameter(
 *         in="path",
 *         name="id",
 *         required=true,
 *         description="The service ID.",
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
