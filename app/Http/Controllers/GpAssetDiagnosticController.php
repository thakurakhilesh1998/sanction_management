<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Models\Gp_List;
use App\Models\PriAsset;
use App\Models\PriAssetSubmission;
use Throwable;

class GpAssetDiagnosticController extends Controller
{
    public function index(Request $request)
    {
        $results = [];

        $user = null;
        $gp = null;
        $submission = null;
        $assets = collect();


        /*
        |--------------------------------------------------------------------------
        | Helper functions
        |--------------------------------------------------------------------------
        */

        $pass = function ($name, $message) use (&$results) {

            $results[] = [
                'name'    => $name,
                'status'  => 'PASS',
                'message' => $message,
                'file'    => null,
                'line'    => null,
                'trace'   => null,
            ];
        };


        $info = function ($name, $message) use (&$results) {

            $results[] = [
                'name'    => $name,
                'status'  => 'INFO',
                'message' => $message,
                'file'    => null,
                'line'    => null,
                'trace'   => null,
            ];
        };


        $skip = function ($name, $message) use (&$results) {

            $results[] = [
                'name'    => $name,
                'status'  => 'SKIPPED',
                'message' => $message,
                'file'    => null,
                'line'    => null,
                'trace'   => null,
            ];
        };


        $fail = function ($name, Throwable $e) use (&$results) {

            $results[] = [
                'name'    => $name,
                'status'  => 'FAIL',
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ];
        };


        $check = function ($name, $callback) use (
            &$results,
            $pass,
            $fail
        ) {

            try {

                $message = $callback();

                $pass(
                    $name,
                    $message === null ? 'OK' : (string) $message
                );

            } catch (Throwable $e) {

                $fail($name, $e);
            }
        };


        /*
        |--------------------------------------------------------------------------
        | 1. Laravel
        |--------------------------------------------------------------------------
        */

        $check('Laravel Application', function () {
            return 'Laravel is running.';
        });


        $check('PHP Version', function () {
            return PHP_VERSION;
        });


        $check('Laravel Version', function () {
            return app()->version();
        });


        $check('Application Environment', function () {
            return app()->environment();
        });


        /*
        |--------------------------------------------------------------------------
        | 2. Authentication
        |--------------------------------------------------------------------------
        */

        $check('GP Authentication', function () use (&$user) {

            if (!Auth::check()) {

                throw new \Exception(
                    'No authenticated user is available. ' .
                    'Please login through the GP account.'
                );
            }

            $user = Auth::user();

            return 'Authenticated user ID: ' . ($user->id ?? 'N/A');
        });


        /*
        |--------------------------------------------------------------------------
        | 3. User Model
        |--------------------------------------------------------------------------
        */

        $check('Authenticated User Model', function () use (&$user) {

            if (!$user) {
                throw new \Exception(
                    'Auth::user() is not available.'
                );
            }

            return get_class($user);
        });


        /*
        |--------------------------------------------------------------------------
        | 4. User GP Name
        |--------------------------------------------------------------------------
        */

        $check('User GP Name', function () use (&$user) {

            if (!$user) {
                throw new \Exception(
                    'User object not available.'
                );
            }

            if (!isset($user->gp_name)) {

                throw new \Exception(
                    'Authenticated user does not have gp_name.'
                );
            }

            if (trim((string) $user->gp_name) === '') {

                throw new \Exception(
                    'Authenticated user gp_name is empty.'
                );
            }

            return 'GP Name: ' . $user->gp_name;
        });


        /*
        |--------------------------------------------------------------------------
        | 5. User Block
        |--------------------------------------------------------------------------
        */

        $check('User Block Name', function () use (&$user) {

            if (!$user) {
                throw new \Exception(
                    'User object not available.'
                );
            }

            if (!isset($user->block_name)) {

                throw new \Exception(
                    'Authenticated user does not have block_name.'
                );
            }

            if (trim((string) $user->block_name) === '') {

                throw new \Exception(
                    'Authenticated user block_name is empty.'
                );
            }

            return 'Block: ' . $user->block_name;
        });


        /*
        |--------------------------------------------------------------------------
        | 6. User District
        |--------------------------------------------------------------------------
        */

        $check('User District', function () use (&$user) {

            if (!$user) {
                throw new \Exception(
                    'User object not available.'
                );
            }

            if (!isset($user->district)) {

                throw new \Exception(
                    'Authenticated user does not have district.'
                );
            }

            if (trim((string) $user->district) === '') {

                throw new \Exception(
                    'Authenticated user district is empty.'
                );
            }

            return 'District: ' . $user->district;
        });


        /*
        |--------------------------------------------------------------------------
        | 7. Database
        |--------------------------------------------------------------------------
        */

        $check('Database Connection', function () {

            DB::connection()->getPdo();

            return 'Database connection successful.';
        });


        $check('Database Name', function () {

            return DB::connection()->getDatabaseName();
        });


        $check('Database Driver', function () {

            return DB::connection()->getDriverName();
        });


        /*
        |--------------------------------------------------------------------------
        | 8. gp_list
        |--------------------------------------------------------------------------
        */

        $check('gp_list Table', function () {

            if (!Schema::hasTable('gp_list')) {

                throw new \Exception(
                    'gp_list table does not exist.'
                );
            }

            return 'gp_list table exists.';
        });


        $check('gp_list Required Columns', function () {

            $required = [
                'id',
                'district_name',
                'block_name',
                'gp_name',
            ];

            $missing = [];

            foreach ($required as $column) {

                if (!Schema::hasColumn(
                    'gp_list',
                    $column
                )) {
                    $missing[] = $column;
                }
            }

            if (!empty($missing)) {

                throw new \Exception(
                    'Missing columns: ' .
                    implode(', ', $missing)
                );
            }

            return 'All required columns exist.';
        });


        /*
        |--------------------------------------------------------------------------
        | 9. Gp_List Model
        |--------------------------------------------------------------------------
        */

        $check('Gp_List Model', function () {

            if (!class_exists(Gp_List::class)) {

                throw new \Exception(
                    'App\Models\Gp_List was not found.'
                );
            }

            return 'Model exists.';
        });


        $check('Gp_List Model Table', function () {

            $model = new Gp_List();

            return 'Model uses table: ' .
                $model->getTable();
        });


        /*
        |--------------------------------------------------------------------------
        | 10. Find Current GP
        |--------------------------------------------------------------------------
        |
        | EXACT SAME MATCHING LOGIC AS GPAssetController
        |--------------------------------------------------------------------------
        */

        $check('Find Current GP', function () use (
            &$user,
            &$gp
        ) {

            if (!$user) {

                throw new \Exception(
                    'Authenticated user not available.'
                );
            }

            $district = $user->district ?? null;
            $block    = $user->block_name ?? null;
            $gpName   = $user->gp_name ?? null;


            if (!$district) {

                throw new \Exception(
                    'User district is empty.'
                );
            }


            if (!$block) {

                throw new \Exception(
                    'User block_name is empty.'
                );
            }


            if (!$gpName) {

                throw new \Exception(
                    'User gp_name is empty.'
                );
            }


            $gp = Gp_List::where(
                    'district_name',
                    $district
                )
                ->where(
                    'block_name',
                    $block
                )
                ->where(
                    'gp_name',
                    $gpName
                )
                ->first();


            if (!$gp) {

                throw new \Exception(
                    'GP could not be found using the exact ' .
                    'matching logic used by GPAssetController. ' .
                    'District="' . $district .
                    '", Block="' . $block .
                    '", GP="' . $gpName . '"'
                );
            }


            return
                'GP found. ID: ' .
                $gp->id .
                ' | District: ' .
                $gp->district_name .
                ' | Block: ' .
                $gp->block_name .
                ' | GP: ' .
                $gp->gp_name;
        });


        /*
        |--------------------------------------------------------------------------
        | 11. pri_assets Table
        |--------------------------------------------------------------------------
        */

        $check('pri_assets Table', function () {

            if (!Schema::hasTable('pri_assets')) {

                throw new \Exception(
                    'pri_assets table does not exist.'
                );
            }

            return 'pri_assets table exists.';
        });


        /*
        |--------------------------------------------------------------------------
        | 12. pri_assets Columns
        |--------------------------------------------------------------------------
        */

        $check('pri_assets Required Columns', function () {

            $required = [
                'id',
                'asset_uuid',
                'gp_id',
                'asset_category',
                'asset_sub_category',
                'building_name',
                'ownership',
                'panchayat_office_status',
                'latitude',
                'longitude',
                'location_accuracy',
                'location_captured_at',
                'asset_image',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ];

            $missing = [];

            foreach ($required as $column) {

                if (!Schema::hasColumn(
                    'pri_assets',
                    $column
                )) {
                    $missing[] = $column;
                }
            }


            if (!empty($missing)) {

                throw new \Exception(
                    'Missing columns: ' .
                    implode(', ', $missing)
                );
            }


            return 'All required columns exist.';
        });


        /*
        |--------------------------------------------------------------------------
        | 13. PriAsset Model
        |--------------------------------------------------------------------------
        */

        $check('PriAsset Model', function () {

            if (!class_exists(PriAsset::class)) {

                throw new \Exception(
                    'App\Models\PriAsset was not found.'
                );
            }

            return 'Model exists.';
        });


        $check('PriAsset Model Table', function () {

            $model = new PriAsset();

            return 'Model uses table: ' .
                $model->getTable();
        });


        /*
        |--------------------------------------------------------------------------
        | 14. Soft Delete
        |--------------------------------------------------------------------------
        */

        $check('PriAsset Soft Delete', function () {

            $model = new PriAsset();

            $traits = class_uses_recursive($model);

            if (!in_array(
                \Illuminate\Database\Eloquent\SoftDeletes::class,
                $traits
            )) {

                throw new \Exception(
                    'PriAsset model does not use SoftDeletes.'
                );
            }

            return 'SoftDeletes trait is present.';
        });


        /*
        |--------------------------------------------------------------------------
        | 15. pri_asset_submissions Table
        |--------------------------------------------------------------------------
        */

        $check(
            'pri_asset_submissions Table',
            function () {

                if (!Schema::hasTable(
                    'pri_asset_submissions'
                )) {

                    throw new \Exception(
                        'pri_asset_submissions table does not exist.'
                    );
                }

                return 'Table exists.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 16. Submission Columns
        |--------------------------------------------------------------------------
        */

        $check(
            'pri_asset_submissions Required Columns',
            function () {

                $required = [
                    'id',
                    'gp_id',
                    'status',
                    'submitted_at',
                    'submitted_by',
                    'created_at',
                    'updated_at',
                ];

                $missing = [];

                foreach ($required as $column) {

                    if (!Schema::hasColumn(
                        'pri_asset_submissions',
                        $column
                    )) {
                        $missing[] = $column;
                    }
                }


                if (!empty($missing)) {

                    throw new \Exception(
                        'Missing columns: ' .
                        implode(', ', $missing)
                    );
                }


                return 'All required columns exist.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 17. PriAssetSubmission Model
        |--------------------------------------------------------------------------
        */

        $check(
            'PriAssetSubmission Model',
            function () {

                if (!class_exists(
                    PriAssetSubmission::class
                )) {

                    throw new \Exception(
                        'App\Models\PriAssetSubmission was not found.'
                    );
                }

                return 'Model exists.';
            }
        );


        $check(
            'PriAssetSubmission Model Table',
            function () {

                $model =
                    new PriAssetSubmission();

                return
                    'Model uses table: ' .
                    $model->getTable();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 18. GP -> Assets Relationship
        |--------------------------------------------------------------------------
        */

        if ($gp) {

            $check(
                'GP -> priAssets Relationship',
                function () use (&$gp) {

                    $relation =
                        $gp->priAssets();

                    if (!$relation) {

                        throw new \Exception(
                            'priAssets relationship returned null.'
                        );
                    }

                    return
                        'Relationship is valid.';
                }
            );

        } else {

            $skip(
                'GP -> priAssets Relationship',
                'Skipped because GP lookup failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 19. GP -> Submission Relationship
        |--------------------------------------------------------------------------
        */

        if ($gp) {

            $check(
                'GP -> priAssetSubmission Relationship',
                function () use (&$gp) {

                    $relation =
                        $gp->priAssetSubmission();

                    if (!$relation) {

                        throw new \Exception(
                            'priAssetSubmission relationship returned null.'
                        );
                    }

                    return
                        'Relationship is valid.';
                }
            );

        } else {

            $skip(
                'GP -> priAssetSubmission Relationship',
                'Skipped because GP lookup failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 20. Current GP Assets
        |--------------------------------------------------------------------------
        */

        if ($gp) {

            $check(
                'Current GP Asset Query',
                function () use (
                    &$gp,
                    &$assets
                ) {

                    $assets =
                        PriAsset::where(
                            'gp_id',
                            $gp->id
                        )
                        ->where(
                            'is_active',
                            true
                        )
                        ->get();

                    return
                        'Active assets found: ' .
                        $assets->count();
                }
            );

        } else {

            $skip(
                'Current GP Asset Query',
                'Skipped because GP lookup failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 21. Non-deleted Assets
        |--------------------------------------------------------------------------
        */

        if ($gp) {

            $check(
                'Current GP Non-Deleted Assets',
                function () use (&$gp) {

                    $count =
                        PriAsset::where(
                            'gp_id',
                            $gp->id
                        )
                        ->where(
                            'is_active',
                            true
                        )
                        ->whereNull(
                            'deleted_at'
                        )
                        ->count();

                    return
                        'Active non-deleted assets: ' .
                        $count;
                }
            );

        } else {

            $skip(
                'Current GP Non-Deleted Assets',
                'Skipped because GP lookup failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 22. Submission Record
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | We use first() rather than firstOrCreate()
        | so the diagnostic DOES NOT modify your database.
        |--------------------------------------------------------------------------
        */

        if ($gp) {

            $check(
                'Current GP Submission',
                function () use (
                    &$gp,
                    &$submission
                ) {

                    $submission =
                        PriAssetSubmission::where(
                            'gp_id',
                            $gp->id
                        )->first();

                    if (!$submission) {

                        return
                            'No submission record exists for this GP. ' .
                            'This is not automatically an error because ' .
                            'GPAssetController can create a draft using firstOrCreate().';
                    }

                    return
                        'Submission ID: ' .
                        $submission->id .
                        ' | Status: ' .
                        $submission->status;
                }
            );

        } else {

            $skip(
                'Current GP Submission',
                'Skipped because GP lookup failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 23. Submission Status
        |--------------------------------------------------------------------------
        */

        if ($submission) {

            $check(
                'Submission Status',
                function () use (&$submission) {

                    if (!in_array(
                        $submission->status,
                        [
                            'draft',
                            'submitted'
                        ]
                    )) {

                        throw new \Exception(
                            'Unexpected submission status: ' .
                            $submission->status
                        );
                    }

                    return
                        'Status is valid: ' .
                        $submission->status;
                }
            );

        } else {

            $skip(
                'Submission Status',
                'No submission record exists.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 24. Frozen Logic
        |--------------------------------------------------------------------------
        */

        if ($submission) {

            $check(
                'GP Frozen Status Logic',
                function () use (&$submission) {

                    $isFrozen =
                        $submission->status === 'submitted';

                    return
                        $isFrozen
                            ? 'GP IS FROZEN (status = submitted).'
                            : 'GP IS NOT FROZEN (status = draft).';
                }
            );

        } else {

            $skip(
                'GP Frozen Status Logic',
                'No submission record exists.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 25. add-asset Blade
        |--------------------------------------------------------------------------
        */

        $check(
            'add-asset Blade Exists',
            function () {

                $path = resource_path(
                    'views/GP/Asset/add-asset.blade.php'
                );

                if (!file_exists($path)) {

                    throw new \Exception(
                        'File not found: ' . $path
                    );
                }

                return 'Blade file exists.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 26. view-assets Blade
        |--------------------------------------------------------------------------
        */

        $check(
            'view-assets Blade Exists',
            function () {

                $path = resource_path(
                    'views/GP/Asset/view-assets.blade.php'
                );

                if (!file_exists($path)) {

                    throw new \Exception(
                        'File not found: ' . $path
                    );
                }

                return 'Blade file exists.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 27. layouts.gp
        |--------------------------------------------------------------------------
        */

        $check(
            'layouts.gp View',
            function () {

                if (!View::exists('layouts.gp')) {

                    throw new \Exception(
                        'layouts.gp view does not exist.'
                    );
                }

                return 'layouts.gp exists.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 28. Render add-asset Blade
        |--------------------------------------------------------------------------
        |
        | This actually renders the view.
        |--------------------------------------------------------------------------
        */

        $check(
            'Render add-asset Blade',
            function () {

                $view =
                    view('GP.Asset.add-asset');

                $html =
                    $view->render();

                if (trim($html) === '') {

                    throw new \Exception(
                        'Blade rendered empty HTML.'
                    );
                }

                return
                    'Rendered successfully. HTML length: ' .
                    strlen($html);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 29. Render view-assets Blade
        |--------------------------------------------------------------------------
        |
        | We use the same variables normally supplied by viewAssets().
        |--------------------------------------------------------------------------
        */

        if ($gp) {

            $check(
                'Render view-assets Blade',
                function () use (
                    &$gp,
                    &$assets,
                    &$submission
                ) {

                    $district =
                        $gp->district_name;

                    $block =
                        $gp->block_name;

                    $gpName =
                        $gp->gp_name;

                    $isFrozen =
                        $submission &&
                        $submission->status === 'submitted';


                    $view =
                        view(
                            'GP.Asset.view-assets',
                            compact(
                                'district',
                                'block',
                                'gpName',
                                'assets',
                                'submission',
                                'isFrozen'
                            )
                        );


                    $html =
                        $view->render();


                    if (trim($html) === '') {

                        throw new \Exception(
                            'view-assets Blade rendered empty HTML.'
                        );
                    }


                    return
                        'Rendered successfully. HTML length: ' .
                        strlen($html);
                }
            );

        } else {

            $skip(
                'Render view-assets Blade',
                'Skipped because GP lookup failed.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 30. Storage
        |--------------------------------------------------------------------------
        */

        $check(
            'Public Storage Disk',
            function () {

                Storage::disk('public');

                return
                    'Public storage disk is configured.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 31. Public Storage Link
        |--------------------------------------------------------------------------
        */

        $check(
            'Public Storage Link',
            function () {

                $path =
                    public_path('storage');

                if (!file_exists($path)) {

                    throw new \Exception(
                        'public/storage does not exist. ' .
                        'storage:link may be missing.'
                    );
                }

                return
                    'public/storage exists.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 32. GP Asset Routes
        |--------------------------------------------------------------------------
        |
        | We DON'T assume route names.
        | We inspect registered URL paths instead.
        |--------------------------------------------------------------------------
        */

        $check(
            'GP Asset Routes',
            function () {

                $found = [];

                foreach (Route::getRoutes() as $route) {

                    $uri =
                        $route->uri();

                    if (
                        str_contains(
                            $uri,
                            'gp'
                        ) &&
                        (
                            str_contains(
                                $uri,
                                'asset'
                            ) ||
                            str_contains(
                                $uri,
                                'assets'
                            )
                        )
                    ) {

                        $found[] =
                            $route->methods()[0] .
                            ' ' .
                            $uri;
                    }
                }


                if (empty($found)) {

                    throw new \Exception(
                        'No GP asset-related routes were found.'
                    );
                }


                return
                    implode(
                        ' | ',
                        array_unique($found)
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 33. Locate add-assets Route
        |--------------------------------------------------------------------------
        */

        $check(
            'Route /gp/add-assets',
            function () {

                foreach (Route::getRoutes() as $route) {

                    if (
                        trim($route->uri(), '/') ===
                        'gp/add-assets'
                    ) {

                        return
                            'Route exists. Methods: ' .
                            implode(
                                ', ',
                                $route->methods()
                            );
                    }
                }


                throw new \Exception(
                    'Route /gp/add-assets is not registered.'
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 34. Locate view-assets Route
        |--------------------------------------------------------------------------
        */

        $check(
            'Route /gp/view-assets',
            function () {

                foreach (Route::getRoutes() as $route) {

                    if (
                        trim($route->uri(), '/') ===
                        'gp/view-assets'
                    ) {

                        return
                            'Route exists. Methods: ' .
                            implode(
                                ', ',
                                $route->methods()
                            );
                    }
                }


                throw new \Exception(
                    'Route /gp/view-assets is not registered.'
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 35. Actual addAsset() Controller
        |--------------------------------------------------------------------------
        |
        | This executes your real controller method
        | under the ACTUAL GP login.
        |--------------------------------------------------------------------------
        */

        $addAssetResponse = null;

        $check(
            'Execute GPAssetController::addAsset()',
            function () use (
                &$addAssetResponse
            ) {

                $controller =
                    app(
                        \App\Http\Controllers\GPAssetController::class
                    );


                $addAssetResponse =
                    $controller->addAsset();


                if (!$addAssetResponse) {

                    throw new \Exception(
                        'addAsset() returned NULL.'
                    );
                }


                return
                    'Method executed. Response: ' .
                    get_class(
                        $addAssetResponse
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 36. Render actual addAsset() response
        |--------------------------------------------------------------------------
        */

        if ($addAssetResponse instanceof \Illuminate\View\View) {

            $check(
                'Render Actual addAsset() Response',
                function () use (
                    &$addAssetResponse
                ) {

                    $html =
                        $addAssetResponse->render();


                    if (trim($html) === '') {

                        throw new \Exception(
                            'addAsset() returned a View but ' .
                            'the rendered HTML is empty.'
                        );
                    }


                    return
                        'Actual addAsset() response rendered successfully. HTML length: ' .
                        strlen($html);
                }
            );

        } else {

            $skip(
                'Render Actual addAsset() Response',
                'addAsset() did not return an Illuminate View.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 37. Actual viewAssets() Controller
        |--------------------------------------------------------------------------
        */

        $viewAssetsResponse = null;

        $check(
            'Execute GPAssetController::viewAssets()',
            function () use (
                &$viewAssetsResponse
            ) {

                $controller =
                    app(
                        \App\Http\Controllers\GPAssetController::class
                    );


                $viewAssetsResponse =
                    $controller->viewAssets();


                if (!$viewAssetsResponse) {

                    throw new \Exception(
                        'viewAssets() returned NULL.'
                    );
                }


                if (
                    $viewAssetsResponse
                    instanceof
                    \Illuminate\Http\RedirectResponse
                ) {

                    return
                        'Method returned REDIRECT to: ' .
                        $viewAssetsResponse->getTargetUrl();
                }


                return
                    'Method executed. Response: ' .
                    get_class(
                        $viewAssetsResponse
                    );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 38. Render actual viewAssets() response
        |--------------------------------------------------------------------------
        */

        if (
            $viewAssetsResponse
            instanceof
            \Illuminate\View\View
        ) {

            $check(
                'Render Actual viewAssets() Response',
                function () use (
                    &$viewAssetsResponse
                ) {

                    $html =
                        $viewAssetsResponse->render();


                    if (trim($html) === '') {

                        throw new \Exception(
                            'viewAssets() returned a View but ' .
                            'the rendered HTML is empty.'
                        );
                    }


                    return
                        'Actual viewAssets() response rendered successfully. HTML length: ' .
                        strlen($html);
                }
            );

        } elseif (
            $viewAssetsResponse
            instanceof
            \Illuminate\Http\RedirectResponse
        ) {

            $info(
                'Render Actual viewAssets() Response',
                'viewAssets() returned a redirect. Target: ' .
                $viewAssetsResponse->getTargetUrl()
            );

        } else {

            $skip(
                'Render Actual viewAssets() Response',
                'viewAssets() did not return a View.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 39. Controller Class
        |--------------------------------------------------------------------------
        */

        $check(
            'GPAssetController Class',
            function () {

                if (!class_exists(
                    \App\Http\Controllers\GPAssetController::class
                )) {

                    throw new \Exception(
                        'GPAssetController class not found.'
                    );
                }

                return
                    'GPAssetController class exists.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 40. Controller Required Methods
        |--------------------------------------------------------------------------
        */

        $check(
            'GPAssetController Required Methods',
            function () {

                $controller =
                    \App\Http\Controllers\GPAssetController::class;


                $required = [
                    'addAsset',
                    'viewAssets',
                    'store',
                    'update',
                    'destroy',
                    'finalSubmit',
                ];


                $missing = [];


                foreach ($required as $method) {

                    if (!method_exists(
                        $controller,
                        $method
                    )) {

                        $missing[] = $method;
                    }
                }


                if (!empty($missing)) {

                    throw new \Exception(
                        'Missing controller methods: ' .
                        implode(', ', $missing)
                    );
                }


                return
                    'All required controller methods exist.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 41. PHP Extensions
        |--------------------------------------------------------------------------
        */

        $check(
            'PHP PDO MySQL Extension',
            function () {

                if (!extension_loaded('pdo_mysql')) {

                    throw new \Exception(
                        'pdo_mysql extension is not loaded.'
                    );
                }

                return 'pdo_mysql is loaded.';
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 42. File Upload Configuration
        |--------------------------------------------------------------------------
        */

        $check(
            'PHP Upload Max Filesize',
            function () {

                return
                    'upload_max_filesize = ' .
                    ini_get('upload_max_filesize');
            }
        );


        $check(
            'PHP Post Max Size',
            function () {

                return
                    'post_max_size = ' .
                    ini_get('post_max_size');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 43. Current Request URL
        |--------------------------------------------------------------------------
        */

        $check(
            'Current Application URL',
            function () use ($request) {

                return
                    $request->fullUrl();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 44. Final Summary
        |--------------------------------------------------------------------------
        */

        $passed =
            collect($results)
                ->where('status', 'PASS')
                ->count();


        $failed =
            collect($results)
                ->where('status', 'FAIL')
                ->count();


        $skipped =
            collect($results)
                ->where('status', 'SKIPPED')
                ->count();


        $infoCount =
            collect($results)
                ->where('status', 'INFO')
                ->count();


        return view(
            'GP.Asset.diagnostic',
            compact(
                'results',
                'passed',
                'failed',
                'skipped',
                'infoCount'
            )
        );
    }
}