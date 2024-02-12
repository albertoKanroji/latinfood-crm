<br>
<div class="accordion" id="customersAccordion">

    <div class="card">
        <div class="card-header" id="customersHeading">
            <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#customersCollapse" aria-expanded="false" aria-controls="customersCollapse">
                  <h3>CLIENTES</h3>  
                </button>
            </h2>
        </div>

        <div id="customersCollapse" class="collapse show" aria-labelledby="customersHeading" data-parent="#customersAccordion">
            <div class="card-body">

                <div class="accordion" id="customerAccordion">

                    @foreach ($customers as $index => $customer)
                        <div class="card">
                            <div class="card-header" id="customerHeading{{ $index }}">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#customerCollapse{{ $index }}" aria-expanded="true" aria-controls="customerCollapse{{ $index }}">
                                     <h3>{{ $customer->DisplayName }}</h3>   
                                    </button>
                                </h2>
                            </div>

                            <div id="customerCollapse{{ $index }}" class="collapse @if ($index === 0) show @endif" aria-labelledby="customerHeading{{ $index }}" data-parent="#customerAccordion">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped mt-1">
                                        <thead class="text-white" style="background: #FF5100;">
                                            <tr>
                                                <th class="table-th text-white text-center">Display Name</th>
                                                <th class="table-th text-white text-center">Company Name</th>
                                                <th class="table-th text-white text-center">Balance With Jobs</th>
                                                <th class="table-th text-white text-center">Line1</th>
                                                <th class="table-th text-white text-center">City</th>
                                                <th class="table-th text-white text-center">Postal Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="table-th text-center">{{ $customer->DisplayName }}</td>
                                                <td class="table-th text-center">{{ $customer->CompanyName }}</td>
                                                <td class="table-th text-center">{{ $customer->BalanceWithJobs }}</td>
                                                @if (isset($customer->BillAddr))
                                                    <td class="table-th text-center">{{ $customer->BillAddr->Line1 }}</td>
                                                    <td class="table-th text-center">{{ $customer->BillAddr->City }}</td>
                                                    <td class="table-th text-center">{{ $customer->BillAddr->PostalCode }}</td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>

</div>

<br>
<hr>

<div class="accordion" id="invoicesAccordion">
    <div class="card">
        <div class="card-header" id="invoicesHeading">
            <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#invoicesCollapse" aria-expanded="true" aria-controls="invoicesCollapse">
                    <h3>FACTURAS</h3>
                </button>
            </h2>
        </div>
        <div id="invoicesCollapse" class="collapse show" aria-labelledby="invoicesHeading" data-parent="#invoicesAccordion">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #FF5100;">
                            <tr>
                                <th class="table-th text-white text-center">Número de Factura</th>
                                <th class="table-th text-white text-center">Fecha</th>
                                <th class="table-th text-white text-center">Total</th>
                                <!-- Agrega más encabezados de factura si es necesario -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="table-th text-center">{{ $invoice->DocNumber }}</td>
                                    <td class="table-th text-center">{{ $invoice->TxnDate }}</td>
                                    <td class="table-th text-center">{{ $invoice->TotalAmt }}</td>
                                    <!-- Agrega más columnas de factura si es necesario -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<br>
<hr>
<div class="accordion" id="paymentsAccordion">

    <div class="card">
        <div class="card-header" id="paymentsHeading">
            <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#paymentsCollapse" aria-expanded="true" aria-controls="paymentsCollapse">
                  <h3>PAGOS</h3>  
                </button>
            </h2>
        </div>

        <div id="paymentsCollapse" class="collapse show" aria-labelledby="paymentsHeading" data-parent="#paymentsAccordion">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #FF5100;">
                            <tr>
                                <th class="table-th text-white text-center">NOMBRE</th>
                                <th class="table-th text-white text-center">APELLIDO</th>
                                <th class="table-th text-white text-center">FACTURA</th>
                                <th class="table-th text-white text-center">PAGOS</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<br>
<hr>
<div class="accordion" id="suppliersAccordion">

    <div class="card">
        <div class="card-header" id="suppliersHeading">
            <h2 class="mb-0">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#suppliersCollapse" aria-expanded="true" aria-controls="suppliersCollapse">
                  <h3>PROVEEDORES</h3>  
                </button>
            </h2>
        </div>

        <div id="suppliersCollapse" class="collapse show" aria-labelledby="suppliersHeading" data-parent="#suppliersAccordion">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #FF5100;">
                            <tr>
                                <th class="table-th text-white text-center">NOMBRE</th>
                                <th class="table-th text-white text-center">APELLIDO</th>
                                <th class="table-th text-white text-center">FACTURA</th>
                                <th class="table-th text-white text-center">PAGOS</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
