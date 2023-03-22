<?php
    /**
     * @OA\Get(
     *     path="/wp-json/wp/v2/bookly_staff",
     *     summary="Get a paginated array of staffs objects.",
     *     tags={"staffs"},
     *     @OA\Parameter(
     *         in="query",
     *         name="service_id",
     *         required=false,
     *         description="The service ID",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
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
     *     path="/wp-json/wp/v2/bookly_staff",
     *     summary="Store a single staff object.",
     *     tags={"staffs"},
     *     @OA\Parameter(
     *         in="query",
     *         name="category_id",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="wp_user_id",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="attachment_id",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="full_name",
     *         required=true,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="phone",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="info",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="working_time_limit",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="visibility",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="position",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="google_data",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="outlook_data",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
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
     *     path="/wp-json/wp/v2/bookly_staff/{id}",
     *     summary="Get a single staff object",
     *     description="Get a single staff data object providing its unique ID number.",
     *     operationId="getStaff",
     *     tags={"staffs"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         description="The staff ID.",
     *         style="form",
     *         @OA\Schema(type="integer", @OA\Items(type="integer"))
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
     *     path="/wp-json/wp/v2/bookly_staff/{id}",
     *     summary="Store a single staff object.",
     *     tags={"staffs"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         description="The staff ID.",
     *         style="form",
     *         @OA\Schema(type="integer", @OA\Items(type="integer"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="category_id",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="wp_user_id",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="attachment_id",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="full_name",
     *         required=true,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="email",
     *         required=true,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="phone",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="info",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="working_time_limit",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="visibility",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="position",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="google_data",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="outlook_data",
     *         required=false,
     *         description="",
     *         style="form",
     *         @OA\Schema(type="string", @OA\Items(type="string"))
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
     *     path="/wp-json/wp/v2/bookly_staff/{id}",
     *     summary="Delete a staff object.",
     *     tags={"staffs"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         description="The staff ID.",
     *         style="form",
     *         @OA\Schema(type="integer", @OA\Items(type="integer"))
     *     ),
     *     @OA\Response(response=200, description="success"),
     *     @OA\Response(response=404, description="not found"),
     *     @OA\Response(response=400, description="bad request"),
     *     @OA\Response(response=401, description="unauthorized"),
     *     @OA\Response(response=403, description="forbidden")
     * )
     */
