import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Router} from '@angular/router';
import {DataGlobalService} from '../../../services/data.global.service';
import {CategoryService} from '../../../services/category/category.service';
import {Subscription} from 'rxjs/Subscription';
import * as moment from 'moment';

declare var $: any;
declare var swal: any;

declare interface Body {
    name: string;
    display_name: string;
    description: string;
}

@Component({
    selector: 'holiday-add-cmp',
    moduleId: module.id,
    templateUrl: 'holiday-add.component.html'
})

export class AddHolidayComponent implements OnInit, OnDestroy {
    errorMsg = '';
    numDate: number;
    form_holiday: FormGroup;
    subscription: Subscription;

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
                private fb: FormBuilder,
                private service: CategoryService,
                private dataGlobalService: DataGlobalService) {
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-muc/them-cac-ngay-nghi-le')) {
            window.history.back();
        } else {
            this.createForm();
            this.numDate = Date.now();
        }

    }

    private validateStartDate(start_date: any, end_date: any) {
        const date1 = moment(start_date, 'DD-MM-YYYY');
        const date2 = moment(end_date, 'DD-MM-YYYY');
        const duration = moment.duration(date2.diff(date1));
        console.log('duration: ' + duration.asDays());
        if (duration.asDays() >= 0) {
            return true;
        } else {
            return false;
        }
    }

    saveHoliday(): void {
        const data = {
            start_date: $('#start-time-holiday').val().trim(),
            end_date: $('#end-time-holiday').val().trim(),
            description: this.form_holiday.get('holiday_description').value.trim(),
        }
        console.log(data);
        if (data.start_date !== '' && data.end_date !== '' && data.description !== '') {
            if (this.validateStartDate(data.start_date, data.end_date)) {
                this.errorMsg = '';
                swal({
                    title: 'Tạo mới dịp nghỉ lễ ?',
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
                    this.subscription = this.service.create('official_holidays', data).subscribe(
                        res => {
                            if (res.status = 'success') {
                                setTimeout(function () {
                                    swal({
                                        title: 'Thành công!',
                                        text: 'Bạn vừa thêm dịp nghỉ lễ!',
                                        type: 'success',
                                        confirmButtonText: 'Thoát'
                                    }).then(isConfirm => {
                                        if (isConfirm) {
                                            window.history.back();
                                        }
                                    });
                                }, 1000);
                            } else {
                                swal(res.message);
                            }
                        },
                        error => {
                            this.dataGlobalService.actionFail(error.error);
                        });

                });
            } else {
                this.errorMsg = 'Ngày bắt đầu phải trước ngày kết thúc';
            }

        } else {
            this.errorMsg = 'Thông tin dữ liệu không thể để trống';
        }


    }


    initDatePicker(typeDate: string): void {
        if (typeDate === 'startDate') {
            $('#start-time-holiday').datetimepicker({
                format: 'DD-MM-YYYY',
                useCurrent: false,
                ignoreReadonly: true,
                daysOfWeekDisabled: [0, 6],
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

        } else {
            $('#end-time-holiday').datetimepicker({
                format: 'DD-MM-YYYY',
                useCurrent: false,
                ignoreReadonly: true,
                daysOfWeekDisabled: [0, 6],
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


    }

    onBack(): void {
        window.history.back();
    }


    private createForm() {
        this.form_holiday = this.fb.group({
            // holiday_name: ['', Validators.compose([Validators.required])],
            holiday_description: ['', Validators.compose([Validators.required])],
            start_time_holiday: ['', Validators.compose([Validators.required])],
            end_time_holiday: ['', Validators.compose([Validators.required])],
        });
    }
}