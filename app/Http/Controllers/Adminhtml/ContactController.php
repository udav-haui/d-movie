<?php

namespace App\Http\Controllers\ADminhtml;

use App\Contact;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class ContactController
 *
 * @package App\Http\Controllers\ADminhtml
 */
class ContactController extends Controller
{
    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    /**
     * ContactController constructor.
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        ContactRepositoryInterface $contactRepository
    ) {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        \Log::info('great!');
        $this->authorize('view', Contact::class);

        $contacts = Contact::with('cinema')->get();

        if (request()->ajax()) {
            $authUser = auth()->user();
            $dt = datatables()->of($contacts);

            $dt->editColumn('status', function (Contact $contact) use ($authUser) {
                $htmlRaw = "<div class=\"dmovie-flex-container\">";
                $htmlRaw .= "<div class=\"pretty p-switch p-fill dmovie-switch\">";
                $htmlRaw .= "<input type=\"checkbox\"";
                $htmlRaw .= (int)$contact->getStatus() === Contact::CONTACTED ? "checked " : "";
                $htmlRaw .= "class=\"status-checkbox\"".
                    "value=\"{$contact->getId()}\"".
                    "data-id=\"{$contact->getId()}\"";
                if ($authUser->cant('update', Contact::class)) {
                    $htmlRaw .= "disabled";
                }
                $htmlRaw .= " dmovie-switch--dt/>";
                $htmlRaw .= "<div class=\"state p-success\">
                          <label class=\"status-text select-none\">
                                {$contact->getStatusLabel()}
                          </label>
                    </div>
                </div></div>";

                return $htmlRaw;
            });

            $dt->editColumn('contact_email', function (Contact $contact) {
                return $contact->getHtmlContactEmail();
            });

            if ($authUser->can('canEditDelete', Contact::class)) {
                $htmlRaw = "";
                $dt->addColumn('task', function (Contact $contact) use ($authUser, $htmlRaw) {
                    if ($authUser->can('update', Contact::class)) {
                        $htmlRaw .= "<a target=\"_blank\" ".
                            "href=\"https://mail.google.com/mail/?view=cm&fs=1&to={$contact->getContactEmail()}\"".
                            "type=\"button\" class=\"";

                        if ($authUser->cant('delete', Contact::class)) {
                            $htmlRaw .= "col-md-12 ";
                        } else {
                            $htmlRaw .= 'col-md-6 ';
                        }

                        $htmlRaw .= "col-md-12 col-xs-12 btn dmovie-btn dmovie-btn-success\"";
                        $htmlRaw .= "title=\"" . __('Contact') . "\">";
                        $htmlRaw .= "<i class=\"mdi mdi-send\"></i></a>";
                    }

                    if ($authUser->can('delete', Contact::class)) {
                        $cssClass = $authUser->can('update', Contact::class) ? "col-md-6" : "col-md-12";

                        $htmlRaw .= "<button id=\"deleteBtn\" type=\"button\"
                                            class=\"{$cssClass} col-xs-12 btn dmovie-btn btn-danger\"
                                            title=\" " . __('Delete') . " \"
                                            data-id=\"{$contact->getId()}\"
                                            url=\"" . route('contacts.destroy', ['contact' => $contact->getId()]) . "\">
                                        <i class=\"mdi mdi-delete-variant\"></i>
                                    </button>";
                    }

                    return $htmlRaw;
                });
            } else {
                $dt->addColumn('task', '');
            }


            return $dt->rawColumns(['status', 'task', 'contact_email'])->make();
        }

        return view('admin.contact.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Contact $contact
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete', Contact::class);

        try {
            /** @var Contact $contact */
            $contact = $this->contactRepository->delete(null, $contact);

            $msg = __('The <code>:itemName</code> has been deleted.', [
                'itemName' => __('contact') . ' ' . $contact->getId()
            ]);
            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'message' => $msg
                ]) :
                redirect(route('contacts.index'))->with('success', $msg);
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
        $this->authorize('delete', Contact::class);

        try {
            $contacts = request('contacts');

            $deletedItems = [];

            /** @var string $contact */
            foreach ($contacts as $contact) {
                /** @var Contact $deletedItem */
                $deletedItem = $this->contactRepository->delete($contact, null);

                array_push($deletedItems, $deletedItem->getId());
            }

            $message = __('A total of :number record(s) have been deleted.', ['number' => count($deletedItems)]);
            return !request()->ajax() ?
                redirect(route('contacts.index'))
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
        $this->authorize('update', Contact::class);

        try {
            $count = 0;

            $contacts = request('contacts');
            $fields = request('fields');

            foreach ($contacts as $contact) {
                /** @var Contact $updatedItem */
                $updatedItem = $this->contactRepository->update($contact, null, $fields);
                if (!$updatedItem) {
                    throw new \Exception(__('We cant update :name have id :id', ['id' => $contact, 'name' => __('contact')]));
                }
                $count++;
            }

            $message = __('A total of :number record(s) have been updated.', ['number' => $count]);
            return !request()->ajax() ?
                redirect(route('contacts.index'))
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
