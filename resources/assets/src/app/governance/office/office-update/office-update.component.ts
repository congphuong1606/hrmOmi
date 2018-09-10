import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {ActivatedRoute, ParamMap, Router} from '@angular/router';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {DataGlobalService} from '../../../services/data.global.service';
import {CategoryReponse} from '../../../models/api/response/CategoriesReponse';
import {CategoryService} from '../../../services/category/category.service';
import {Subscription} from 'rxjs/Subscription';

declare var swal: any;

declare interface Body {
    name: string;
    code: string;
    display_name: string;
    description: string;
}

@Component({
    selector: 'office-update-cmp',
    moduleId: module.id,
    templateUrl: 'office-update.component.html'
})

export class UpdateOfficeComponent implements OnInit, OnDestroy {

    office: CategoryReponse = new CategoryReponse();
    id: number;
    bodyParam: Body = {
        name: '',
        code: '',
        display_name: '',
        description: '',
    };
    errorMsg = '';
    form: FormGroup;
    subscription: Subscription;


    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');

    }

    constructor(private router: Router,
                private route: ActivatedRoute,
                private dataGlobalService: DataGlobalService,
                private service: CategoryService) {
    }


    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/them-chuc-danh')) {
            window.history.back();
        }
        this.getQueryParamRouter();

    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.id = parseInt(queryParamMap.get('id') === null ? '' : queryParamMap.get('id'));
        this.bodyParam.name = queryParamMap.get('name') === null ? '' : queryParamMap.get('name');
        this.bodyParam.code = queryParamMap.get('code') === null ? '' : queryParamMap.get('code');
        this.bodyParam.description = queryParamMap.get('description') === null ? '' : queryParamMap.get('description');
    }

    saveUpdate(): void {
        if (this.bodyParam.name.trim() !== '' && this.bodyParam.description.trim() !== '') {
            swal({
                title: 'Cập nhật chức danh mới ?',
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
            }).then(r => {
                this.bodyParam.display_name = this.bodyParam.name;
                this.subscription = this.service.update('positions', this.id, this.bodyParam).subscribe(
                    res => {
                        if (res.status = 'success') {
                            this.errorMsg = '';
                            setTimeout(function () {
                                swal({
                                    title: 'Thành công!',
                                    text: 'Bạn vừa cập nhật chức danh thành công !',
                                    type: 'success',
                                    confirmButtonText: 'Thoát'
                                }).then(isConfirm => {
                                    if (isConfirm) {
                                        window.history.back();
                                    }
                                });
                            }, 1000);
                        } else {
                            this.errorMsg = 'có lỗi bạn êy';
                        }
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    });

            });

        } else {
            this.errorMsg = 'Thông tin dữ liệu không thể để trống';
        }
    }

    onQuit(): void {
        window.history.back();

    }

}