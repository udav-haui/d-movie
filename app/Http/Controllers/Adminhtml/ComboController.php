<?php

namespace App\Http\Controllers\Adminhtml;

use App\Combo;
use App\Http\Requests\ComboRequest;
use App\Repositories\Interfaces\ComboRepositoryInterface;

/**
 * Class ComboController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class ComboController extends \App\Http\Controllers\Controller
{
    /**
     * @var ComboRepositoryInterface
     */
    private $comboRepository;

    /**
     * ComboController constructor.
     * @param ComboRepositoryInterface $comboRepository
     */
    public function __construct(
        ComboRepositoryInterface $comboRepository
    ) {
        $this->comboRepository = $comboRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Combo::class);

        if (request()->ajax()) {
            $combos = $this->comboRepository->all();
            $authUser = auth()->user();
            $dt = datatables()->of($combos);



            $dt->editColumn(Combo::STATUS, function (Combo $combo) use ($authUser) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$combo->getStatus() === Combo::ENABLE ? "checked " : "";
                $htmlRaw .= "class=\"status-checkbox\"".
                    "value=\"{$combo->getId()}\"".
                    "data-id=\"{$combo->getId()}\"";
                if ($authUser->cant('update', Combo::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= " dmovie-switch-dt/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$combo->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });


            if ($authUser->can('canEditDelete', Combo::class)) {
                $htmlRaw = "";
                $dt->addColumn('task', function (Combo $combo) use ($authUser, $htmlRaw) {
                    if ($authUser->can('update', Combo::class)) {
                        $htmlRaw .= "<a href=\"" . route('combos.edit', ['combo' => $combo->getId()]) . "\"
                                   type=\"button\" class=\"";
                        if ($authUser->cant('delete', Combo::class)) {
                            $htmlRaw .= "col-md-12 ";
                        } else {
                            $htmlRaw .= 'col-md-6 ';
                        }

                        $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                        $htmlRaw .= "title=\"" . __('Detail') . "\">";
                        $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                    }

                    if ($authUser->can('delete', Combo::class)) {
                        $cssClass = $authUser->can('update', Combo::class) ? "col-md-6" : "col-md-12";

                        $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                            title=\" " . __('Delete') . " \"
                            data-id=\"{$combo->getId()}\"
                            url=\"" . route('combos.destroy', ['combo' => $combo->getId()]) . "\">
                            <i class=\"fa fa-trash-o\"></i>
                        </button>";
                    }

                    return $htmlRaw;
                });
            } else {
                $dt->addColumn('task', '');
            }

            return $dt->rawColumns(['task', 'status'])->make();
        }

        return view('admin.combo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Combo::class);

        return view('admin.combo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ComboRequest $request)
    {
        $this->authorize('create', Combo::class);

        try {
            /** @var Combo $combo */
            $combo = $this->comboRepository->create($request->all());

            return redirect(route('combos.index'))
                ->with('success', __('You saved this <code>:item</code>.', ['item' => $combo->getName()]));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Combo $combo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Combo $combo)
    {
        $this->authorize('update', Combo::class);

        return view('admin.combo.edit', compact('combo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Combo $combo
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(ComboRequest $request, Combo $combo)
    {
        $this->authorize('update', Combo::class);

        try {
            /** @var Combo $combo */
            $combo = $this->comboRepository->update(null, $combo, $request->all());

            return  redirect(route('combos.index'))
                ->with('success', __('The <code>:itemName</code> has been saved.', ['itemName' => $combo->getName()]));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Combo $combo
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Combo $combo)
    {
        $this->authorize('delete', Combo::class);

        try {
            /** @var Combo $combo */
            $combo = $this->comboRepository->delete(null, $combo);

            $message = __('The <code>:itemName</code> has been deleted.', ['itemName' => $combo->getName()]);
            return !request()->ajax() ?
                redirect(route('combos.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }

    /**
     * Mass destroy model
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massDestroy()
    {
        $this->authorize('delete', Combo::class);

        try {
            $combos = request('combos');

            $deletedItems = [];

            /** @var string $combo */
            foreach ($combos as $combo) {
                /** @var Combo $deletedItem */
                $deletedItem = $this->comboRepository->delete($combo, null);

                array_push($deletedItems, $combo);
            }

            $message = __('The [:itemName] have been deleted.', ['itemName' => implode(', ', $deletedItems)]);
            return !request()->ajax() ?
                redirect(route('static-pages.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }

    /**
     * Mass update model
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massUpdate()
    {
        $this->authorize('update', Combo::class);

        try {
            $updatedItems = [];

            $combos = request('combos');
            $fields = request('fields');

            foreach ($combos as $combo) {
                /** @var Combo $updatedItem */
                $updatedItem = $this->comboRepository->update($combo, null, $fields);
                if (!$updatedItem) {
                    throw new \Exception(__('We cant update :name have id :id', ['id' => $combo, 'name' => __('combo')]));
                }
                array_push($updatedItems, $combo);
            }

            $message = __('The [:itemName] have been saved.', ['itemName' => implode(', ', $updatedItems)]);
            return !request()->ajax() ?
                redirect(route('static-pages.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . '-' . $e->getMessage();
            return !request()->ajax() ?
                back()->with(
                    'error',
                    $message
                ) :
                response()->json([
                    'status' => 400,
                    'message' => $message
                ]);
        }
    }
}
