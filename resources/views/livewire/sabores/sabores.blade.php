<div>
    <div class="row sales layout-top-spacing">
        <div class="col-sm-12">
            <div class="widget widget-chart-one">
                <div class="widget-heading">
                    <h4 class="card-title">
                        <b>{{ $componentName}} | List</b>
                    </h4>
                    <ul class="tabs tab-pills">
                        <li>
                            <a href="javascript:void(0)" class="btn btn-warning mb-2 mr-2 btn-rounded"
                                data-toggle="modal" data-target="#theModal"> Add New Flavor<svg viewBox="0 0 24 24"
                                    width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg></a>
                        </li>
                    </ul>
                </div>
                @include('common.searchbox')
                <div class="widget-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table striped mt-1">
                            <thead class="text-white" style="background: #FF5100">
                                <tr>
                                    <th class="table-th text-white text-center">Name</th>
                                    <th class="table-th text-white text-center">Description</th>
                                    <th class="table-th text-white text-center">Stock</th>
                                    <th class="table-th text-white text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $coin)
                                <tr>
                                    <td class="text-center">
                                        <h6>{{$coin->nombre}}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ isset($coin->descripcion) ? $coin->descripcion : 'Not Description' }}
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ isset($coin->stock) ? $coin->stock : 'Sin Stock' }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click="Edit({{$coin->id}})"
                                            class="btn btn-warning mb-2 mr-2 btn-rounded" title="Edit">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                stroke-width="2" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round" class="css-i6dzq1">
                                                <path d="M12 20h9"></path>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z">
                                                </path>
                                            </svg>
                                        </a>
                                        
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
    @include('livewire.sabores.form')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('show-modal', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('sabor-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('sabor-updated', msg => {
            $('#theModal').modal('hide')
        });
    });
    </script>
</div>