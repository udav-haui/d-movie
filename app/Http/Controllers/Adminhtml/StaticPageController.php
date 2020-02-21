<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Requests\StaticPageRequest;
use App\Repositories\Interfaces\StaticPageRepositoryInterface;
use App\StaticPage;
use Illuminate\Http\Request;

/**
 * Class StaticPageController
 *
 * @package App\Http\Controllers
 */
class StaticPageController extends \App\Http\Controllers\Controller
{
    /**
     * @var StaticPageRepositoryInterface
     */
    private $pageRepository;

    /**
     * StaticPageController constructor.
     * @param StaticPageRepositoryInterface $pageRepository
     */
    public function __construct(
        StaticPageRepositoryInterface $pageRepository
    ) {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', StaticPage::class);

        if (request()->ajax()) {
            $pages = $this->pageRepository->all();
            $authUser = auth()->user();

            $dt = datatables()->of($pages);

            $dt->editColumn(StaticPage::LANGUAGE, function (StaticPage $page) {
                return $page->getLanguageLabel();
            });


            $dt->editColumn(StaticPage::STATUS, function (StaticPage $page) use ($authUser) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$page->getStatus() === StaticPage::ENABLE ? "checked " : "";
                $htmlRaw .= "class=\"status-checkbox\"".
                    "value=\"{$page->getId()}\"".
                    "data-id=\"{$page->getId()}\"";
                if ($authUser->cant('update', StaticPage::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= " dmovie-switch-dt/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$page->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });


            if ($authUser->can('canEditDelete', StaticPage::class)) {
                $htmlRaw = "";
                $dt->addColumn('task', function (StaticPage $page) use ($authUser, $htmlRaw) {
                    if ($authUser->can('update', StaticPage::class)) {
                        $htmlRaw .= "<a href=\"" . route('static-pages.edit', ['static_page' => $page->getId()]) . "\"
                                   type=\"button\" class=\"";
                        if ($authUser->cant('delete', StaticPage::class)) {
                            $htmlRaw .= "col-md-12 ";
                        } else {
                            $htmlRaw .= 'col-md-6 ';
                        }

                        $htmlRaw .= "col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                        $htmlRaw .= "title=\"" . __('Detail') . "\">";
                        $htmlRaw .= "<i class=\"fa fa-pencil-square-o\"></i></a>";
                    }

                    if ($authUser->can('delete', StaticPage::class)) {
                        $cssClass = $authUser->can('update', StaticPage::class) ? "col-md-6" : "col-md-12";

                        $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                            title=\" " . __('Delete') . " \"
                            data-id=\"{$page->getId()}\"
                            url=\"" . route('static-pages.destroy', ['static_page' => $page->getId()]) . "\">
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

        return view('admin.static_page.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', StaticPage::class);

        return view('admin.static_page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StaticPageRequest $request)
    {
        $this->authorize('create', StaticPage::class);

        try {
            /** @var StaticPage $page */
            $page = $this->pageRepository->create($request->all());

            return redirect(route('static-pages.index'))
                ->with('success', __(
                    'The [<code>:itemName</code>] has been saved.',
                    ['itemName' => $page->getName()]
                ));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StaticPage  $staticPage
     * @return \Illuminate\Http\Response
     */
    public function show(StaticPage $staticPage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param StaticPage $static_page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(StaticPage $static_page)
    {
        $this->authorize('update', StaticPage::class);

        return view('admin.static_page.edit', compact('static_page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StaticPageRequest $request
     * @param \App\StaticPage $static_page
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(StaticPageRequest $request, StaticPage $static_page)
    {
        $this->authorize('update', StaticPage::class);

        try {
            /** @var StaticPage $page */
            $page = $this->pageRepository->update(null, $static_page, $request->all());

            return redirect(route('static-pages.index'))
                ->with('success', __(
                    'The [<code>:itemName</code>] has been saved.',
                    ['itemName' => $page->getName()]
                ));


        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaticPage  $staticPage
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(StaticPage $staticPage)
    {
        $this->authorize('delete', StaticPage::class);

        try {
            /** @var StaticPage $page */
            $page = $this->pageRepository->delete(null, $staticPage);

            $message = __('The <code>:itemName</code> has been deleted.', ['itemName' => $page->getName()]);

            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]) :
                back()->with('success', $message);
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
        $this->authorize('delete', StaticPage::class);

        try {
            $pages = request('pages');

            $deletedItems = [];

            /** @var string $page */
            foreach ($pages as $page) {
                /** @var StaticPage $deletedItem */
                $deletedItem = $this->pageRepository->delete($page, null);

                array_push($deletedItems, '<code>' . $deletedItem->getName() . '</code>');
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
        $this->authorize('update', StaticPage::class);

        try {
            $updatedItems = [];

            $pages = request('pages');
            $fields = request('fields');

            foreach ($pages as $page) {
                /** @var StaticPage $updatedItem */
                $updatedItem = $this->pageRepository->update($page, null, $fields);
                if (!$updatedItem) {
                    throw new \Exception(__('We cant update :name have id :id', ['id' => $page, 'name' => __('page')]));
                }
                array_push($updatedItems, '<code>' . $updatedItem->getName() . '</code>');
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
