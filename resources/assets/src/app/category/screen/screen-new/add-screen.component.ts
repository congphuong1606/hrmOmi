import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import * as constants from '../../../constants';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router} from '@angular/router';
import {ScreenCategoryService} from '../../../services/category/screen.service';
import {ScreenCategory, Url} from '../../../models/api/category-response/ScreenReponse';
import {DataGlobalService} from '../../../services/data.global.service';

declare var $: any;
declare var swal: any;

declare interface Body {
    name: string;
    display_name: string;
    description: string;
    screen_category_id: Number;
    url: string;
};

@Component({
    selector: 'add-screen-cmp',
    moduleId: module.id,
    templateUrl: 'add-screen.component.html'
})


export class AddScreenComponent implements OnInit {
    form: FormGroup;
    urls: string[] = constants.LIST_URL_ROUTER
    errorMsg = '';
    body: Body = {
        name: '',
        display_name: '',
        description: '',
        screen_category_id: -1,
        url: '',
    };
    public categorys: ScreenCategory[] = [];

    constructor(private router: Router,
                public dataGlobalService: DataGlobalService,
                private service: ScreenCategoryService) {

    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/them-danh-muc-man-hinh')) {
            window.history.back();
        } else {
            this.getCategorySc();
        }

    }


    getCategorySc(): void {
        this.service.getListScrCategory().then(res => {
            if (res.status = 'success') {
                this.categorys = (res.screen_categories as ScreenCategory[]);
                console.log('LENG: ' + this.categorys.length);
                if (this.categorys.length > 0) {
                    this.body.screen_category_id = this.categorys[0].id;
                }
            }
        }).catch(error => {
            swal({title: 'Xảy ra lỗi : ' + error,});
        });
    }

    selectItem(data: string): void {
        this.body.url = data;
        console.log(this.body.url);
    }

    deleteScreenCategory(scategory: any): void {

    }

    creatScreen(): void {
        if (this.body.screen_category_id !== -1) {
            if (this.body.name.trim() !== '' && this.body.description.trim() !== '') {
                if (this.body.url.trim() !== '') {
                    if (this.body.description.trim() !== '') {
                        this.body.display_name = this.body.name;
                        swal({
                            title: 'Thông tin màn hình',
                            html: 'Tên : ' + this.body.name + '<br>' + this.body.url,
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
                            this.service.createScreen(this.body).then(res => {
                                if (res.status = 'success') {
                                    this.errorMsg = '';
                                    console.log(res.screen.id);
                                    swal({type: 'success', title: 'Tạo mới thành công', html: 'Đã tạo thành công danh mục màn hình '});
                                } else if (res.status = 'fail') {
                                    swal({type: 'error', title: 'Lỗi', html: res.message});
                                }


                            }).catch(error => {
                                this.dataGlobalService.actionFail(error.error);
                            });

                        });
                    } else {
                        this.errorMsg = 'chi tiết mô tả trống';
                    }
                } else {
                    this.errorMsg = 'Chưa có đường dẫn cho màn hình';
                }


            } else {
                this.errorMsg = 'Thông tin dữ liệu không thể để trống';
            }

        } else {
            this.errorMsg = 'Chưa có nhóm màn hình';
        }

    }

    createScrCategory(): void {
        swal({
            title: 'Tên nhóm màn hình',
            input: 'text',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },

        }).then(categoryName => {
            if (categoryName) {
                this.service.createScrCategory(categoryName).then(res => {
                    if (res.status = 'sucess') {
                        this.categorys.push((res.screen_category as ScreenCategory));
                        console.log('__________________________TẠO DANH MỤC MÀN HÌNH THÀNH CÔNG__________________________');
                        swal({type: 'success', title: 'Tạo mới thành công', html: 'Đa táo thành công danh mục màn hình ' + categoryName});
                    } else {
                        swal({
                            type: 'success',
                            title: 'Không Thành công',
                            html: 'KHOOGN THÀNH CÔNG MÁ ƠI '
                        });
                    }
                }).catch(error => {
                    this.dataGlobalService.actionFail(error.error);
                });
            }
        });
    }

    onQuit(): void {
        window.history.back();

    }


    setCategorySelect(id: any): void {
        console.log('DÃ CHỌN : ' + id);
        this.body.screen_category_id = parseInt(id);
    }


}