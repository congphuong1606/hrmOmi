<div style="max-width: 600px">
    @if($approved==APPROVED)
        <p><strong>{{$approver}}</strong> đã duyệt đơn nghỉ phép của bạn.</p>
    @elseif($approved==REFUSED)
        <p><strong>{{$approver}}</strong> đã từ chối đơn nghỉ phép của bạn</p>
    @endif
    <p><strong>Message: </strong>{{$approved_reason}}</p>
    <table style="border-radius:2px;margin:15px 30px 25px;min-width:400px;border:1px solid #eee;"
           cellspacing="0"
           cellpadding="10" border="0">
        <tbody>
        <tr style="background:#fbfbfb">
            <td style="font-weight:bold;border-bottom:1px solid #eee;width:180px">Họ và tên / Fullname</td>
            <td style="border-bottom:1px solid #eee">{{$name}}</td>
        </tr>
        <tr style="background:#fbfbfb">
            <td style="font-weight:bold;border-bottom:1px solid #eee;width:180px">Email</td>
            <td style="border-bottom:1px solid #eee">{{$email}}</td>
        </tr>
        <tr style="background:#f9f9f9">
            <td style="font-weight:bold;border-bottom:1px solid #eee;width:180px">Loại lý do nghỉ / Type of leave</td>
            <td style="border-bottom:1px solid #eee">Nghỉ 1 ngày / Allday leave</td>
        </tr>
        <tr style="background:#fbfbfb">
            <td style="font-weight:bold;border-bottom:1px solid #eee;width:180px">Lý do vắng mặt / Detailed
                reason
            </td>
            <td style="border-bottom:1px solid #eee">{{$detailed_reason}}</td>
        </tr>
        <tr style="background:#fbfbfb">
            <td style="font-weight:bold;border-bottom:1px solid #eee;width:180px">Từ lúc / From</td>
            <td style="border-bottom:1px solid #eee">{{$start_datetime}}</td>
        </tr>
        <tr style="background:#fbfbfb">
            <td style="font-weight:bold;border-bottom:1px solid #eee;width:180px">Đến lúc / To</td>
            <td style="border-bottom:1px solid #eee">{{$end_datetime}}</td>
        </tr>
        <tr style="background:#fbfbfb">
            <td style="font-weight:bold;border-bottom:1px solid #eee;width:180px">Trạng thái / Status
            </td>
            @if($approved==APPROVED)
                <td style="border-bottom:1px solid #eee">Đã được duyệt</td>
            @elseif($approved==REFUSED)
                <td style="border-bottom:1px solid #eee">Bị từ chối</td>
            @endif
        </tr>
        </tbody>
    </table>&nbsp;<p></p>
</div>