import {Component, OnInit, Input, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router} from '@angular/router';

import {ActivatedRoute, ParamMap} from '@angular/router';

import {JobStatus} from '../../../models/api/category-response/JobStatus';
import {DataGlobalService} from '../../../services/data.global.service';
import {CategoryService} from '../../../services/category/category.service';
import {Subscription} from 'rxjs/Subscription';

declare var swal: any;

declare interface Body {
    name: string;
    display_name: string;
    description: string;
}

@Component({
    selector: 'jobstatus-update-cmp',
    moduleId: module.id,
    templateUrl: 'jobstatus-update.component.html'
})

export class UpdateJobStatusComponent implements OnInit, OnDestroy {
    jobStatus: JobStatus = new JobStatus();
    id: number;
    bodyParam: Body = {
        name: '',
        display_name: '',
        description: '',
    };
    errorMsg: string = '';
    subscription: Subscription;

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
                private route: ActivatedRoute,
                private dataGlobalService: DataGlobalService,
                private service: CategoryService) {
    }

    form: FormGroup;

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/cap-nhat-trang-thai-cong-viec')) {
            window.history.back();
        }
        this.getQueryParamRouter();


    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.id = parseInt(queryParamMap.get('id') === null ? '' : queryParamMap.get('id'));
        this.bodyParam.name = queryParamMap.get('name') === null ? '' : queryParamMap.get('name');
        this.bodyParam.description = queryParamMap.get('description') === null ? '' : queryParamMap.get('description');
    }

    saveUpdate(): void {
        if (this.bodyParam.name.trim() !== '' && this.bodyParam.description.trim() !== '') {
            swal({
                title: 'Cập nhật trạng thái công việc ?',
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
                this.subscription = this.service.update('job_status', this.id, this.bodyParam).subscribe(
                    res => {
                        if (res.status = 'success') {
                            this.errorMsg = '';
                            setTimeout(function () {
                                swal({
                                    title: 'Thành công!',
                                    text: 'Bạn vừa cập nhật Trạng thái công việc thành công !',
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
        this.ngOnDestroy();
        window.history.back();
    }
}