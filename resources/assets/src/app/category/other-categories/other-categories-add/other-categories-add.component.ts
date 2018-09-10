import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router, ActivatedRoute, ParamMap} from '@angular/router';
import {DataGlobalService} from '../../../services/data.global.service';
import {CategoryService} from '../../../services/category/category.service';
import {Subscription} from 'rxjs/Subscription';
import {CategoryOtherService} from '../../../services/category/category-other.service';

declare var swal: any;
declare var $: any;

declare interface Body {
    name: string;
    display_name: string;
    description: string;
}

@Component({
    selector: 'other-categories-add-cmp',
    moduleId: module.id,
    templateUrl: 'other-categories-add.component.html'
})

export class AddOtherCategoryComponent implements OnInit, OnDestroy {
     errorMsg = '';
     subUrl = '';
     typeCategory = {
        category_type: '',
        category_name: ''
    };
     BASE_URL = '/danh-muc/them-danh-muc-khac';
     numDate = Date.now();

     form: FormGroup;
     bodyParam: Body = {
        name: '',
        display_name: '',
        description: '',
    };
     subscription: Subscription;
     isTypeOtherCategoryTable = false;
     sub: Subscription;

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.sub !== undefined ? this.sub.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
                private service: CategoryService,
                public categoryOtherService: CategoryOtherService,
                private route: ActivatedRoute,
                public dataGlobalService: DataGlobalService) {
    }

    ngOnInit() {
        this.getQueryParamRouter();
        if (!this.dataGlobalService.checkPemisson(this.BASE_URL)) {
            window.history.back();
        } else {

        }
    }

    initTimeInput(idElement: string) {
        $('#' + idElement).datetimepicker({
            format: 'HH:mm',
            useCurrent: false,
            ignoreReadonly: true,
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        }).attr('readonly', 'readonly');
    }

    getQueryParamRouter() {
        this.route.paramMap.subscribe((params: ParamMap) => this.subUrl = params.get('category-name'));
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.typeCategory.category_type = queryParamMap.get('category_type') === null ? '' : queryParamMap.get('category_type');
        this.typeCategory.category_name = queryParamMap.get('category_name') === null ? '' : queryParamMap.get('category_name');
        if (this.typeCategory.category_type !== 'specialized_skills'
            && this.typeCategory.category_type !== 'job_positions'
            && this.typeCategory.category_type !== 'working_status') {
            this.isTypeOtherCategoryTable = true;
        } else {
            this.isTypeOtherCategoryTable = false;
        }
    }

    addCategory(): void {
        if (this.bodyParam.name.trim() !== '' && this.bodyParam.description.trim() !== '') {
            swal({
                title: 'Thêm danh mục ' + this.bodyParam.name + ' ?',
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
            }).then(
                ok => {
                    this.dataGlobalService.disBtnSubmit();
                    const data = {
                        name: this.bodyParam.name,
                        description: this.bodyParam.description,
                        type: this.typeCategory.category_type,
                    };
                    if (this.isTypeOtherCategoryTable) {
                        if (this.typeCategory.category_type === 'reasons') {
                            const body = {
                                name: this.bodyParam.name,
                                description: this.bodyParam.description,
                                type: this.typeCategory.category_type,
                                start_morning: $('#start-morning').val(),
                                end_morning: $('#end-morning').val(),
                                start_afternoon: $('#start-afternoon').val(),
                                end_afternoon: $('#end-afternoon').val(),
                            };
                            if (this.validateTime(body)) {
                                this.sub = this.categoryOtherService.createReasonCategory(body).subscribe(
                                    repo => {
                                        this.errorMsg = '';
                                        this.showDialogSuccess();
                                    }, error => {
                                        this.dataGlobalService.enableBtnSubmit();
                                        this.dataGlobalService.actionFail(error.error);
                                    }
                                );
                            } else {
                                this.dataGlobalService.enableBtnSubmit();
                                this.errorMsg = 'Thời gian không hợp lệ!';
                            }
                        } else {
                            this.subscription = this.categoryOtherService.create(data).subscribe(
                                res => {
                                    this.errorMsg = '';
                                    this.showDialogSuccess();
                                },
                                error => {
                                    this.dataGlobalService.enableBtnSubmit();
                                    this.dataGlobalService.actionFail(error.error);
                                }
                            );
                        }
                    } else {
                        this.subscription = this.service.create(this.typeCategory.category_type, this.bodyParam).subscribe(
                            res => {
                                this.errorMsg = '';
                                this.showDialogSuccess();
                            },
                            error => {
                                this.dataGlobalService.enableBtnSubmit();
                                this.dataGlobalService.actionFail(error.error);
                            }
                        );


                    }
                });

        } else {
            this.errorMsg = 'Thông tin dữ liệu không thể để trống';
        }


    }

    onBack(): void {
        window.history.back();
    }

    private validateTime(body: any) {
        const inteval1 = this.dataGlobalService.intervalTimeInDay(body.start_morning, body.end_morning);
        const inteval2 = this.dataGlobalService.intervalTimeInDay(body.end_morning, body.start_afternoon);
        const inteval3 = this.dataGlobalService.intervalTimeInDay(body.start_afternoon, body.end_afternoon);
        const lateThreshold = this.dataGlobalService.getTimeConfig().in_late_threshold;
        if (inteval1 >= lateThreshold && inteval2 >= lateThreshold && inteval3 >= lateThreshold) {
            return true;
        } else {
            return false;
        }
    }

    private showDialogSuccess() {
        this.dataGlobalService.enableBtnSubmit();
        setTimeout(function () {
            swal({
                title: 'Thành công!',
                text: 'Bạn vừa thêm một danh mục thành công !',
                type: 'success',
                confirmButtonText: 'Thoát'
            }).then(isConfirm => {
                if (isConfirm) {
                    window.history.back();
                }
            });
        }, 1000);
    }

}

