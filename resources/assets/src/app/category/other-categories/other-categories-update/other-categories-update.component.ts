import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {ActivatedRoute, ParamMap} from '@angular/router';
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
    selector: 'other-categories-update-cmp',
    moduleId: module.id,
    templateUrl: 'other-categories-update.component.html'
})

export class UpdateOtherCategoryComponent implements OnInit, OnDestroy {
     subUrl = '';
     typeCategory = {
        category_type: '',
        category_name: ''
    };
     BASE_URL = '/danh-muc/cap-nhat-danh-muc-khac';

     id: number;
     bodyParam: Body = {
        name: '',
        display_name: '',
        description: '',
    };
     errorMsg = '';
     subscription: Subscription;
     form: FormGroup;
     isTypeOtherCategoryTable = false;
     start_morning = '';
     end_morning = '';
     start_afternoon = '';
     end_afternoon = '';
     formUpdateTimeReasons: FormGroup;
     sub: Subscription;

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.sub !== undefined ? this.sub.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
                private fb: FormBuilder,
                private route: ActivatedRoute,
                public dataGlobalService: DataGlobalService,
                private categoryOtherService: CategoryOtherService,
                private service: CategoryService) {
    }


    ngOnInit() {
        this.getQueryParamRouter();
        if (!this.dataGlobalService.checkPemisson(this.BASE_URL)) {
            window.history.back();
        } else {

        }


    }

    getQueryParamRouter() {
        this.route.paramMap.subscribe((params: ParamMap) => this.subUrl = params.get('category-name'));
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.typeCategory.category_type = queryParamMap.get('category_type') === null ? '' : queryParamMap.get('category_type');
        this.typeCategory.category_name = queryParamMap.get('category_name') === null ? '' : queryParamMap.get('category_name');
        this.id = parseInt(queryParamMap.get('id') === null ? '' : queryParamMap.get('id'));
        this.bodyParam.name = queryParamMap.get('name') === null ? '' : queryParamMap.get('name');
        this.bodyParam.description = queryParamMap.get('description') === null ? '' : queryParamMap.get('description');
        if (this.typeCategory.category_type !== 'specialized_skills'
            && this.typeCategory.category_type !== 'job_positions'
            && this.typeCategory.category_type !== 'working_status') {
            this.isTypeOtherCategoryTable = true;
        } else {
            this.isTypeOtherCategoryTable = false;
        }
        if (this.typeCategory.category_type === 'reasons') {
            this.start_morning = queryParamMap.get('start_morning') === null ? '' : queryParamMap.get('start_morning');
            this.end_morning = queryParamMap.get('end_morning') === null ? '' : queryParamMap.get('end_morning');
            this.start_afternoon = queryParamMap.get('start_afternoon') === null ? '' : queryParamMap.get('start_afternoon');
            this.end_afternoon = queryParamMap.get('end_afternoon') === null ? '' : queryParamMap.get('end_afternoon');
            this.createForm();
        }
    }

    private createForm() {
        this.formUpdateTimeReasons = this.fb.group({
            start_morning_update: [this.start_morning, Validators.compose([Validators.required])],
            end_morning_update: [this.end_morning, Validators.compose([Validators.required])],
            start_afternoon_update: [this.start_afternoon, Validators.compose([Validators.required])],
            end_afternoon_update: [this.end_afternoon, Validators.compose([Validators.required])],
        });
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

    saveUpdate(): void {
        if (this.bodyParam.name.trim() !== '' && this.bodyParam.description.trim() !== '') {
            swal({
                title: 'Cập nhật ' + this.typeCategory.category_name + ' ?',
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
                    if (this.isTypeOtherCategoryTable) {
                        const data = {
                            name: this.bodyParam.name,
                            description: this.bodyParam.description,
                            type: this.typeCategory.category_type,
                        };
                        if (this.typeCategory.category_type === 'reasons') {
                            const body = {
                                name: this.bodyParam.name,
                                description: this.bodyParam.description,
                                type: this.typeCategory.category_type,
                                start_morning: $('#start-morning-update').val(),
                                end_morning: $('#end-morning-update').val(),
                                start_afternoon: $('#start-afternoon-update').val(),
                                end_afternoon: $('#end-afternoon-update').val(),
                            }
                            if (this.validateTime(body)) {
                                this.sub = this.categoryOtherService.updateReasonCategory(this.id, body).subscribe(
                                    repo => {
                                        this.errorMsg = '';
                                        this.updateCateSuccess();
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
                            this.subscription = this.categoryOtherService.update(this.id, data).subscribe(
                                res => {
                                    this.errorMsg = '';
                                    this.updateCateSuccess();
                                },
                                error => {
                                    this.dataGlobalService.enableBtnSubmit();
                                    this.dataGlobalService.actionFail(error.message);
                                });
                        }
                    } else {
                        this.bodyParam.display_name = this.bodyParam.name;
                        this.subscription = this.service.update(this.typeCategory.category_type, this.id, this.bodyParam).subscribe(
                            res => {
                                this.errorMsg = '';
                                this.updateCateSuccess();
                            },
                            error => {
                                this.dataGlobalService.enableBtnSubmit();
                                this.dataGlobalService.actionFail(error.error);
                            });
                    }
                }, cancel => {

                }
            );

        } else {
            this.errorMsg = 'Thông tin dữ liệu không thể để trống';
        }
    }

    onQuit(): void {
        window.history.back();
    }

    pathValue() {
    }

    private updateCateSuccess() {
        this.dataGlobalService.enableBtnSubmit();
        setTimeout(function () {
            swal({
                title: 'Thành công!',
                text: 'Cập nhật danh mục thành công !',
                type: 'success',
                confirmButtonText: 'Thoát'
            }).then(isConfirm => {
                if (isConfirm) {
                    window.history.back();
                }
            });
        }, 1000);
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
}

