import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {ActivatedRoute, ParamMap, Router} from '@angular/router';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {ScreenCategoryService} from '../../../services/category/screen.service';
import {ScreenCategory, ScreenRepo, Screen} from '../../../models/api/category-response/ScreenReponse';

declare var $: any;
import * as constants from '../../../constants';
import {DataGlobalService} from '../../../services/data.global.service';

declare var swal: any;

@Component({
    selector: 'screen-update-cmp',
    moduleId: module.id,
    templateUrl: 'screen-update.component.html'
})

export class UpdateScreenComponent implements OnInit {
    body: any = {
        name: '',
        display_name: '',
        description: '',
        screen_category_id: -1,
        url: '',
    };
    urls: string[] = constants.LIST_URL_ROUTER;
    idScreen: number;
    categorys: ScreenCategory[] = [];
    errorMsg = '';
    form: FormGroup

    constructor(private router: Router,
                private route: ActivatedRoute,
                public dataGlobalService: DataGlobalService,
                private service: ScreenCategoryService) {
    }



    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/cap-nhat-danh-muc-man-hinh')) {
            window.history.back();
        } else {
            this.getScreen();
            this.getCategorySc();

        }


    }

    onQuit(): void {
        window.history.back();

    }

    setCategorySelect(id: any): void {
        this.body.screen_category_id = parseInt(id);
    }

    selectItem(data: string): void {
        this.body.url = data;
        console.log(this.body.url);
    }

    getScreen(): void {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.idScreen = parseInt(queryParamMap.get('id') === null ? '' : queryParamMap.get('id'));
        this.body.description = queryParamMap.get('description') === null ? '' : queryParamMap.get('description');
        this.body.name = queryParamMap.get('name') === null ? '' : queryParamMap.get('name');
        this.body.url = queryParamMap.get('url') === null ? '' : queryParamMap.get('url');
        this.body.screen_category_id = parseInt(queryParamMap.get('screen_category_id') === null ? '' :
            queryParamMap.get('screen_category_id'));
    }

    getCategorySc(): void {
        this.service.getListScrCategory().then(res => {
            if (res.status = 'success') {
                this.categorys = (res.screen_categories as ScreenCategory[]);
            }
        }).catch(error => {
            this.dataGlobalService.actionFail(error.error);
        });
    }

    updateScreen(): void {
        if (this.body.screen_category_id !== -1) {
            if (this.body.name.trim() !== '' && this.body.description.trim() !== '') {
                if (this.body.url.trim() !== '') {
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
                        this.service.updateScreen(this.body, this.idScreen).then(res => {
                            if (res.status = 'success') {
                                this.errorMsg = '';
                                console.log(res.screen.id);
                                swal({type: 'success', title: 'Thành công', html: 'Thay đổi thông tin màn hình thành công'});
                            }
                        }).catch(error => {
                            this.dataGlobalService.actionFail(error.error);
                        });

                    });
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
            title: 'Nhập tên nhóm màn hình',
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

            this.service.createScrCategory(categoryName).then(
                res => {
                    if (res.status = 'sucess') {
                        this.categorys.push((res.screen_category as ScreenCategory));
                        console.log('__________________________TẠO DANH MỤC MÀN HÌNH THÀNH CÔNG__________________________');
                        swal({
                            type: 'success',
                            title: 'Tạo mới thành công',
                            html: 'Đã tạo thành công danh mục màn hình ' + categoryName
                        });
                    } else {
                        swal({
                            type: 'success',
                            title: 'Không Thành công',
                        });
                    }
                },
                error => {
                    this.dataGlobalService.actionFail(error.error);
                }
            );


        });
    }


}