<div class="page-inner">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-capitalize">
                        <a href="{{route('dashboard')}}">
                            <span><i class="fas fa-arrow-left mr-3 text-capitalize"></i>Data Proses</span>
                        </a>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @foreach ($data_items as $items)
            <div class="card">
                <div class="card-header">
                    <h2 class="header-title">Kandidat {{count($items[0]['label'])}} Itemset</h2>
                </div>
                <div class="card-body">
                    <table class="table table-light">
                        <thead class="thead-light">
                            <tr>
                                <td>No.</td>
                                @foreach ($items[0]['label'] as $key => $label)
                                <td>Item {{$key+1}}</td>
                                @endforeach
                                <td>Jumlah</td>
                                <td>Support</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $keys => $item)
                            <tr>
                                <td>{{ $keys+1 }}</td>
                                @foreach ($item['label'] as $key => $label)
                                <td>{{$label}}</td>
                                @endforeach
                                <td>{{ $item['qty'] }}</td>
                                <td>{{ $item['suport'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

            <div class="card">
                <div class="card-header">
                    <h2 class="header-title">Rules</h2>
                </div>
                <div class="card-body">
                    <table class="table table-light">
                        <thead class="thead-light">
                            <tr>
                                <td>No.</td>
                                <td>Keterangan</td>
                                <td>Support</td>
                                <td>Confidence</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_item_rules as $keys => $rules)
                            <tr>
                                <td>{{ $rules['no'] }}</td>
                                <td>Jika pelanggan membeli <b>{{implode(',',$rules['antecedent'])}}</b> maka palanggan harus membeli <b>{{implode(',',$rules['consequent'])}}</b></td>
                                <td>{{ $rules['support'] }}</td>
                                <td>{{ $rules['confidence'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>