@extends('master')

@section('content')
    @include('components.header')
    @include('components.home.content')
    @include('components.footer')
@endsection

@section('script')
    <script>
        function history_keyword(id_keyword)
        {
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#token').attr('content')
                },
                url: "{{ asset('/history-keyword')}}",
                method: 'post',
                data: {
                    id_keyword: id_keyword,
                },
                success: function(data){
                    var keywords = [];

                    $.each(data, function(i, item) {
                        var keyword;
                        var rank;
                        if(item.rank === 1)
                        {
                            keyword = '<span class="text-success">'+item.key_word+'</span>';
                            rank = '<span class="text-success">'+item.rank+'</span>';
                        }
                        else if(item.rank === 0)
                        {
                            keyword = '<span class="text-danger">'+item.key_word+'</span>';
                            rank = '<span class="text-danger">'+item.rank+'</span>';
                        }

                        keywords.push({
                            'STT' : i+1,
                            'Domain' : item.domain,
                            'Key word' : keyword,
                            'Thứ hạng' : rank,
                            'Ngày quét': item.date_check,

                        });
                    });


                    $('#data-table-basic-history').DataTable({
                        data: keywords,
                        columns: [
                            { data: 'STT' },
                            { data: 'Domain' },
                            { data: 'Key word' },
                            { data: 'Thứ hạng' },
                            { data: 'Ngày quét' }
                        ],
                        "bDestroy": true
                    });


                },
                error: function(error)
                {
                   console.log(error);
                }
            });
        }
    </script>

    <script>
        $('#filter').click(function () {
            var domain = $('#domain').val();
            var rank = $('#rank').val();

            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#token').attr('content')
                },
                url: "{{ asset('/filter-keyword')}}",
                method: 'post',
                data: {
                    id_domain: domain,
                    rank: rank
                },
                success: function(data){
                    $('#list_keyword').empty();

                    if(data.count == 0 )
                    {
                        $('#list_keyword').html('<p class="text-info"> không có kết quả nào </p>');

                        $('#count').html(data.count+' kết quả');
                        return;
                    }

                    $('#count').html(data.count+' kết quả');


                    var keywords = [];
                    $.each(data.keywords, function(i, item) {
                        var keyword;
                        var rank;
                        if(item.rank === 1)
                        {
                            keyword = '<a target="_blank" href="https://www.google.com.vn/search?hl=en&q='+item.key_word+'"><span class="text-success">'+item.key_word+'</span></a>';
                            rank = '<span class="text-success">'+item.rank+'</span>';
                        }
                        else if(item.rank === 0)
                        {
                            keyword = '<a target="_blank" href="https://www.google.com.vn/search?hl=en&q='+item.key_word+'"><span class="text-danger">'+item.key_word+'</span></a>';
                            rank = '<span class="text-danger">'+item.rank+'</span>';
                        }
                        else
                        {
                            keyword = '<a target="_blank" href="https://www.google.com.vn/search?hl=en&q='+item.key_word+'">'+item.key_word+'</a>';
                            rank = item.rank;
                        }

                        var str = '<button onclick="history_keyword('+item.id+')" data-toggle="modal" data-target="#myModalone2" class="btn btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-menu"></i>Xem lịch sử</button>\n' +
                            '                                                            <div class="modal fade" id="myModalone2" role="dialog">\n' +
                            '                                                                <div class="modal-dialog modals-default">\n' +
                            '                                                                    <div class="modal-content" style="padding: 0px 0px;">\n' +
                            '                                                                        <div class="modal-header">\n' +
                            '                                                                            <button type="button" class="close" data-dismiss="modal" style="z-index: 999">&times;</button>\n' +
                            '                                                                        </div>\n' +
                            '                                                                        <div class="modal-body">\n' +
                            '                                                                            <div class="data-table-list">\n' +
                            '                                                                                <div class="table-responsive">\n' +
                            '                                                                                    <table id="data-table-basic-history" class="table table-striped">\n' +
                            '                                                                                        <thead>\n' +
                            '                                                                                        <tr>\n' +
                            '                                                                                            <th>STT</th>\n' +
                            '                                                                                            <th>Domain</th>\n' +
                            '                                                                                            <th>Key word</th>\n' +
                            '                                                                                            <th>Thứ hạng</th>\n' +
                            '                                                                                            <th>Ngày quét</th>\n' +
                            '                                                                                        </tr>\n' +
                            '                                                                                        </thead>\n' +
                            '                                                                                        <tbody id="list_history">\n' +
                            '\n' +
                            '                                                                                        </tbody>\n' +
                            '                                                                                    </table>\n' +
                            '                                                                                </div>\n' +
                            '                                                                            </div>\n' +
                            '                                                                        </div>\n' +
                            '                                                                    </div>\n' +
                            '                                                                </div>\n' +
                            '                                                            </div>\n' +
                            '\n' +
                            '                                                            <a href="/delete-keyword/'+item.id+'">\n' +
                            '                                                                <button class="btn  btn-small btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-close"></i> Xóa</button>\n' +
                            '                                                            </a>';


                        keywords.push({
                            'STT' : i+1,
                            'Domain' : item.domain,
                            'Key word' : keyword,
                            'Thứ hạng' : rank,
                            'Lần quét cuối cùng': item.updated_at,
                            '' : str
                        });
                    });
                    console.log(keywords[0]);

                    $('#data-table-basic1').DataTable( {
                        data: keywords,
                        columns: [
                            { data: 'STT' },
                            { data: 'Domain' },
                            { data: 'Key word' },
                            { data: 'Thứ hạng' },
                            { data: 'Lần quét cuối cùng' },
                            { data: '' }
                        ],
                        "bDestroy": true
                    } );
                },
                error: function(error)
                {
                    console.log(error);
                }
            });
        });
    </script>


    <script>
        $('#refresh').click(function () {
            jQuery.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('#token').attr('content')
                },
                url: "{{ asset('/refresh')}}",
                method: 'post',
                success: function(data){
                    $('#list_keyword').empty();

                    if(data.count == 0 )
                    {
                        $('#list_keyword').html('<p class="text-info"> không có kết quả nào </p>');

                        $('#count').html(data.count+' kết quả');
                        return;
                    }

                    $('#count').html(data.count+' kết quả');

                    var keywords = [];
                    $.each(data.keywords, function(i, item) {
                        var keyword;
                        var rank;
                        if(item.rank === 1)
                        {
                            keyword = '<a target="_blank" href="https://www.google.com.vn/search?hl=en&q='+item.key_word+'"><span class="text-success">'+item.key_word+'</span></a>';
                            rank = '<span class="text-success">'+item.rank+'</span>';
                        }
                        else if(item.rank === 0)
                        {
                            keyword = '<a target="_blank" href="https://www.google.com.vn/search?hl=en&q='+item.key_word+'"><span class="text-danger">'+item.key_word+'</span></a>';
                            rank = '<span class="text-danger">'+item.rank+'</span>';
                        }
                        else
                        {
                            keyword = '<a target="_blank" href="https://www.google.com.vn/search?hl=en&q='+item.key_word+'">'+item.key_word+'</a>';
                            rank = item.rank;
                        }

                        var str = '<button onclick="history_keyword('+item.id+')" data-toggle="modal" data-target="#myModalone2" class="btn btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-menu"></i>Xem lịch sử</button>\n' +
                            '                                                            <div class="modal fade" id="myModalone2" role="dialog">\n' +
                            '                                                                <div class="modal-dialog modals-default">\n' +
                            '                                                                    <div class="modal-content" style="padding: 0px 0px;">\n' +
                            '                                                                        <div class="modal-header">\n' +
                            '                                                                            <button type="button" class="close" data-dismiss="modal" style="z-index: 999">&times;</button>\n' +
                            '                                                                        </div>\n' +
                            '                                                                        <div class="modal-body">\n' +
                            '                                                                            <div class="data-table-list">\n' +
                            '                                                                                <div class="table-responsive">\n' +
                            '                                                                                    <table id="data-table-basic-history" class="table table-striped">\n' +
                            '                                                                                        <thead>\n' +
                            '                                                                                        <tr>\n' +
                            '                                                                                            <th>STT</th>\n' +
                            '                                                                                            <th>Domain</th>\n' +
                            '                                                                                            <th>Key word</th>\n' +
                            '                                                                                            <th>Thứ hạng</th>\n' +
                            '                                                                                            <th>Ngày quét</th>\n' +
                            '                                                                                        </tr>\n' +
                            '                                                                                        </thead>\n' +
                            '                                                                                        <tbody id="list_history">\n' +
                            '\n' +
                            '                                                                                        </tbody>\n' +
                            '                                                                                    </table>\n' +
                            '                                                                                </div>\n' +
                            '                                                                            </div>\n' +
                            '                                                                        </div>\n' +
                            '                                                                    </div>\n' +
                            '                                                                </div>\n' +
                            '                                                            </div>\n' +
                            '\n' +
                            '                                                            <a href="/delete-keyword/'+item.id+'">\n' +
                            '                                                                <button class="btn  btn-small btn-default btn-icon-notika waves-effect"><i class="notika-icon notika-close"></i> Xóa</button>\n' +
                            '                                                            </a>';


                        keywords.push({
                            'STT' : i+1,
                            'Domain' : item.domain,
                            'Key word' : keyword,
                            'Thứ hạng' : rank,
                            'Lần quét cuối cùng': item.updated_at,
                            '' : str
                        });
                    });
                    console.log(keywords[0]);

                    $('#data-table-basic1').DataTable( {
                        data: keywords,
                        columns: [
                            { data: 'STT' },
                            { data: 'Domain' },
                            { data: 'Key word' },
                            { data: 'Thứ hạng' },
                            { data: 'Lần quét cuối cùng' },
                            { data: '' }
                        ],
                        "bDestroy": true
                    } );
                },
                error: function(error)
                {
                    console.log(error);
                }
            });
        });
    </script>
@endsection