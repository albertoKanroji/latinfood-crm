<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>Shipping | Analist</b>
                </h4>
               
            </div>
            
        
        
            <div class="widget-content">
                <div class="row">
<div class="col-md-6 col-xl-4">
<div class="card mb-3 widget-content bg-night-fade">
<div class="widget-content-wrapper text-white">
<div class="widget-content-left">
<div class="widget-heading">Total Orders</div>
<div class="widget-subheading">Last year expenses</div>
</div>
<div class="widget-content-right">
<div class="widget-numbers text-white"><span>1896</span></div>
</div>
</div>
</div>
</div>
<div class="col-md-6 col-xl-4">
<div class="card mb-3 widget-content bg-arielle-smile">
<div class="widget-content-wrapper text-white">
<div class="widget-content-left">
<div class="widget-heading">Clients</div>
<div class="widget-subheading">Total Clients Profit</div>
</div>
<div class="widget-content-right">
<div class="widget-numbers text-white"><span>$ 568</span></div>
</div>
</div>
</div>
</div>
<div class="col-md-6 col-xl-4">
<div class="card mb-3 widget-content bg-happy-green">
<div class="widget-content-wrapper text-white">
<div class="widget-content-left">
<div class="widget-heading">Followers</div>
<div class="widget-subheading">People Interested</div>
</div>
<div class="widget-content-right">
<div class="widget-numbers text-white"><span>46%</span></div>
</div>
</div>
</div>
</div>
<div class="d-xl-none d-lg-block col-md-6 col-xl-4">
<div class="card mb-3 widget-content bg-premium-dark">
<div class="widget-content-wrapper text-white">
<div class="widget-content-left">
<div class="widget-heading">Products Sold</div>
<div class="widget-subheading">Revenue streams</div>
</div>
<div class="widget-content-right">
<div class="widget-numbers text-warning"><span>$14M</span></div>
</div>
</div>
</div>
</div>
</div>
                </div>
</div>
</div>
</div>

<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget">
            <div class="widget-heading">
                <h4 class="card-title text-center"><b>REPORTS OF SALES</b></h4>
            </div>

            <div class="widget-content">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6>Choose the user</h6>
                                <div class="form-group">
                                    <select wire:model="userId" class="form-control">
                                        <option value="0">All</option>
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <h6>Choose the type of report</h6>
                                <div class="form-group">
                                    <select wire:model="reportType" class="form-control">
                                        <option value="0">Sales Of The Day</option> 
                                        <option value="1">Sales By Date</option>        
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Date from</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateFrom" class="form-control flatpickr" placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12 mt-2">
                                <h6>Date to</h6>
                                <div class="form-group">
                                    <input type="text" wire:model="dateTo" class="form-control flatpickr" placeholder="Click para elegir">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button wire:click="$refresh" class="btn btn-dark btn-block">
                                    Consult
                                </button>

                                <a class="btn btn-dark btn-block {{count($data) <1 ? 'disabled' : '' }}" 
                                href="{{ url('report/pdf' . '/' . $userId . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}" target="_blank">Generate PDF</a>

                                <a  class="btn btn-dark btn-block {{count($data) <1 ? 'disabled' : '' }}" 
                                href="{{ url('report/excel' . '/' . $userId . '/' . $reportType . '/' . $dateFrom . '/' . $dateTo) }}" target="_blank">Export to Excel</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9">
                        <!--TABLAE-->
                        <div class="table-responsive">
                            <table class="table table-bordered table striped mt-1">
                                <thead class="text-white" style="background: #FF5100">
                                    <tr>
                                         <th class="table-th text-white text-center">FOLIO</th>
                                         <th class="table-th text-white text-center">TOTAL</th>
                                         <th class="table-th text-white text-center">ITEMS</th>
                                         <th class="table-th text-white text-center">STATUS</th>
                                         <th class="table-th text-white text-center">USER</th>
                                         <th class="table-th text-white text-center">DATE</th>
                                         <th class="table-th text-white text-center">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data) <1)
                                    <tr><td colspan="7"><h5>Sin Resultados</h5></td></tr>
                                    @endif
                                    @foreach($data as $d)
                                    <tr>
                                        <td class="text-center"><h6>{{$d->id}}</h6></td>                               
                                        <td class="text-center"><h6>${{number_format($d->total,2)}}</h6></td>
                                        <td class="text-center"><h6>{{$d->items}}</h6></td>                                   
                                        <td class="text-center"><h6>{{$d->status}}</h6></td>    
                                        <td class="text-center"><h6>{{$d->user}}</h6></td>   
                                        <td class="text-center">
                                            <h6>
                                                {{\Carbon\Carbon::parse($d->created_at)->format('d-m-Y')}}
                                            </h6>
                                        </td>    
                                        <td class="text-center" >
                                            <button wire:click.prevent="getDetails({{$d->id}})"
                                                class="btn btn-dark btn-sm">
                                                <i class="fas fa-list"></i>
                                            </button>
                                           
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>                   
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.reports.sales-detail')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        flatpickr(document.getElementsByClassName('flatpickr'),{
            enableTime: false,
            dateFormat: 'Y-m-d',
            locale: {
                firstDayofWeek: 1,
                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                    "Sunday",
                     "Monday",
                     "Tuesday",
                     "Wednesday",
                     "Thursday",
                     "Friday",
                     "Saturday",
                    ],
                },
                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                     "January",
                     "February",
                     "March",
                     "April",
                     "May",
                     "June",
                     "July",
                     "August",
                     "September",
                     "October",
                     "November",
                     "December",
                    ],
                },

            }

        })


        //eventos
        window.livewire.on('show-modal', Msg =>{
            $('#modalDetails').modal('show')
        })
    })

    function rePrint(saleId)
    {
        window.open("print://" + saleId,  '_self').close()
    }
</script>
