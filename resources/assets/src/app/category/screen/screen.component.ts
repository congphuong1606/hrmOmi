import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {Router} from '@angular/router';
import {ScreenCategoryService} from '../../services/category/screen.service';
import {Screen, ListScreenRepo} from '../../models/api/category-response/ScreenReponse';
import {PermisionService} from '../../services/permision.service';
import {DataGlobalService} from '../../services/data.global.service';

declare var $: any;
declare var swal: any

declare interface TableData {
    headerRow: string[];
    dataRows: Screen[];
}

@Component({
    selector: 'screen-cmp',
    moduleId: module.id,
    templateUrl: 'screen.component.html'
})

export class ScreenComponent implements OnInit {
    tableData: TableData = {
        headerRow: [],
        dataRows: []
    };
    form: FormGroup;
    isAdmin = false;
    allowUpdate = false;
    allowAdd = false;
    check = false;

    setupData(): void {
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.allowUpdate = this.checkPemision('/danh-muc/cap-nhat-danh-muc-man-hinh');
        this.allowAdd = this.checkPemision('/danh-muc/them-danh-muc-man-hinh');

    }

    addScreenCategory() {
        this.router.navigate(['../danh-muc/them-danh-muc-man-hinh']);
    }

    constructor(private router: Router,
                private service: ScreenCategoryService,
                public dataGlobalService: DataGlobalService,
                private permisionService: PermisionService) {
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/man-hinh')) {
            window.history.back();
        } else {
            this.setupData();
            this.getList();
        }

    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }

    onBack(): void {
        window.history.back();
    }

    getList(): void {
        this.tableData.headerRow = ['Nhóm', 'Màn hình', 'Chi tiết', 'Đường dẫn', 'Sửa', 'Xóa'];
        this.service.getListScreen().subscribe(
            res => {
                this.check = true;
                this.tableData.dataRows = (res as ListScreenRepo).screens;

            }, error => {
                this.dataGlobalService.actionFail(error.error);
            });
    }

    updateScreenCategory(data: any): void {
        this.router.navigate(['../danh-muc/cap-nhat-danh-muc-man-hinh'], {queryParams: data});
    }

    delete(rl: any): void {
        swal({
            title: 'Xác nhận xóa?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.service.delete(rl.id).then(res => {
                console.log(res);
                this.tableData.dataRows = this.tableData.dataRows.filter(h => h !== rl);
                swal(
                    'Đã xóa!',
                    'Bạn đã xóa nhóm quyền ' + rl.name,
                    'success'
                );
            }).catch(error => {
                this.dataGlobalService.actionFail(error.error);
            });

        });
    }

    creatPermision(row: any): void {
        let screenId = row.id;
        swal({
            title: 'chức năng của màn ' + row.name,
            html:
            '<lable style="float:left;margin-top:30px">Tên chức năng</lable>' +
            '<input placeholder="@example: Add || Edit ,..." id="swal-input1" class="swal2-input" >' +
            '<lable style="float:left">Tên hiển thị</lable>' +
            '<input placeholder="@example: Thêm nhân viên || Xóa nhân viên ,..." id="swal-input2" class="swal2-input">',
            showCancelButton: true,
            confirmButtonText: 'Tạo',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(function (resolve) {
                    setTimeout(function () {
                        resolve([
                            (<HTMLInputElement>document.getElementById('swal-input1')).value,
                            (<HTMLInputElement>document.getElementById('swal-input2')).value
                        ]);
                    }, 1000);
                });
            },
            allowOutsideClick: false
        }).then((function (result) {
            let permisonName: string = screenId + '.' + result[0];
            let body: any = {
                name: permisonName,
                display_name: result[1] + '',
                description: result[1] + '',
            };
            this.permisionService.createPermisson(body).then(res => {
                if (res.status = 'sucess') {
                    //   this.categorys.push((res.screen_category as ScreenCategory));
                    console.log('__________________________TẠO QUYỀN MÀN HÌNH THÀNH CÔNG__________________________');
                    swal({
                        type: 'success',
                        title: 'Thành công',
                        html: 'Đã tạo mới ' + result[1]
                    });
                } else {
                    swal({
                        type: 'success',
                        title: 'Không Thành công',
                    });
                }
            }).catch(error => {
                this.dataGlobalService.actionFail(error.error);
            });
        }).bind(this));
    }


    isLoadSuccess(): boolean {
        let flag = true;
        if (this.check && flag) {
            flag = false;
            $('[rel="tooltip"]').tooltip();
        }
        return this.check;
    }


    pinThead() {

        $('.sticky-header').floatThead({top: 100});

    }
}