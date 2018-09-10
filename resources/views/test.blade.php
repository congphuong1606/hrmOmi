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
                <p id="title"><b>Kết quả đào tạo</b></p>
            </td>
            <td class="td_right" width="35%">
                <p>Mã số: BM-HCKT-02-03<br>
                    Lần ban hành: 02 <br>
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
<table width="80%" align="center">
    <tr>
        <th>
            STT
        </th>
        <th>
            Họ và tên
        </th>
        <th>
            Chức vụ
        </th>
        <th>
            Bộ phận
        </th>
        <th>
            Kết quả (/10)
        </th>
    </tr>
    @foreach($training as $item)
        <tr>
            <td>{{$item['order']}}</td>
            <td>{{$item['name']}}</td>
            <td>{{$item['position']}}</td>
            <td>{{$item['department']}}</td>
            <td>{{$item['score']}}</td>
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

    b {
        font-family: DejaVu Sans, serif;
    }

    p {
        font-family: DejaVu Sans, "ariblk", "monospace", "Times-Roman";
    }

    strong {
        text-align: center;
        font-size: 20px;
        color: black;
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