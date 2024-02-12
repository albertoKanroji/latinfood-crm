<div>  

    <div class="row layout-top-spacing mt-5">

        <div class="col-sm-12 col-md-6">
            <div class="widget widget-chart-one">
                <h4 class="p-3 text-center text-theme-1 font-bold" style="  color: #FF5100;">Top Products</h4>
                <div id="chartTop5">
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="widget widget-chart-one">
                <h4 class="p-3 text-center text-theme-1 font-bold" style="  color: #FF5100;">Weekly Sales</h4>
                <div id="areaChart">
                </div>
            </div>


        </div>
    </div>
    
    <div class="row pt-5">
        <div class="col-sm-12 ">
            <div class="widget widget-chart-one">
                <h4 class="p-3 text-center text-theme-1 font-bold" style="  color: #FF5100;">Sales for Year:  {{$year}}</h4>
                <div id="chartMonth">
                </div>
            </div>
        </div>
    </div>

    @include('livewire.dash.script')

</div>