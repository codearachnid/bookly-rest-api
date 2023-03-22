<?php

/**
* @OA\Get(
*     path="/wp-json/wp/v2/bookly_customers",
*     summary="Get a paginated array of customers objects.",
*     tags={"customers"},
*     @OA\Response(response=200, description="success"),
*     @OA\Response(response=404, description="not found"),
*     @OA\Response(response=400, description="bad request"),
*     @OA\Response(response=401, description="unauthorized"),
*     @OA\Response(response=403, description="forbidden"),
* )
*/

/**
* @OA\Get(
*     path="/wp-json/wp/v2/bookly_customers/{id}",
*     summary="Get a single customer object.",
*     tags={"customers"},
*     @OA\Parameter(
*         in="path",
*         name="id",
*         required=true,
*         description="The customer ID.",
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
* @OA\Post(
*     path="/wp-json/wp/v2/bookly_customers",
*     summary="Store a single customer object.",
*     tags={"customers"},
*     @OA\Parameter(
*         in="query",
*         name="full_name",
*         required=true,
*         description="The customer Full Name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="wp_user_id",
*         required=false,
*         description="The customer Wordpress User ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="first_name",
*         required=false,
*         description="The customer First Name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="last_name",
*         required=false,
*         description="The customer Last Name",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="phone",
*         required=false,
*         description="The customer Phone Number.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="email",
*         required=false,
*         description="The customer E-mail Address.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="birthday",
*         required=false,
*         description="The customer Birthday.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="country",
*         required=false,
*         description="The customer Country.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="state",
*         required=false,
*         description="The customer State.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="postcode",
*         required=false,
*         description="The customer Post Code.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="city",
*         required=false,
*         description="The customer City name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="street",
*         required=false,
*         description="The customer Street name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="additional_address",
*         required=false,
*         description="The customer Additional Address.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="notes",
*         required=false,
*         description="The customer Notes.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="info_fields",
*         required=false,
*         description="The customer Info Fields.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="created",
*         required=false,
*         description="The customer Creation Date.",
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
*     path="/wp-json/wp/v2/bookly_customers/{id}",
*     summary="Update a single customer object.",
*     tags={"customers"},
*     @OA\Parameter(
*         in="path",
*         name="id",
*         required=true,
*         description="The customer ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="integer"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="full_name",
*         required=true,
*         description="The customer Full Name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="wp_user_id",
*         required=false,
*         description="The customer Wordpress User ID.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="first_name",
*         required=false,
*         description="The customer First Name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="last_name",
*         required=false,
*         description="The customer Last Name",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="phone",
*         required=false,
*         description="The customer Phone Number.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="email",
*         required=false,
*         description="The customer E-mail Address.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="birthday",
*         required=false,
*         description="The customer Birthday.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="country",
*         required=false,
*         description="The customer Country.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="state",
*         required=false,
*         description="The customer State.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="postcode",
*         required=false,
*         description="The customer Post Code.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="city",
*         required=false,
*         description="The customer City name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="street",
*         required=false,
*         description="The customer Street name.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="additional_address",
*         required=false,
*         description="The customer Additional Address.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="notes",
*         required=false,
*         description="The customer Notes.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="info_fields",
*         required=false,
*         description="The customer Info Fields.",
*         style="form",
*         @OA\Schema(type="array", @OA\Items(type="string"))
*     ),
*     @OA\Parameter(
*         in="query",
*         name="created",
*         required=false,
*         description="The customer Creation Date.",
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
*     path="/wp-json/wp/v2/bookly_customers/{id}",
*     summary="Delete a customer object.",
*     tags={"customers"},
*     @OA\Parameter(
*         in="path",
*         name="id",
*         required=true,
*         description="The customer ID.",
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
