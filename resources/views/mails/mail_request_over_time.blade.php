<div id=":iq" class="a3s aXjCH m162e1af999aefbf3">
    <div>
        <table style="border-radius:2px;margin:20px 0 25px;min-width:400px;border:1px solid #eee" cellspacing="0"
               cellpadding="10">
            <tr style="background:#eee">
                <th colspan="6" style=" border-bottom: 1px solid #eee">Đăng ký làm ngoài giờ</th>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="border-bottom:1px solid #eee" colspan="3">Người đề xuất</td>
                <td style="border-bottom:1px solid #eee" colspan="3">{{$name}} ({{$email}})</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="border-bottom:1px solid #eee" colspan="3">Tên dự án</td>
                <td style="border-bottom:1px solid #eee" colspan="3">{{$project}}</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="border-bottom:1px solid #eee" colspan="3">Ngày đăng ký</td>
                <td style="border-bottom:1px solid #eee" colspan="3">{{$created_at}}</td>
            </tr>
            <tr style="background:#fbfbfb">
                <td style="border-bottom:1px solid #eee" colspan="3">Số lượng nhân viên làm ngoài giờ</td>
                <td style="border-bottom:1px solid #eee" colspan="3">{{$number_of_person}}</td>
            </tr>
            <tr style="background:#eee">
                <th colspan="6" style=" border-bottom: 1px solid #eee">Nội dung chi tiết</th>
            </tr>
            <tr>
                <th>STT</th>
                <th>Họ và tên</th>
                <th>Nội dung công việc</th>
                <th>Ngày làm việc</th>
                <th>Từ giờ</th>
                <th>Đến giờ</th>
            </tr>
            <tbody>
            @foreach($detail_contents as $item)
                <tr style="background:#fbfbfb">
                    <td style="border-bottom:1px solid #eee">{{$item['order']}}</td>
                    <td style="border-bottom:1px solid #eee">{{$item['name']}}</td>
                    <td style="border-bottom:1px solid #eee">{{$item['content']}}</td>
                    <td style="border-bottom:1px solid #eee">{{$item['date']}}</td>
                    <td style="border-bottom:1px solid #eee">{{$item['from']}}</td>
                    <td style="border-bottom:1px solid #eee">{{$item['to']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <p>Trân trọng,&nbsp;</p>
    <p>{{$name}}</p>
    <p>{{$department}}</p>
</div>
