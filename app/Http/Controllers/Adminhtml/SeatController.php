<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatRequest;
use App\Repositories\SeatRepository;
use App\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

/**
 * Class SeatController
 *
 * @package App\Http\Controllers\Adminhtml
 */
class SeatController extends Controller
{
    /**
     * @var SeatRepository
     */
    private $seatRepository;

    /**
     * SeatController constructor.
     * @param SeatRepository $seatRepository
     */
    public function __construct(
        SeatRepository $seatRepository
    ) {
        $this->seatRepository = $seatRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param SeatRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(SeatRequest $request)
    {
        $this->authorize('create', Seat::class);

        try {
            $rs = $this->seatRepository->create($request->all());

            if ($rs instanceof Seat) {
                $message = __('The seat :name have created.', ['name' => $rs->getRow().$rs->getNumber()]);
            } else {
                $message = __('The seats :name have created.', ['name' => implode(',', $rs['success'])]);
                if (count($rs['error']) > 0) {
                    if (count($rs['success']) > 0) {
                        return redirect()
                            ->route('shows.getSeats', ['show' => $request->show_id])
                            ->with('success', $message)
                            ->with('error', __('The seats [:seats] have existed.', [
                                'seats' => implode(', ', $rs['error'])
                            ]));
                    }
                    return redirect()
                        ->route('shows.getSeats', ['show' => $request->show_id])
                        ->with('error', __('The seats [:seats] have existed.', ['seats' => implode(', ', $rs['error'])]));
                }
            }

            return redirect()
                ->route('shows.getSeats', ['show' => $request->show_id])
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seat  $seat
     * @return \Illuminate\Http\Response
     */
    public function show(Seat $seat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Seat $seat
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Seat $seat)
    {
        $this->authorize('update', Seat::class);

        return view('admin.seat.edit', compact('seat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SeatRequest $request
     * @param \App\Seat $seat
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(SeatRequest $request, Seat $seat)
    {
        $this->authorize('update', Seat::class);

        try {
            /** @var Seat $seat */
            $seat = $this->seatRepository->update(null, $seat, $request->all());

            return redirect(route('shows.getSeats', ['show' => $seat->getShow()->getId()]))
                ->with(
                    'success',
                    __(
                        'The [<code>:itemName</code>] has been saved.',
                        ['itemName' => $seat->getRow().$seat->getNumber()]
                    )
                );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Seat $seat
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Seat $seat)
    {
        $this->authorize('delete', Seat::class);

        try {
            /** @var Seat $seat */
            $seat = $this->seatRepository->delete(null, $seat);

            $message = __('The seat :name have deleted.', ['name' => $seat->getRow() . $seat->getNumber()]);
            return !request()->ajax() ?
                redirect(route('shows.getSeats', ['show' => $seat->getShow()]))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (\Exception $e) {
            $message = __('Ooops, something wrong appended.') . ' - ' . $e->getMessage();
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
        $this->authorize('delete', Seat::class);

        try {
            $seats = request('seats');

            $deletedItems = [];
            $deletedItem = new Seat;

            /** @var string $seat */
            foreach ($seats as $seat) {
                /** @var Seat $deletedItem */
                $deletedItem = $this->seatRepository->delete($seat, null);

                array_push($deletedItems, $deletedItem->getRow() . $deletedItem->getNumber());
            }

            $message = __('The seats :seats have deleted.', ['seats' => implode(', ', $deletedItems)]);
            return !request()->ajax() ?
                redirect(route('shows.getSeats', ['show' => $deletedItem->getShow()]))
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
     * Multi update
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function massUpdate()
    {
        $this->authorize('update', Seat::class);

        try {
            $updatedItems = [];
            $updatedItem = null;

            $seats = request('seats');
            $fields = request('fields');

            foreach ($seats as $seat) {
                /** @var Seat $updatedItem */
                $updatedItem = $this->seatRepository->update($seat, null, $fields);
                if (!$updatedItem) {
                    throw new \Exception(__('We cant update seat have id :id', ['id' => $seat]));
                }
                array_push($updatedItems, $updatedItem->getRow().$updatedItem->getNumber());
            }

            $message = __('The seats [:seats] have updated.', ['seats' => implode(', ', $updatedItems)]);
            return !request()->ajax() ?
                redirect(route('shows.getSeats', ['show' => $updatedItem->getShow()]))
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
