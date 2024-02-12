<!--
This software, includin any associated code, documentation and related material, is licensed solely by Oyarcegroup.com by accessing or using this software, you agree to comply with the following terms and conditions. 
 This coding is licensed under the international standards IEEE and STHT, 833-3901-0093, the share, reproduction, sale or distribution without the consent of OyarceGroup.com is totally prohibited and may be criminally punished.

Oyarcegroup.com retains full ownership of this software, including all intellectual property rights associated with it. This license does not grant you any ownership rights or licenses except those explicitly provided herein.-->
<div wire:ignore.self id="modal-details" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark">
				<h5 class="modal-title text-white">
					<b>Sales Detail</b>
				</h5>
				<button class="close" data-dismiss="modal" type="button" aria-label="Close">
					<span class="text-white">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<div class="table-responsive">
                        <table class="table table-bordered table-striped mt-1">
                            <thead class="text-white" style="background: #3B3F5C">
                                <tr>
                                    <th class="table-th text-center text-white">PRODUCT</th>
                                    <th class="table-th text-center text-white">AMOUNT</th>
                                    <th class="table-th text-center text-white">PRICE</th>
                                    <th class="table-th text-center text-white">IMPORTED</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                @foreach($details as $d)
                                <tr>
                                    <td class="text-center"><h6>{{$d->product}}</h6></td>
                                    <td class="text-center"><h6>{{$d->quantity}}</h6></td>
                                    <td class="text-center"><h6>${{number_format($d->price,2)}}</h6></td>
                                    <td class="text-center"><h6>${{number_format($d->quantity * $d->price,2)}}</h6></td>
                                    
                                    
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            	<td class="text-right"><h6 class="text-info">TOTALS:</h6></td>
                            	<td class="text-center">
                            		@if($details)
                            		<h6 class="text-info">{{$details->sum('quantity')}}</h6>
                            		@endif
                            	</td>
                            	@if($details)
                            	@php $mytotal =0; @endphp
                            	@foreach($details as $d)
                            	@php
                            	$mytotal += $d->quantity * $d->price;
                            	@endphp
                            	@endforeach
                            	<td></td>
                            	<td class="text-center"><h6 class="text-info">${{number_format($mytotal,2)}}</h6></td>
                            	@endif
                            </tfoot>
                        </table>
                    </div>
			</div>
		</div>
	</div>
</div>