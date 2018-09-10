<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lora">
<head>
    <table width="100%">
        <tr>
            <td class="td_left" width="30%">
                <img id="logo" src="http://ominext.com/img/common/logo.png"/>
            </td>
            <td class="td_center" width="35%">
                <p id="title"><strong style="font-size: 18px">Kết quả đào tạo</strong></p>
            </td>
            <td class="td_right" width="35%">
                <p style="font-size: 12px">Mã số: BM-HCKT-02-03<br>
                    Lần ban hành: 02<br>
                    Ngày ban hành: 13/10/2017</p>
            </td>
        </tr>
    </table>
</head>
<body>
<div id="info">
    <b>Ngày đào tạo: {{$training_date}}</b><br>
    <b>Tên khóa đào tạo: {{$course_name}}</b><br>
    <b>Giáo viên đào tạo:</b><br>
    @foreach($trainers as $trainer)
        <b style="margin-left: 20px">- {{$trainer}}</b><br>
    @endforeach
</div>
<table width="90%" align="center">
    <tr>
        <th width="8%">
            STT
        </th>
        <th width="28%">
            Họ và tên
        </th>
        <th width="28%">
            Chức vụ
        </th>
        <th width="28%">
            Bộ phận
        </th>
        <th width="15%">
            Kết quả (/10)
        </th>
    </tr>
    @foreach($training as $item)
        <tr>
            <td style="text-align: center;padding: 5px;font-size: 10px">{{$item['order']}}</td>
            <td style="padding: 5px;font-size: 10px">{{$item['name']}}</td>
            <td style="padding: 5px;font-size: 10px">{{$item['position']}}</td>
            <td style="padding: 5px;font-size: 10px">{{$item['department']}}</td>
            <td style="text-align: center;padding: 5px;font-size: 10px">{{$item['score']}}</td>
        </tr>
    @endforeach
</table>
<br>
<p id="approver"><b>Người duyệt</b></p>
</body>
</html>
<style>
    table {
        border-collapse: collapse;
    }

    table, td, th {
        border: 1px solid black;
        font-family: DejaVu Sans, serif;
    }

    th {
        font-size: 10px;
        text-align: center;
        padding: 5px;
    }

    b {
        font-family: DejaVu Sans, serif;
        font-size: 14px;
    }

    p {
        font-family: DejaVu Sans, serif;
    }

    .td_left {
        text-align: center;
    }

    .td_center {

    }

    .td_right {
        padding: 10px;
    }

    #title {
        text-align: center;
        font-size: 20px
    }

    #logo {
        width: 172px;
        height: 50px;
    }

    #info {
        padding: 10px;
        margin-top: 30px;
        margin-bottom: 30px;
        border: 1px solid black;
    }

    #approver {
        margin-left: 20px;
    }
</style>