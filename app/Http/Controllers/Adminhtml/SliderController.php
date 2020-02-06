<?php

namespace App\Http\Controllers\Adminhtml;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Repositories\Interfaces\SliderRepositoryInterface;
use App\Slider;
use Exception;

class SliderController extends Controller
{
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * SliderController constructor.
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        SliderRepositoryInterface $sliderRepository
    ) {
        $this->sliderRepository = $sliderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Slider::class);


        if (request()->ajax()) {
            $sliders = Slider::select(['id', 'title', 'order']);

            return datatables()->of($sliders)
                ->rawColumns(['checkbox'])
                ->make();
        }

        $sliders = $this->sliderRepository->allByOrder();
        return view(
            'admin.slider.index',
            compact('sliders')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Slider::class);

        return view('admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SliderRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(SliderRequest $request)
    {
        $this->authorize('create', Slider::class);

        $fields = $request->all();

        try {
            $this->sliderRepository->create($fields);

            return redirect(route('sliders.index'))
                ->with('success', __('Slide image was created successfully.'));
        } catch (\Exception $exception) {
            return back()->with(
                'error',
                __('Ooops, something wrong appended.') . ' : ' . $exception->getMessage()
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Slider $slider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Slider $slider)
    {
        $this->authorize('update', Slider::class);

        return view(
            'admin.slider.edit',
            compact('slider')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SliderRequest $request
     * @param Slider $slider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(SliderRequest $request, Slider $slider)
    {
        $this->authorize('update', Slider::class);

        $fields = $request->all();

        try {
            $this->sliderRepository->update(null, $slider, $fields);

            return redirect(route('sliders.index'))
                ->with('success', __('Slide image was updated successfully.'));
        } catch (\Exception $e) {
            return back()->with(
                'error',
                __('Ooops, something wrong appended.') . ' : ' . $e->getMessage()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Slider $slider
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Slider $slider)
    {
        $this->authorize('delete', Slider::class);

        try {
            $this->sliderRepository->delete(null, $slider);

            $message = __('Slide image was deleted successfully.');
            return !request()->ajax() ?
                redirect(route('sliders.index'))
                    ->with('success', $message) :
                response()->json([
                    'status' => 200,
                    'message' => $message
                ]);
        } catch (Exception $exception) {
            $message = __('Ooops, something wrong appended.') . '-' . $exception->getMessage();
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
     * Change image status
     *
     * @param Slider $slider
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function changeStatus(Slider $slider)
    {
        $this->authorize('update', Slider::class);

        $status = request('status');

        try {
            $this->sliderRepository->changeStatus($slider, $status);

            $message = __('Slide item was updated successfully.');
            return request()->ajax() ?
                response()->json([
                    'status' => 200,
                    'message' => $message,
                    'data' => [
                        'text' => (int)$status === Slider::ENABLE ? __('Enable') : __('Disable')
                    ]
                ]) :
                back()->with('success', $message);
        } catch (\Exception $exception) {
            $message = __('Ooops, something wrong appended.') . $exception;

            return request()->ajax() ?
                response()->json([
                    'status' => 400,
                    'message' => $message,
                    'data' => []
                ]) :
                back()->with('error', $message);
        }
    }


    public function ajaxIndex()
    {

    }
//    public function test(Slider $slider)
//    {
//        $slider->setId(15);
//        $slider->save();
//        dd($slider);
//    }
}
