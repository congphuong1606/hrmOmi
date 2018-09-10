import {Component, OnInit, AfterViewInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl, Validators} from '@angular/forms';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../services/authSevice';
import {DataGlobalService} from '../services/data.global.service';
import {TimeCongig} from '../models/api/response/SettingReponse';
import {SettingService} from '../services/setting.service';
import * as constants from '../constants';

declare var $: any;
declare var swal: any;

@Component({
    selector: 'setting-cmp',
    moduleId: module.id,
    templateUrl: 'setting.component.html',

})

export class SettingComponent implements OnInit, AfterViewInit, OnDestroy {
    public numDate = Date.now();
    public formSettingTime: FormGroup;
    public timeConfig: TimeCongig;
    dateRemove = '16-06';

    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private settingService: SettingService,
                public dataGlobalService: DataGlobalService,
                private authService: AuthService) {
    }

    ngOnDestroy(): void {
    }

    ngOnInit() {
        this.timeConfig = this.dataGlobalService.getTimeConfig();
        if (this.timeConfig !== undefined) {
            this.createForm();
        }
    }

    ngAfterViewInit(): void {

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

    private createForm() {
        this.formSettingTime = this.fb.group({
            start_time_office_house: [this.timeConfig.start_morning, Validators.compose([Validators.required])],
            end_time_office_house: [this.timeConfig.end_afternoon, Validators.compose([Validators.required])],
            start_time_lunch_break: [this.timeConfig.end_morning, Validators.compose([Validators.required])],
            end_time_lunch_break: [this.timeConfig.start_afternoon, Validators.compose([Validators.required])],
            in_late_threshold: [this.timeConfig.in_late_threshold, Validators.compose([Validators.required])],
            time_off_registration_threshold: [this.timeConfig.time_off_registration_threshold, Validators.compose([Validators.required])],
            hr_and_administration_mail: [this.timeConfig.hr_and_administration_mail,
                Validators.compose([Validators.required, Validators.email])],
            bom_mail: [this.timeConfig.bom_mail, Validators.compose([Validators.required, Validators.email])],
        });
        this.dateRemove = this.timeConfig.time_off_reset_milestone;
    }

    resetTimeConfig() {
        this.formSettingTime.patchValue({
            start_time_office_house: this.timeConfig.end_morning,
            end_time_office_house: this.timeConfig.end_afternoon,
            start_time_lunch_break: this.timeConfig.end_morning,
            end_time_lunch_break: this.timeConfig.start_afternoon,
            in_late_threshold: this.timeConfig.in_late_threshold,
            time_off_registration_threshold: this.timeConfig.time_off_registration_threshold,
            hr_and_administration_mail: this.timeConfig.hr_and_administration_mail,
            bom_mail: this.timeConfig.bom_mail,
        });
    }

    updateSettingconfig(): void {
        swal({
            title: 'Các thông số đã cài đặt trước đó sẽ bị thay đổi',
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
            const body = {
                start_morning: $('#start-time-office-house').val(),
                end_afternoon: $('#end-time-office-house').val(),
                end_morning: $('#start-time-lunch-break').val(),
                start_afternoon: $('#end-time-lunch-break').val(),
                in_late_threshold: this.formSettingTime.get('in_late_threshold').value,
                time_off_registration_threshold: this.formSettingTime.get('time_off_registration_threshold').value,
                hr_and_administration_mail: this.formSettingTime.get('hr_and_administration_mail').value,
                bom_mail: this.formSettingTime.get('bom_mail').value,
                time_off_reset_milestone: $('#option-date-remove option:selected').text().trim() + '-' +
                $('#option-month-remove option:selected').text().trim(),
            };
            this.settingService.update(body).subscribe(
                res => {
                    console.log(res);
                    localStorage.setItem(constants.TIME_CONFIG, JSON.stringify(res.setting as TimeCongig));
                    swal(
                        'Thành công',
                        'Đã thay đổi thông số cài đặt',
                        'success'
                    );
                }, error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }
}

