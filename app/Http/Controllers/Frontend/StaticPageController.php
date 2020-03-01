<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class StaticPageController
 *
 * @package App\Http\Controllers\Frontend
 */
class StaticPageController extends Controller
{

    /**
     * @var ContactRepositoryInterface
     */
    private $contactRepository;

    /**
     * StaticPageController constructor.
     *
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(ContactRepositoryInterface $contactRepository)
    {

        $this->contactRepository = $contactRepository;
    }

    /**
     * @param string $pageSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $pageSlug)
    {
        return view('frontend.static_page.show', compact('globalSlug', 'pageSlug'));
    }

    /**
     * @param ContactRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendFeedback(ContactRequest $request)
    {

        try {
            $data = $request->all();
            $data[\App\Contact::STATUS] = \App\Contact::PENDING;
            /** @var \App\Contact $contact */
            $contact = $this->contactRepository->create($data, false);
            return back()
                ->with('success', __('Thank you for your feedback! :name', ['name' => $contact->getContactName()]));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

    }
}
