<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRequest;
use App\Http\Requests\RoleRequest;
use App\Role;
use App\Services\RoleService;
use Config;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * @var RoleService
     */
    private $roleService;

    /**
     * RoleController constructor.
     * @param RoleService $roleService
     */
    public function __construct(
        RoleService $roleService
    ) {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Role::class);

        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(RoleRequest $request)
    {
        $this->authorize('create', Role::class);

        try {
            $this->roleService->store($request);
            return redirect(Role::REDIRECT)->with('success', __('Role have created success!'));
        } catch (\Exception $exception) {
            return back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Role $role)
    {
        $this->authorize('update', Role::class);
        $permissions = $role->permissions->toArray();
        $permissionsArr = [];
        foreach ($permissions as $permission) {
            $permissionsArr[] = $permission['permission_code'];
        }
        $permissionsString = implode(',', $permissionsArr);
        return view('admin.role.edit', compact('role', 'permissionsString'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param Role $role
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('update', Role::class);
        try {
            if ($this->roleService->update($request, $role)) {
                return redirect(Role::REDIRECT)->with('success', __('Role have updated success.'));
            }
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        try {
            $this->roleService->delete($role);
            return response()->json([
                'status' => 200,
                'message' => __('Role have deleted success.')
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 400,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Show Assign to user form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showAssignForm()
    {
        $this->authorize('viewAny', Role::class);
        return view('admin.role.assign');
    }

    /**
     * Assign list user to a role
     *
     * @param AssignRequest $request
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function doAssign(AssignRequest $request)
    {
        $this->authorize('viewAny', Role::class);
        try {
            $this->roleService->doAssign($request);
            return back()->with('success', __('Succ'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Fetch all role
     *
     * @return Role[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function fetch()
    {
        $this->authorize('viewAny', Role::class);
        return request()->ajax() ?
            response()->json([
                'data' => $this->roleService->fetch()
            ]) : $this->roleService->fetch();
    }

    /**
     * Get a role
     *
     * @param Role $role
     * @return Role|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function get(Role $role)
    {
        $this->authorize('viewAny', Role::class);
        return request()->ajax() ?
            response()->json([
                'data' => $role
            ]) : $role;
    }

    /**
     * Make a payment by momo
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function momoTest()
    {
        try {
            $endpoint = config('app.momo.endpoint');
            $partnerCode = config('app.momo.partner_code');
            $accessKey = config('app.momo.access_key');
            $secretKey = config('app.momo.secret_key');
            $amount = '3000000';
            $requestId = time() . '';
            $orderId = time() . '';
            $orderInfo = 'Test thanh toán momo';
            $returnUrl = 'https://dmovie.vn/admin/momo/callback';
            $notifyurl = 'https://dmovie.vn/admin/momo/notify';
            $extraData = '';
            $requestType = config('app.momo.request_type');

            $rawHash = "partnerCode=" . $partnerCode .
                "&accessKey=" . $accessKey .
                "&requestId=" . $requestId .
                "&amount=" . $amount.
                "&orderId=" . $orderId .
                "&orderInfo=" . $orderInfo .
                "&returnUrl=" . $returnUrl .
                "&notifyUrl=" . $notifyurl .
                "&extraData=" . "";
            $signature = hash_hmac("sha256", $rawHash, $secretKey);
            $data = [
                'partnerCode' => $partnerCode,
                'accessKey' => $accessKey,
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'returnUrl' => $returnUrl,
                'notifyUrl' => $notifyurl,
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];
            $jsonResult = $this->execPostRequest($endpoint, json_encode($data));
            $result = json_decode($jsonResult, true);
            return redirect($result['payUrl']);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Send post request to endpoint url with json data
     *
     * @param string $url
     * @param array $data
     * @return bool|string
     */
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }


    public function momoCallback()
    {
        dd(request()->all());
    }
}
