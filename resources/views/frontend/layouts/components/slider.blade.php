<!-- BEGIN SLIDER -->
<div class="page-slider margin-bottom-35">

    <div id="myCarousel" class="carousel slide" data-interval="3000" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            @foreach($activeSlidersGlobal as $key => $slider)
                <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="@if ($key === 0) active @endif"></li>
            @endforeach
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <?php /** @var \App\Slider $slider */  ?>
            @foreach($activeSlidersGlobal as $key => $slider)
                <div class="item @if ($key === 0) active @endif">
                    <a href="{{ $slider->getHref() }}">
                        <img src="{{ $slider->getImagePath() }}" alt="{{ $slider->getTitle() }}" style="width:100%;">
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <!-- LayerSlider start -->

    <!-- LayerSlider end -->
</div>
<!-- END SLIDER -->
