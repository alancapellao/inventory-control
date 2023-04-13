<x-dashboard>

    @section('head')
    <link rel="stylesheet" href="{{ asset('css/statistics.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @endsection

    <div class="container-fluid position-relative d-block p-0">
        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center p-4">
                        <div class="text-center">
                            <p class="mb-0">Products Sales</p>
                            <br>
                            <h6 class="mb-0" id="sale">0</h6>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center p-4">
                        <div class="text-center">
                            <p class="mb-0">Products in Stock</p>
                            <br>
                            <h6 class="mb-0" id="stock">0</h6>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center p-4">
                        <div class="text-center">
                            <p class="mb-0">Products Active</p>
                            <br>
                            <h6 class="mb-0" id="active">0</h6>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center p-4">
                        <div class="text-center">
                            <p class="mb-0">Products Disabled</p>
                            <br>
                            <h6 class="mb-0" id="disabled">0</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid pt-4 px-4 chart">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-6">
                    <div class="bg-secondary text-center rounded p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-0">Stock by Categoria</h6>
                        </div>
                        <canvas id="pie-chart"></canvas>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-6">
                    <div class="bg-secondary text-center rounded p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h6 class="mb-0">Sales by Categoria</h6>
                        </div>
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('lib/chart.min.js') }}"></script>
        <script src="{{ asset('js/statistics.js') }}"></script>
        <script src="{{ asset('js/dashboard.js') }}"></script>
        @endsection

</x-dashboard>
